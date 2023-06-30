<?php

namespace App\Providers;

use App\Repository\DelayReport\DelayReportRepository;
use App\Repository\DelayReport\DelayReportRepositoryInterface;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->bind(DelayReportRepositoryInterface::class, DelayReportRepository::class);
    }
}
