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


Route::get('userContact', 'Api\ReportController@userContact');  

Route::post('/saveOrUpdate','Api\ReportController@saveOrUpdate');

Route::post('/recent-reports', function () {
	$lat = request('lat');
	$lng = request('lng');
	$distance = 200;
	$results = DB::select(DB::raw('SELECT lat, lng, address, incidents.name AS incident_name, body, users.name AS username,  DATE_FORMAT(reports.created_at, "%M %d, %Y %I:%i %p") AS report_date, users.contact_number AS contact_number, (3959 * acos(cos(radians(' . $lat . ')) * cos(radians(lat)) * cos(radians(lng) - radians(' . $lng . ')) + sin (radians(' . $lat . ')) * sin (radians(lat)))) AS distance FROM reports LEFT JOIN users ON reports.owner_id=users.id LEFT JOIN incidents ON reports.incident_id = incidents.id  HAVING distance < 20 ORDER BY report_date desc') );

	$markers = collect($results)->map(function ($item, $key) {
        return [
            'position' => ['lat' => floatval($item->lat), 'lng' =>  floatval($item->lng)],
            'name'=>$item->address,
            'incident'=>$item->incident_name,
            'report'=>$item->body,
            'reporter'=>$item->username,
            'report_date'=>$item->report_date,
            'contact_number'=>$item->contact_number
        ];
    });

    $formattedResults = collect($results)->map(function ($item, $key) {
        return [
            'text'=>$item->address,
            'latlng'=>"lat: {$item->lat} lng: {$item->lng}",
            'incident'=>$item->incident_name
        ];
    });


    $data=[
        'status'=>'success',
        'markers'=>$markers,
        'results'=>$formattedResults
    ];
    return response($data,200);


});