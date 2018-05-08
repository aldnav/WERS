<?php

namespace App\Http\Controllers;

use App\User;
use App\Report;
use App\Ticket;
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

        $report = Report::find($id);

        $ticket = $report->ticket;
        $responder = Ticket::find($ticket->id)->responder;

        if(!$responder)
            $ticket->responder_id = $userid;

        $report->is_validated = true;
        
        if ($resolve == 1) {
            $report->is_resolved = true;
            $ticket->status = 3;
        }else{
           $ticket->status = 2;
        }
        if ($request->resolution_note) {
            $report->resolution_note = 'Validated.' . $request->resolution_note;
        }
        $ticket->save();


        $report->save();
        return response()->json(['result'=>$ticket]);
    }

    public function reject(Request $request, $id, $userid) {
        $report = Report::find($id);
        $ticket = $report->ticket;
        $report->is_rejected = true;
        $tiket->responder_id=$userid;
        $ticket->status=2;
        if ($request->resolution_note) {
            $report->resolution_note = 'Rejected.' . $request->resolution_note;
        }
        $report->save();
        $ticket->save();
    }

     //Report
        //isvalidated
        //isresolved

        //Ticket
        //0 - rejected
        //1 - unassigned
        //2 - assigned
        //3 - resolved

        //validate:
        //isvalidated=true
        //ticket status = 2 (assigned)
        
        //validate and resolve:
        //isvalidated = true;
        //isresolved = true
        //ticket resolved = 3 (resolved)

        //rejected:
        //ticket status = 0
        //report isrejected = true
    
        public function stats() {
            // 1 fire
            // 2 flood
            // 3 road accidents
            // 4 landslide
            $count_fires = Report::where('incident_id', 1)->count();
            $count_flood = Report::where('incident_id', 2)->count();
            $count_road = Report::where('incident_id', 3)->count();
            $count_landslide = Report::where('incident_id', 4)->count();
            return response()->json(['result'=> [
                ['icon' => 'fire', 'count' => $count_fires, 'text'=> 'Fire'],
                ['icon' => 'flood', 'count' => $count_flood, 'text'=> 'Flood'],
                ['icon' => 'road', 'count' => $count_road, 'text'=> 'Vehicular Accidents'],
                ['icon' => 'landslide', 'count' => $count_landslide, 'text'=> 'Landslide'],
            ]]);
        }
}
