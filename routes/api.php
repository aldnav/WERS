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

Route::post('/recent-reports', 'Api\ReportController@recentReports');