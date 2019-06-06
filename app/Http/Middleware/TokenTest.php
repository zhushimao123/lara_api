<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use http\Client\Request;
class TokenTest
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
        //参数需携带token
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
        if($_GET['uid'] != $_COOKIE['uid']){
            $respose = [
                'errno'=> 4110,
                'msg' => '非法操作'
            ];
            die(json_encode($respose,JSON_UNESCAPED_UNICODE));
        }
        if(empty($_GET['token'])){
            $respose = [
                'errno'=> 40001,
                'msg' => '请求失败'
            ];
            die(json_encode($respose,JSON_UNESCAPED_UNICODE));
        }else{
            $keys = 'access_token';
            $r_token = Redis::get($keys);
            if($r_token != $_GET['token'])
            {
                $respose = [
                    'errno'=> 40006,
                    'msg' => 'token 无效'
                ];
                die(json_encode($respose,JSON_UNESCAPED_UNICODE));
            }
            $k = 'saddkey';
            $fa = Redis::sIsMember($k,$r_token);
            if($fa ==false){
                $respose = [
                    'errno'=> 40007,
                    'msg' => 'token 无效'
                ];
                die(json_encode($respose,JSON_UNESCAPED_UNICODE));
            }
        }

        return $next($request);
    }
}
