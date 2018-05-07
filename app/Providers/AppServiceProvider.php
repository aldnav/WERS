<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\ReportObserver;
use App\Report;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Report::observe(ReportObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
