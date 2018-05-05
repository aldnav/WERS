<?php

namespace App\Http\Controllers;

use App\User;
use App\Report;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Auth\Authenticatable;

class ReportPersonalController extends Controller
{
    public function userReports(Authenticatable $user) {

        $reports = User::find($user->id)->reports;

        return response()->json([
            'result'=>$reports
            // 'result'=>Report::all()
        ]);
    }

    public function getUserInfo($id) {
        
        $user = User::find($id);

        return response()->json([
            'name'=>$user->name,
            'avatar_url'=>'/images/avatar.png'
        ]);
    }

    public function validate($id, $userid, $resolve = 0) {
        $report = Report::find($id)->where('owner_id', '=', $userid)->first();
        $report->is_validated = true;
        if ($resolve == 1) {
            $report->is_resolved = true;
        }
        $report->save();
    }
}
