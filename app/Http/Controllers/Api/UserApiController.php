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

    //作业
    public function tests()
    {
        $url = 'http://1809a.apitest.com/ceshi';
        //1、form-data
        $data = [
            'name'=> 'zhangsan',
            'email'=> '1115177650@qq.com'
        ];
        //2、application/x-www-form-urlencoded
        $post_str = "name=zhangsan&email=1115177650@qq.com";
        //3、raw（字符串文本）
        $post_arr  = [
            'name'=> 'lisi',
            'email'=> 'lisi@qq.com'
        ];
        $post_json = json_encode($post_arr);
        //初始化 创建新资源
        $ch = curl_init();
        // 设置 URL 和相应的选项
        curl_setopt($ch, CURLOPT_URL,$url);
        //发送post请求
        curl_setopt($ch, CURLOPT_POST, 1);
        //禁止浏览器输出
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //发送数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
        //字符串文本
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-Type:text/plain']);
        //抓取 URL 并把它传递给浏览器
        $rs = curl_exec($ch);  //data 数据
        var_dump($rs);
        //错误码
        var_dump(curl_error($ch));
        // 关闭 cURL 资源，并且释放系统资源
        curl_close($ch);
       
    }
    //中间件使用
    //创建中间件  注册中间件 使用
    public function times()
    {
        echo 222222;
    }
}
