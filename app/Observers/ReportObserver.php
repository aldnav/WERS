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
      $users = User::where('user_role', 1)
              ->get();
      foreach ($users as $user) {
        $user->notify(new NewReport($report));
      }
    }
}
