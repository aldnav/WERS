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
           
            $report = Report::create($request->all());
            
//            return redirect()->back()->with('message', 'IT WORKS!');
            //return Report::json($report);
        }

        public function incidentTypes(){
            return response()->json(['incidents'=>Incident::all()]);
        }

    }
