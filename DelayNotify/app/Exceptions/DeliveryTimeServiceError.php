<?php

namespace App\Exceptions;

use App\Exceptions\Base\DoNotReportException;

class DeliveryTimeServiceError extends DoNotReportException
{
    public function render($request)
    {
        return response()->json(
            [
                'errorCode' => -1,
                'errorMessage' => __('delay_report.error.delivery_time_service_error'),
            ],
            400
        );
    }
}
