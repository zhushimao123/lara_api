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
                    'token' => $token,
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
    //token信息  123
    protected function getToken($uid)
    {
        $str = substr(sha1(time() . Str::random(10) . $uid.$_SERVER['DB_HOST']), 5, 15);
        return $str;
    }
    //添加至购物车
    public function  cart()
    {
        $data = file_get_contents("php://input");
        $json_arr = json_decode($data,true);
        //购物车表有商品的id 修改要购买的数量
        $Cart = DB::table('shop_cart')->where(['goods_id'=> $json_arr['goods_id'],'user_id'=> $json_arr['user_id']])->first();
        if(!$Cart) {
            // 没有商品数据 做添加
            $cartinfo = [
                'user_id'=> $json_arr['user_id'],
                'goods_id'=> $json_arr['goods_id'],
                'buy_number'=> $json_arr['buy_number'],
                'create_time'=> time()
            ];

            $res = DB::table('shop_cart')->insert($cartinfo);
            if($res){
                $response = [
                    'msg'=> '加入购物车成功',
                    'erron' => 'ok'
                ];
                echo  json_encode($response,JSON_UNESCAPED_UNICODE);die;
            }else{
                $response = [
                    'msg'=> '加入购物车失败',
                    'erron' => 'no'
                ];
                echo  json_encode($response,JSON_UNESCAPED_UNICODE);die;
            }
        }else{
            //做修改
            //根据商品id 用户id
            $info = DB::table('shop_cart')->where(['goods_id'=> $json_arr['goods_id'],'user_id'=> $json_arr['user_id']])->first();
//            var_dump($info);die;
            $buy_number = $info->buy_number;
//            var_dump($buy_number);die;
            $res = DB::table('shop_cart')->where(['goods_id'=> $json_arr['goods_id'],'user_id'=> $json_arr['user_id']])->update(['buy_number'=>$buy_number + $json_arr['buy_number']]);
            if($res){
                $response = [
                    'msg'=> '加入购物车成功',
                    'erron' => 'ok'
                ];
                echo  json_encode($response,JSON_UNESCAPED_UNICODE);die;
            }else{
                $response = [
                    'msg'=> '加入购物车失败',
                    'erron' => 'no'
                ];
                echo  json_encode($response,JSON_UNESCAPED_UNICODE);die;
            }
        }
    }
}
