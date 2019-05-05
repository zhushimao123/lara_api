<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
class UserApiController extends Controller
{
    public function userapi(Request $Request)
    {
        // var_dump($uid);
        $res = DB::table('user')->where(['uid'=>26])->get()->toArray();
        if($res){
            $data = [];
            $data[] = [
                'error'=> 0,
                'msg' => 'ok',
                'data'=> $res
            ];
            $aa = json_encode($data,true);
            echo "<pre>";print_r($aa);echo "<pre>";
        }
    }
    public function test(Request $Request)
    {
        $url = 'http://1809a.apitest.com/users?uid=26';
        //初始化 创建新资源
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $rs = curl_exec($ch);
        var_dump($rs);
        var_dump(curl_error($ch));

        curl_close($ch);
    }
}
