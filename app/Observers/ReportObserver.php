<?php

namespace App\Observers;

use App\Notifications\NewReport;
use App\Report;

class ReportObserver
{
    public function created(Report $report)
    {
        // foreach ($user->followers as $follower) {
        //     $follower->notify(new NewPost($user, $post));
        // }
    }
}
