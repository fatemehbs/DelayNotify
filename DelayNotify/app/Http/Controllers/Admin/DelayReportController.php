<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReportDelaysAssignToAgentRequest;
use App\Http\Resources\Admin\VendorDelaysReportResource;
use App\Http\Responses\ErrorResponseWithCode;
use App\Http\Responses\SuccessFulResponse;
use App\Models\Agent;
use App\Models\DelayReport;
use App\Models\Order;
use App\Repository\DelayReport\DelayReportRepositoryInterface;
use App\Utility\CacheUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DelayReportController extends Controller
{
    public DelayReport $model;
    private CacheUtils $delayQueue;
    private DelayReportRepositoryInterface $delayReportRepository;

    public function __construct(
        DelayReport $model,
        CacheUtils $cache,
        DelayReportRepositoryInterface $delayReportRepository
    )
    {
        $this->model = $model;
        $this->delayQueue = $cache;
        $this->delayReportRepository = $delayReportRepository;
    }

    /**
     * @param ReportDelaysAssignToAgentRequest $request
     * @return ErrorResponseWithCode|SuccessFulResponse
     */
    public function assignToAgent(Request $request): ErrorResponseWithCode|SuccessFulResponse
    {
        $validator = Validator::make($request->all(), [
            'agent_id' => 'required'
        ]);
        if ($validator->fails()) {
            return new ErrorResponseWithCode(__('validation.required', ['attribute' => 'شماره کاربری']));
        }

        $agentId = (int) $request->input('agent_id');
        $agent = Agent::createAgentIfNotExisted($agentId);

        if ($agent->pendingOrdersCount() > 0) {
            return new ErrorResponseWithCode(__('delay_report.error.agent_busy'));
        }
        if($this->delayQueue->isEmpty(config('delay_report.redis_key'))) {
            return new ErrorResponseWithCode(__('delay_report.error.no_delayed_orders_found'), 400);
        }

        $orderId = $this->delayQueue->dequeue(config('delay_report.redis_key'));
        $order = Order::findOrFail($orderId);
        $delayReport = $order->delayReports
            ->where('status', DelayReport::STATUS_PENDING)
            ->where('order_id', $orderId)
            ->sortBy('created_at')
            ->first();
        if ($delayReport) {
            $delayReport->agent_id = $agentId;
            $delayReport->status = DelayReport::STATUS_IN_PROGRESS;
            $delayReport->save();
        }
        return new SuccessFulResponse('با موفقیت انجام شد');
    }

    public function getVendorDelays(Request $request)
    {
        $count = $request->query('count', 10);
        $data = $this->delayReportRepository->getVendorsWithDelay($count);
        $output = $data->toArray();
        $output['data'] = VendorDelaysReportResource::collection($data);
        $output['total_pages'] = $output['last_page'];
        return $output;
    }
}
