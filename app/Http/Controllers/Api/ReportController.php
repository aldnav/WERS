<?php

    namespace App\Http\Controllers\Api;

    use Redis;
    use App\Report;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Incident;
    use App\Ticket;
    use \Auth;
    use Illuminate\Support\Facades\DB;
    
    class ReportController extends Controller
    {

        public function trackStatus($rep)
        {   
            return view('reports.status', compact('rep'));
        }

        public function saveOrUpdate(Request $request)
        {
            $report = Report::create($request->all());
            $ticket = Ticket::create(['report_id' => $report->id, 'status'=>1]);
            
            $responders=self::nearbyResponders(request('lat'),request('lng'));

            foreach($responders as $responder){
                \App\Notification::notify(
                $responder['user_id'],
                'responder:nearby',
                $report->id,
                'report');
            }


            \App\Notification::notify(
                $report->owner_id,
                'created',
                $report->id,
                'report');

            return response()->json(['responders'=>$responders]);
        }


        public function incidentTypes(){
            return response()->json(['incidents'=>Incident::all()]);
        }

        public function recentReports(){
            $center = request('place');
            $lat = $center['lat'];
            $lng = $center['lng'];
            $status = request('status');
            $userId = request('id');
            $userIdQuery='';
            $distance = request('radius');

            if($status==1){
                $userIdQuery = 'AND tickets.responder_id ='.$userId;
            }
            
            $results = DB::select(DB::raw('SELECT reports.id AS report_id, round(reports.lat,3) AS latitude, round(reports.lng,3) AS longitude, address, incidents.name AS incident_name, body, users.name AS username,  DATE_FORMAT(reports.created_at, "%M %d, %Y %I:%i %p") AS report_date, reports.created_at AS unform_date, reports.contact_number AS contact_number, users.id AS user_id, (3959 * acos(cos(radians(' . $lat . ')) * cos(radians(reports.lat)) * cos(radians(reports.lng) - radians(' . $lng . ')) + sin (radians(' . $lat . ')) * sin (radians(reports.lat)))) AS distance FROM reports INNER JOIN users ON reports.owner_id=users.id INNER JOIN incidents ON reports.incident_id = incidents.id INNER JOIN tickets ON tickets.report_id = reports.id WHERE reports.is_validated='.$status.' AND reports.is_resolved=0 AND reports.is_rejected=0 '.$userIdQuery.' HAVING distance <
                 '.$distance.' ORDER BY report_date desc') );

            $markers = collect($results)->map(function ($item, $key) {
                return [
                    'position' => ['lat' => floatval($item->latitude), 'lng' =>  floatval($item->longitude)],
                    'name'=>$item->address ? $item->address :"lat: {$item->latitude} lng: {$item->longitude}",
                    'incident'=>$item->incident_name,
                    'report'=>$item->body,
                    'reporter'=>$item->username,
                    'report_date'=>$item->report_date,
                    'contact_number'=>$item->contact_number,
                    'owner_id'=>$item->user_id,
                    'id' => $item->report_id
                ];
            });

            $formattedResults = collect($results)->map(function ($item, $key) {
                return [
                    'report_id'=>$item->report_id,
                    'text'=>$item->address,
                    'latlng'=>"lat: {$item->latitude} lng: {$item->longitude}",
                    'incident'=>$item->incident_name,
                    'report_date'=>$item->report_date, 
                    'distance'=> $item->distance,
                    'unform_date'=> $item->unform_date,
                ];
            });


            $data=[
                'status'=>'success',
                'markers'=>$markers,
                'results'=>$formattedResults,
                'radius'=>$distance
            ];
            return response($data,200);
        }

        public static function nearbyResponders($lat, $lng){
            //Log::info('This is some useful information.');

             $results = DB::select(DB::raw('SELECT users.id AS user_id, (3959 * acos(cos(radians(' . $lat . ')) * cos(radians(users.lat)) * cos(radians(users.lng) - radians(' . $lng . ')) + sin (radians(' . $lat . ')) * sin (radians(users.lat)))) AS distance FROM users WHERE users.user_role=1 HAVING distance <
                 40')) ;   

            //  $formattedResults = collect($results)->map(function ($item, $key) {
            //     return [
            //         'id'=>$item->user_id
            //     ];
            // });

            $formattedResults= json_decode(json_encode($results), true);

            return $formattedResults;

            //  $data=[
            //     'status'=>'success',
            //     'results'=>$formattedResults,
            // ];

            // return response()->json(['results'=>$data]);

        }

        public function userReports(){
            $status = request('status');
            $userId = request('id');
            if($status==2)
            {
                $status=1;
                $is_resolved=1;
                $is_rejected=0;
            }
            else if($status==3)
            {
                $status=0;
                $is_resolved=0;
                $is_rejected=1;
            } 
            else
            {
                $is_resolved=0;
                $is_rejected=0;
            }
            
            $results = DB::select(DB::raw('SELECT reports.id AS report_id, round(reports.lat,3) AS latitude, round(reports.lng,3) AS longitude, address, incidents.name AS incident_name, body, users.name AS responder,  DATE_FORMAT(reports.created_at, "%M %d, %Y %I:%i %p") AS report_date, reports.created_at AS unform_date, users.contact_number AS contact_number FROM reports 
                INNER JOIN incidents ON reports.incident_id = incidents.id 
                LEFT JOIN tickets ON tickets.report_id = reports.id 
                LEFT JOIN users ON tickets.responder_id=users.id 
                WHERE reports.is_validated='.$status.' AND reports.is_resolved='.$is_resolved.' AND reports.is_rejected='.$is_rejected.' AND reports.owner_id ='.$userId.' ORDER BY report_date desc'));

            $markers = collect($results)->map(function ($item, $key) {
                return [
                    'position' => ['lat' => floatval($item->latitude), 'lng' =>  floatval($item->longitude)],
                    'name'=>$item->address ? $item->address :"lat: {$item->latitude} lng: {$item->longitude}",
                    'incident'=>$item->incident_name,
                    'report'=>$item->body,
                    'report_date'=>$item->report_date,
                    'unform_date'=>$item->unform_date,
                    'contact_number'=>$item->contact_number,
                    'id' => $item->report_id,
                    'responder'=>$item->responder
                ];
            });


            $data=[
                'status'=>'success',
                'markers'=>$markers,
            ];

            return response($data);
        }

        public function reportTracker(){
            $repid = request('repid');
            $userId = request('userid');
            $filter='';
            if($repid!=0)
                $filter='AND reports.id='.$repid;


            $results = DB::select(DB::raw('SELECT reports.id AS report_id, round(reports.lat,3) AS latitude, round(reports.lng,3) AS longitude, address, incidents.name AS incident_name, body, users.name AS responder,  DATE_FORMAT(reports.created_at, "%M %d, %Y %I:%i %p") AS report_date, reports.created_at AS unformatted_date, users.contact_number AS contact_number, reports.is_validated AS is_valid, reports.is_rejected AS is_reject, tickets.status AS ticket_status FROM reports 
                INNER JOIN incidents ON reports.incident_id = incidents.id 
                LEFT JOIN tickets ON tickets.report_id = reports.id 
                LEFT JOIN users ON tickets.responder_id=users.id 
                WHERE reports.owner_id= '.$userId.' '.$filter.' ORDER BY report_date desc'));
        
            $reports = collect($results)->map(function ($item, $key) {
                $statuses = ['REJECTED', 'PENDING', 'VALIDATED','RESOLVED'];
                $status = '';
                if($item->is_reject==1){
                    $status=$statuses[0];
                } else if($item->is_valid == 0){
                    $status=$statuses[1];
                }  else if($item->is_valid==1){
                    $status=$statuses[2];
                } else {
                    $status=$statuses[3];
                }
                return [
                    'report_id' => $item->report_id,
                    'address' => $item->address ? $item->address :"lat: {$item->latitude} lng: {$item->longitude}",
                    'incident'=>$item->incident_name,
                    'details'=> $item->body,
                    'report_date'=> $item->report_date,
                    'unformatted_date'=>$item->unformatted_date,
                    'contact_number'=>$item->contact_number,
                    'responder'=>$item->responder,
                    'status'=>$status 
                ];
            });

            $data=[
                'reports'=>$reports,
            ];

            return response($data);
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

        /*
        * 0 - rejected
        * 1 - Pending
        * 2 - validated
        * 3 - resolved
        * 
        */
    }
