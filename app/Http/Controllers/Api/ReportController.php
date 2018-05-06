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
