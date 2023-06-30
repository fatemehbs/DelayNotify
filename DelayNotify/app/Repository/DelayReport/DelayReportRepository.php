<?php

namespace App\Repository\DelayReport;

use App\Models\DelayReport;
use App\Repository\Base\BaseRepository;
use Illuminate\Support\Facades\DB;

class DelayReportRepository extends BaseRepository implements DelayReportRepositoryInterface
{
    /**
     * DelayReportRepository constructor.
     */
    public function __construct(DelayReport $delayReport)
    {
        parent::__construct($delayReport);
    }


    public function getVendorsWithDelay($count)
    {
        $limited_report_day = config('delay_report.limited_report_day');
        $timestamp = now()->subDays($limited_report_day);
        return DelayReport::leftJoin('orders', 'orders.id', 'delay_reports.order_id')
            ->leftJoin('vendors', 'vendors.id', 'orders.vendor_id')
            ->where('delay_reports.created_at', '>=', $timestamp)
            ->orderBy('total_delay_time', 'desc')
            ->groupBy('vendors.id')
            ->select([
                'vendors.description',
                'vendors.status',
                'vendors.title',
                'vendors.type',
                'vendors.code',
                'vendors.id',
                DB::raw('SUM(estimated_delay_time) as total_delay_time')
            ])
            ->paginate($count);
    }
}
