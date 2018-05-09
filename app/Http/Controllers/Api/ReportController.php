<?php

    namespace App\Http\Controllers\Api;

    use App\Report;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Incident;
    use App\Ticket;
    use \Auth;
    use Illuminate\Support\Facades\DB;
    
    class ReportController extends Controller
    {


        public function saveOrUpdate(Request $request)
        {
            $report = Report::create($request->all());
            Ticket::create(['report_id' => $report->id, 'status'=>1]);           
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
            if($status==1){
                $userIdQuery = 'AND tickets.responder_id ='.$userId;
            }
            $distance = request('radius');
            $results = DB::select(DB::raw('SELECT reports.id AS report_id, reports.lat AS latitude, reports.lng AS longitude, address, incidents.name AS incident_name, body, users.name AS username,  DATE_FORMAT(reports.created_at, "%M %d, %Y %I:%i %p") AS report_date, users.contact_number AS contact_number, users.id AS user_id, (3959 * acos(cos(radians(' . $lat . ')) * cos(radians(reports.lat)) * cos(radians(reports.lng) - radians(' . $lng . ')) + sin (radians(' . $lat . ')) * sin (radians(reports.lat)))) AS distance FROM reports INNER JOIN users ON reports.owner_id=users.id INNER JOIN incidents ON reports.incident_id = incidents.id INNER JOIN tickets ON tickets.report_id = reports.id WHERE reports.is_validated='.$status.' AND reports.is_resolved=0 AND reports.is_rejected=0 '.$userIdQuery.' HAVING distance <
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


    }
