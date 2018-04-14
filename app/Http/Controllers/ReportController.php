<?php

namespace App\Http\Controllers;
use App\Report;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Response;

class ReportController extends HomeController
{
    //

    //use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


	public function ajaxDemo(Request $request)
	{
		if($request->method() == "POST")
		{
			Report::create($request->all());
			return response()->json(array("result"=>"success"));
		}
		else
		{
			return view('/front');
		}
	}

	public function saveOrUpdate(Request $request)
	{
		$report = new Report;
		$report->id = $request->id;
		$report->body = $request->body;
		$report->incident_id = $request->incident_id;
		$report->lat = $request->lat;
		$report->lng = $request->lng;
		$report->owner_id = $request->owner_id;
		$report->body=$request->body;
		$report->status = $request->status;
		$result = $report->save();
		if($result){
			response()->json(array('response'=>'Record successfully saved.'));
		}
		else{
			response()->json(array('response'=>'Failed to save.'));
		}
		return Response::json($result);
	}
}
