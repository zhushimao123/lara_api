<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use http\Client\Request;
class Testapi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //参数需携带key openid 生成token
//        echo "<pre>";print_r($_SERVER);echo "<pre>";die;
        $keys = 'num'.$_SERVER['REQUEST_URI'];
        $num = Redis::incr($keys);
        Redis::expire($keys,3600);
        if($num>20){
            $respose = [
                'errno'=> 40005,
                'msg' => '请求频繁'
            ];
            die(json_encode($respose,JSON_UNESCAPED_UNICODE));
        }
//        var_dump($_COOKIE['uid']);die;
        if(empty($_GET['appid']) || empty($_GET['key']) || empty($_GET['uid'] )){
            $respose = [
                'errno'=> 40001,
                'msg' => '请求失败'
            ];
            die(json_encode($respose,JSON_UNESCAPED_UNICODE));
        }else{
            $res = DB::table('t_open')->where(['uid'=>$_GET['uid']])->first();
            if(!$res){
                $respose = [
                    'errno'=> 40002,
                    'msg' => '请求参数无效'
                ];
                die(json_encode($respose,JSON_UNESCAPED_UNICODE));
            }else{
                if($res->appid != $_GET['appid'] || $res->key != $_GET['key'])
                {
                    $respose = [
                        'errno'=> 40003,
                        'msg' => '请求参数无效'
                    ];
                    die(json_encode($respose,JSON_UNESCAPED_UNICODE));
                }
            }
        }
        if($_GET['uid'] != $_COOKIE['uid']){
            $respose = [
                'errno'=> 4110,
                'msg' => '非法操作'
            ];
            die(json_encode($respose,JSON_UNESCAPED_UNICODE));
        }

        return $next($request);
    }
}
