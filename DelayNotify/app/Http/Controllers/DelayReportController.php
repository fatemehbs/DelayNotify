<?php

namespace App\Http\Controllers;

use App\Classes\DeliveryTimeService;
use App\Http\Responses\ErrorResponseWithCode;
use App\Http\Responses\SuccessFulResponse;
use App\Models\DelayReport;
use App\Models\Order;
use App\Models\Trip;
use App\Models\User;
use App\Utility\CacheUtils;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DelayReportController extends Controller
{
    private DelayReport $model;
    private CacheUtils $delayQueue;
    private DeliveryTimeService $deliveryTimeService;

    public function __construct(
        DeliveryTimeService $deliveryTimeService,
        DelayReport $model,
        CacheUtils $cache
    )
    {
        $this->model = $model;
        $this->delayQueue = $cache;
        $this->deliveryTimeService = $deliveryTimeService;
    }


    /**
     * @param Request $request
     * @param int $orderId
     * @return ErrorResponseWithCode|SuccessFulResponse
     * @throws ValidationException
     */
    public function reportDelay(Request $request, int $orderId): ErrorResponseWithCode|SuccessFulResponse
    {
        $validatedData = $this->validateData($request);
        if ($validatedData->fails()) {
            return new ErrorResponseWithCode(__('validation.required', ['attribute' => 'یوزرآیدی']));
        }

        $description = $request->input('description', ' ');
        $userId = $request->input('user_id');
        User::createUserIfNotExisted($userId);

        $order = Order::findOrFail($orderId);
        $deliveryTime = $order->created_at->addMinutes($order->delivery_time);
        $delayTime = now()->diffInMinutes($deliveryTime);

        if ($deliveryTime >= now()) {
            return new ErrorResponseWithCode(__('delay_report.error.before_delivery_time'));
        }

        $trip = Trip::where('order_id', $orderId)->first();
        DB::beginTransaction();
        try {
            if (optional($trip)->status && in_array($trip->status, [
                    Trip::STATUS_AT_VENDOR,
                    Trip::STATUS_ASSIGNED,
                    Trip::STATUS_PICKED])
            ) {
                $estimatedDeliveryTime = $this->deliveryTimeService->getEstimatedDeliveryTime();
                $order->delivery_time = $estimatedDeliveryTime;
                $order->save();
            } else {
                if (!$order->in_delay_queue) {
                    $order->in_delay_queue = true;
                    $order->save();
                    $this->enqueueOrderToDelayQueue(config('delay_report.redis_key'), $orderId);
                }
            }

            $this->model->create([
                'estimated_delay_time' => $delayTime,
                'status' => DelayReport::STATUS_PENDING,
                'order_id' => $orderId,
                'description' => $description,
                'user_id' => $userId,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return new ErrorResponseWithCode(__('delay_report.error.server_error'), 500);
        }
        return new SuccessFulResponse('با موفقیت ثبت شد');
    }

    private function enqueueOrderToDelayQueue($key, $orderId)
    {
        $this->delayQueue::enqueue($key, $orderId);
    }

    private function validateData(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'user_id' => 'required|numeric'
        ]);
    }
}
