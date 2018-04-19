<?php

    namespace App\Http\Controllers\Api;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Incident;

    class IncidentController extends Controller
    {

        public function getIncidents()
        {
//            $listIncidents = Incident::select('id','name')->get();
            return response()->json(['incidents'=>Incident::all()]);
        }

    }
