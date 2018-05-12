<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('APP_ENV') === 'prod') {
            URL::forceScheme('https'); // force https
        }
        /**
         * Notification
         * 1. Who to send notif
         * 2. what is the action done
         * 3. what is the id of the target object
         * 4. what is the type of the target object
         */

        \App\Report::updated(function($report) {
            
            $ticket = $report->ticket;
            if ($report->is_validated) {
                $event = '';
                switch($ticket->status) {
                    case 0:
                        $event = 'rejected';
                        break;
                    case 2:
                        $event = 'assigned';
                        break;
                    case 3:
                        $event = 'resolved';
                        break;                    
                }
                if ($event != '') {
                    // reporter's view
                    \App\Notification::notify(
                        $report->owner_id,
                        'reporter:'.$event,
                        $report->id,
                        'report');
                    
                    // responder's view
                    if ($event  == 'assigned') {
                        \App\Notification::notify(
                            $ticket->responder_id,
                            'responder:'.$event,
                            $report->id,
                            'report');
                    }
                }
            }

        });

        \App\Notification::created(function($notification) {
            // Redis::publish('message', json_encode([
            //     'pchannel'=>'notification',
            //     'event'=>'created',
            //     'obj'=>$notification
            // ]));
            $options = array(
                'cluster' => 'ap1',
                'encrypted' => true
            );
            $pusher = new \Pusher\Pusher(
                '3b1cc74f234a6626b808',
                'a5deb3c87f88f844b51e',
                '524677',
                $options
            );

            $data['message'] = json_encode([
                'pchannel'=>'notification',
                'event'=>'created',
                'obj'=>$notification
            ]);
            $pusher->trigger(
                'notif-'.$notification->owner_id,
                'notification',
                $data);
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
