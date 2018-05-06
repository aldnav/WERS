<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', ['as' => 'user-register', 'uses'=>'Auth\RegisterController@create']);
// Route::post('/login', ['as' => 'user-login', 'uses'=>'Auth\LoginController@login']);

Route::get('incidentTypes', 'Api\ReportController@incidentTypes');  


Route::get('userContact', 'UserController@userContact');  

Route::post('/saveOrUpdate','Api\ReportController@saveOrUpdate');

Route::post('/respondReport','Api\ReportController@respondReport');

Route::post('/recent-reports', function () {
    $center = request('place');
	$lat = $center['lat'];
	$lng = $center['lng'];
    $status = request('status');
  
	$distance = request('radius');
	$results = DB::select(DB::raw('SELECT reports.id AS report_id, reports.lat AS latitude, reports.lng AS longitude, address, incidents.name AS incident_name, body, users.name AS username,  DATE_FORMAT(reports.created_at, "%M %d, %Y %I:%i %p") AS report_date, users.contact_number AS contact_number, users.id AS user_id, (3959 * acos(cos(radians(' . $lat . ')) * cos(radians(reports.lat)) * cos(radians(reports.lng) - radians(' . $lng . ')) + sin (radians(' . $lat . ')) * sin (radians(reports.lat)))) AS distance FROM reports LEFT JOIN users ON reports.owner_id=users.id LEFT JOIN incidents ON reports.incident_id = incidents.id WHERE reports.is_validated='.$status.' AND reports.is_resolved=0 AND reports.is_rejected=0 HAVING distance <
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
        ];
    });


    $data=[
        'status'=>'success',
        'markers'=>$markers,
        'results'=>$formattedResults,
        'radius'=>$distance
    ];
    return response($data,200);


});