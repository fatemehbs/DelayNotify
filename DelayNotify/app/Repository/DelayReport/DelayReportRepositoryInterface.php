<?php

namespace App\Repository\DelayReport;

use App\Repository\Base\BaseRepositoryInterface;

interface DelayReportRepositoryInterface extends BaseRepositoryInterface
{
    public function getVendorsWithDelay($count);
}
