<?php

use Illuminate\Http\Request;

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

Route::get('/user', function(){
	return Auth::user()->id;
});

Route::post('/saveOrUpdate','Api\ReportController@saveOrUpdate');