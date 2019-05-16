<?php

namespace App\Http\Controllers\passport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class PassportController extends Controller
{
    public function user()
    {
        $data = file_get_contents("php://input");
        $json_arr = json_decode($data,true);
        echo $json_arr;die;
        $res = DB::table('t_user')->where(['email'=> $json_arr['email']])->first();
        if($res){
            $response = [
                'msg'=> '邮箱已存在',
                'erron' => 'no'
            ];
            echo  json_encode($response,JSON_UNESCAPED_UNICODE);die;
        }
        //入库
        $info = [
            'email'=> $json_arr['email'],
            'pass'=> $json_arr['pass']
        ];
        //存表
        $result = DB::table('t_user')->insert($info);
        if(!$result){
            $response =  '注册失败';
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }else{
            $response = [
                'msg'=> '注册成功',
                'erron' => 'ok'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }

    }
    //登陆
    public function login()
    {
        $data = file_get_contents("php://input");
        $json_arr = json_decode($data,true);
        $res = DB::table('t_user')->where(['email'=> $json_arr['email']])->first();
        if($res){
            if($res->pass != $json_arr['pass']){
                $response = [
                    'msg'=> '账号或密码错误',
                    'erron' => 'no'
                ];
                echo  json_encode($response,JSON_UNESCAPED_UNICODE);die;
            }else{
                //   成功 存入token
                $token = $this -> getToken($res-> uid);
                $key = 'usre:'.$res-> uid;
                Redis::set($key,$token);
                Redis::expire($key,3600);
//                echo $redis_key;die;
                $response = [
                    'msg'=> '登陆成功',
                    'erron' => 'ok',
                    'token' => Redis::get($key),
                    'uid'=> $res-> uid
                ];
                echo  json_encode($response,JSON_UNESCAPED_UNICODE);die;
            }
        }else{
            $response = [
                'msg'=> '账号或密码错误',
                'erron' => 'no'
            ];
            echo  json_encode($response,JSON_UNESCAPED_UNICODE);die;
        }
    }
    //token信息
    protected function getToken($uid)
    {
        $str = substr(sha1(time() . Str::random(10) . $uid.$_SERVER['DB_HOST']), 5, 15);
        return $str;
    }
}
