<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
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
		return 'it is called'.$request->body;
	}
}
