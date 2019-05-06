<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class teststimes
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
        // echo 1111;
        $key = "nums";
        $num = Redis::get($key);
        var_dump($num);
        if($num>=10){
            die('请求超过次数限制');
        }
        Redis::incr($key);
        Redis::expire($key,20);
        return $next($request);
    }
}
