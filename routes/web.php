<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('front');
});

Route::get('userContact', 'UserController@userContact');  

// Route::get('/', function () {
//     return view('responder');
// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/signin',function(){
	return view('auth/login');
});


Route::get('/user-reports', 'ReportPersonalController@userReports');
Route::get('/user-reports/user-info/{id}', 'ReportPersonalController@getUserInfo');
Route::post('/user-reports/validate/{id}/{userid}/{resolve?}', 'ReportPersonalController@validate');
Route::post('/user-reports/reject/{id}/{userid}', 'ReportPersonalController@reject');
