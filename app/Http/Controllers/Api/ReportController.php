<?php

    namespace App\Http\Controllers\Api;

    use App\Report;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Incident;
    use \Auth;

    class ReportController extends Controller
    {


        public function saveOrUpdate(Request $request)
        {
            $report = Report::create($request->all());            
        }

        public function incidentTypes(){
            return response()->json(['incidents'=>Incident::all()]);
        }

        public function userContact(){
            $user = Auth::user();
            return response()->json(['result'=>$user]);
        }


    }
