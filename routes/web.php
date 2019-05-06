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
Route::get('/userapi','Api\UserApiController@userapi');
Route::get('/test','Api\UserApiController@test');
//作业 三种post请求
Route::get('/tests','Api\UserApiController@tests');
Route::get('/times','Api\UserApiController@times')->Middleware('teststimes'); //中间件测试
Route::post('/reg','Api\UserApiController@posts');
Route::post('/login','Api\UserApiController@logn');

