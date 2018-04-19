<?php

    namespace App\Http\Controllers\Api;

    use App\Report;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Incident;

    class ReportController extends Controller
    {


        public function saveOrUpdate(Request $request)
        {
            /*fix bug here*/

            $report = new Report;
            $data = $request->all();
            $report->id = array_get($data,'id');
            $report->body =array_get($data,'body');
            $report->incident_id = array_get($data,'incident_id');
            $report->lat =array_get($data,'lat');
            $report->lng = array_get($data,'lng');
            $report->owner_id = array_get($data,'owner_id');
            $report->status = array_get($data,'status');
            $result = $report->save();
            if($result){
                response()->json(array('response'=>'Record successfully saved.'));
            }
            else{
                response()->json(array('response'=>'Failed to save.'));
            }

            return Response::json($result);
            // $this.validate($request, [
            //     'body' => 'required']);

            //           
            // console.log($request);
            // $report = Report::create([ 'body'=> $request('body'),
            //     'owner_id'=> $request('owner_id'),
            //     'lat'=>$request('lat'),
            //     'lng'=>$request('lng'),
            //     'incident_id'=>$request('incident_id'),
            //     'status'=>$request('status')]);
            
            // return response()->json(array('response'=>'Failed to save.'));
            //return Report::json($report);
        }

        public function incidentTypes(){
            return response()->json(['incidents'=>Incident::all()]);
        }

    }
