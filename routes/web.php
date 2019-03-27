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
    return view('welcome');
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/apitest', 'Api\ApiController@apitest');
Route::any('/login', 'Api\ApiController@login');
Route::post('/api', 'Api\ApiController@api');

//login
Route::get('/userreg','User\UserController@reg');
Route::post('/userreg','User\UserController@doReg');

Route::get('/userlogin','User\UserController@login');
Route::post('/userlogin','User\UserController@doLogin');
Route::get('/usercenter','User\UserController@center')->middleware('check.login');

Route::post('/user/reg','User\UserController@apiReg');
Route::post('/user/login','User\UserController@test');
Route::post('/user/token','User\UserController@token');
Route::post('/user/quit','User\UserController@quit');

Route::post('/hb/reg','HBulider\TestController@reg');
Route::post('/hb/login','HBulider\TestController@login');


