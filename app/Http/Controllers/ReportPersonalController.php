<?php

namespace App\Http\Controllers;

use App\User;
use App\Report;
use Illuminate\Http\Request;
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

    public function validate(Request $request, $id, $userid, $resolve = 0) {
        $report = Report::find($id)->where('owner_id', '=', $userid)->first();
        $report->is_validated = true;
        if ($resolve == 1) {
            $report->is_resolved = true;
        }
        if ($request->resolution_note) {
            $report->resolution_note = 'Validated.' . $request->resolution_note;
        }
        $report->save();
    }

    public function reject(Request $request, $id, $userid) {
        $report = Report::find($id)->where('owner_id', '=', $userid)->first();
        $report->is_rejected = true;
        if ($request->resolution_note) {
            $report->resolution_note = 'Rejected.' . $request->resolution_note;
        }
        $report->save();
    }
}