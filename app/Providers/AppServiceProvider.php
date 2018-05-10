<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Redis;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Ticket::updated(function($ticket) {
            // sample service
        });

        \App\Notification::created(function($notification) {
            Redis::publish('message', json_encode([
                'pchannel'=>'notification',
                'event'=>'created',
                'obj'=>$notification
            ]));
        });
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
