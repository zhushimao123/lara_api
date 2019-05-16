<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Redis;

class checktokenlogin
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
         echo "<pre>"; print_r($_COOKIE);echo "<pre>";
         if(empty($_COOKIE['uid']) || empty($_COOKIE['Cookietoken'])){
             header("refresh:3;url=http://www.1809x.com/login");
             die('请登录');
         }
        $key  = "token:user".$_COOKIE['uid'];
        $redis_key = Redis::get($key);
        if($redis_key != $_COOKIE['Cookietoken']){
            die('无效的token');
        }
        echo "欢迎登陆";
        return $next($request);
    }
}
