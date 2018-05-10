<?php

namespace App\Observers;

use App\Notifications\NewReport;
use Illuminate\Support\Facades\Log;
use App\Report;
use App\User;

class ReportObserver
{
    public function created(Report $report)
    {
      $users = User::all();
      foreach ($users as $user) {
        Log::debug('An informational message.');
        $user->notify(new NewReport($report));
      }
    }
}
