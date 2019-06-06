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
  //  ->Middleware('checktokenlogin');

Route::get('/userapi','Api\UserApiController@userapi');
Route::get('/test','Api\UserApiController@test');
//作业 三种post请求
Route::get('/tests','Api\UserApiController@tests');
Route::get('/times','Api\UserApiController@times')->Middleware('teststimes'); //中间件测试
//注册
Route::post('/reg','Api\UserApiController@posts');
//登陆
Route::post('/login','Api\UserApiController@logn');
//个人中心
Route::get('/users','Api\UserApiController@myUser')->Middleware(['checklogin','teststimes']);
//资源控制器
Route::resource('/goods', \goods\GoodsController::class);
//作业 对称加密
Route::get('/encode','Api\UserApiController@dncrypt');
//凯撒加密
Route::get('/kaisa','Api\UserApiController@kaisa');
//发送加密数据
Route::get('/encrypt','Api\UserApiController@encrypt');
//非对称加密传送数据
Route::get('/rsas','Api\UserApiController@rsa');
//签名
Route::get('/qianming','Api\UserApiController@autograph');
//注册页面
Route::get('/user','Api\UserApiController@user');
//周考注册
Route::post('/userinfo','Api\UserApiController@userinfo');


//h5+app注册
Route::post('/appuser','passport\PassportController@user');
//登陆
Route::post('/applogin','passport\PassportController@login');
////个人中心
//Route::get('/appuserInfo','passport\PassportController@userInfo');
//添加至购物车
Route::post('/appcart','passport\PassportController@cart');

/*
 *企业注册
 *  */
//注册
Route::get('/reg','test\ApitestController@register');
//执行
Route::post('/regdo','test\ApitestController@regdo');
//显示客户端ip
Route::get('/userip','test\ApitestController@userip')->Middleware('tokentest');
//显示客户端ui
Route::get('/userui','test\ApitestController@userui')->Middleware('tokentest');
//getToken
Route::get('/token','test\ApitestController@getToken')->Middleware('testapi');
//登陆
Route::get('/logo','sigin\SiginController@sigin');

//签到
Route::get('/sigindo','sigin\SiginController@sigindo');
//执行
Route::get('/sig','sigin\SiginController@sig');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
