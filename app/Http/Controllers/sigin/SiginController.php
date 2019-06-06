<?php

namespace App\Http\Controllers\sigin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class SiginController extends Controller
{
    public function sigin()
    {
        return view('sigin');
    }
    //签到
    public function sigindo(Request $request)
    {
        $email = $request-> email;
        $pass = $request-> pwd;
//        var_dump($pass);die;
        $info = [
            'email'=> $email,
            'pass' => $pass
        ];
        $res = DB::table('t_sigin')->insert($info);
        $uid = DB::getPdo()->lastInsertId($res);
//        var_dump($uid);die;
        return view('login',['uid'=>$uid]);
    }
    //执行
    public  function  sig()
    {
        //用户id
        $uid = $_GET['uid'];
        $todayDate = date('Y-m-d');
        $cacheKey = 'sigin_'. $todayDate;
        Redis::setBit($cacheKey, $uid, 1);
        $num = Redis::bitCount($cacheKey);
        $response = [
            'num'=> $num
        ];
       echo  json_encode($response);die;
    }
}
