<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
use http\Client\Request;
class checklogin
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
        $token = $request-> input('token');
        $uid = $request-> input('uid');
        if(empty($uid) || empty($uid)){
            $response = [
                'errno' => 40001,
                'msg' => '请登录'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        $key  = "token:user".$uid;
        $redis_key = Redis::get($key);
        if($redis_key){
            //有token信息
            if($redis_key != $token){
                $response = [
                    'errno' => 40002,
                    'msg' => '无效得token'
                ];
                die(json_encode($response,JSON_UNESCAPED_UNICODE));
            }
        }else{
            //没有
            $response = [
                'errno' => 40003,
                'msg' => '非法token'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        return $next($request);
    }
}
