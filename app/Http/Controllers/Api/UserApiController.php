<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\model\user;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
class UserApiController extends Controller
{
    public function userapi(Request $Request)
    {
        // var_dump($uid);
        $res = DB::table('user')->where(['uid' => 26])->get()->toArray();
        if ($res) {
            $data = [];
            $data[] = [
                'error' => 0,
                'msg' => 'ok',
                'data' => $res
            ];
            $aa = json_encode($data, true);
            echo "<pre>";
            print_r($aa);
            echo "<pre>";
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
            'name' => 'zhangsan',
            'email' => '1115177650@qq.com'
        ];
        //2、application/x-www-form-urlencoded
        $post_str = "name=zhangsan&email=1115177650@qq.com";
        //3、raw（字符串文本）
        $post_arr = [
            'name' => 'lisi',
            'email' => 'lisi@qq.com'
        ];
        $post_json = json_encode($post_arr);
        //初始化 创建新资源
        $ch = curl_init();
        // 设置 URL 和相应的选项
        curl_setopt($ch, CURLOPT_URL, $url);
        //发送post请求
        curl_setopt($ch, CURLOPT_POST, 1);
        //禁止浏览器输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //发送数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
        //字符串文本
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:text/plain']);
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

    //注册
    public function posts(Request $request)
    {
        $pass1 = $request->input('pass1');
        $pass2 = $request->input('pass2');
        $email = $request->input('email');
        //确认密码是否一致
        if ($pass1 != $pass2) {
            $response = [];
            $response[] = [
                'errno' => 50001,
                'msg' => '密码错误'
            ];
            die(json_encode($response, JSON_UNESCAPED_UNICODE));
        }
        $pass = password_hash($pass1, PASSWORD_DEFAULT);
        //验证邮箱唯一
        $em = user::where(['email' => $email])->first();
        if ($em) {
            $response = [];
            $response[] = [
                'errno' => 50002,
                'msg' => '邮箱已存在'
            ];
            die(json_encode($response, JSON_UNESCAPED_UNICODE));
        }
        //入库
        $data = [
            'name' => $request->input('name'),
            'email' => $email,
            'pass' => $pass,
            'create_time' => time()
        ];
        $res = user::insertGetId($data);
        if ($res) {
            $response = [
                'errno' => 0,
                'msg' => '注册成功'
            ];
            die(json_encode($response, JSON_UNESCAPED_UNICODE));
        } else {
            $response = [
                'errno' => 50003,
                'msg' => '注册失败'
            ];
            die(json_encode($response, JSON_UNESCAPED_UNICODE));
        }
    }

    //登陆
    public function logn(Request $request)
    {
        $email = $request->input('email');
        $pass = $request->input('pass');
        //查询
        $u = user::where(['email' => $email])->first();
        if (!$u) {
            $response = [
                'errno' => 50004,
                'msg' => '帐号不正确'
            ];
            die(json_encode($response, JSON_UNESCAPED_UNICODE));
        } else {
            //验证密码
            if (password_verify($pass, $u->pass)) {
                $response = [
                    'errno' => 50005,
                    'msg' => '密码不正确'
                ];
                die(json_encode($response, JSON_UNESCAPED_UNICODE));
            }

            //token 存入
            $token = $this->getToken($u->uid);
            $key = "token:user" . $u->uid;
            $redis_key = Redis::get($key);
            if ($redis_key) {
                return $redis_key;
            } else {
                Redis::set($key, $token);
                Redis::expire($key, 604800);
            }

        }
    }
    //个人中心
    public function myUser()
    {
        echo "我的名字路星河";
    }
    //对称加密
    public function encode()
    {
        $data = 'zhushimao';
        $method = "AES-256-CBC";
        $key = 'encode';
        $options = OPENSSL_RAW_DATA ;
        $iv = 'asdasdasdasdasda';
        $enc = openssl_encrypt($data,$method,$key,$options,$iv);
        echo base64_encode($enc);
    }
    public function dncrypt()
    {
        $method = "AES-256-CBC";
        $key = 'encode';
        $options = OPENSSL_RAW_DATA ;
        $iv = 'asdasdasdasdasda';
       $str =  $_GET['str'];
       $str2 = base64_decode($str);
       $u = openssl_decrypt($str2,$method,$key,$options,$iv);
       echo $u;
    }
    //凯撒加密
    public function kaisa()
    {
        $str = 'abc';
        $lenght = strlen($str);
        $str2 = "";
        for ($i=0;$i<$lenght;$i++){
          $str1 = ord($str[$i])+1;
          $str2 .= chr($str1);
//
          $str3 =  ord($str2[$i])-1;
          $str4 = chr($str3);
          echo $str4;
        }
    }
    //对称加密传送数据
    public function encrypt()
    {
        $data = [
            'name'=> 'laowang',
            'email' => '33322@163.com',
            'tel' => '15235942622'
        ];
        $method = 'AES-256-CBC';
        $key = 'zsm123';
        $options = OPENSSL_RAW_DATA;
        $iv = 'aaaaaaaaaaaaaaaa';
        $info = json_encode($data,true);
        $open_json = openssl_encrypt($info,$method,$key,$options,$iv);
        $ba64 = base64_encode($open_json);
      //curl 发送请求
        $url = 'http://client.1809a.com/dncrypt';
        //初始化 创建新资源
        $ch = curl_init();
        // 设置 URL 和相应的选项
        curl_setopt($ch, CURLOPT_URL, $url);
        //发送post请求
        curl_setopt($ch, CURLOPT_POST, 1);
//        //禁止浏览器输出
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //发送数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $ba64);
        //字符串文本
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:text/plain']);
        //抓取 URL 并把它传递给浏览器
        $rs = curl_exec($ch);  //data 数据
//        var_dump($rs);
        //错误码
//        var_dump(curl_error($ch));
        // 关闭 cURL 资源，并且释放系统资源
        curl_close($ch);


    }
    //非对称加密
    public function rsa()
    {
        $info = [
            'name'=> 'laowang',
            'email' => '33322@163.com',
            'tel' => '15235942622'
        ];
        $json_str = json_encode($info);
        $key = openssl_get_privatekey('file://'.storage_path('app/keys/private.pem'));
//         $a = openssl_error_string();
//         echo $a;die;
        openssl_private_encrypt($json_str,$crypted,$key,OPENSSL_PKCS1_PADDING);
        $b64 = base64_encode($crypted);

        $url = 'http://client.1809a.com/rsa';
        //初始化 创建新资源
        $ch = curl_init();
        // 设置 URL 和相应的选项
        curl_setopt($ch, CURLOPT_URL, $url);
        //发送post请求
        curl_setopt($ch, CURLOPT_POST, 1);
//        //禁止浏览器输出
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //发送数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $b64);
        //字符串文本
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:text/plain']);
        //抓取 URL 并把它传递给浏览器
        $rs = curl_exec($ch);  //data 数据
//        var_dump($rs);
        //错误码
//        var_dump(curl_error($ch));
        // 关闭 cURL 资源，并且释放系统资源
        curl_close($ch);

    }
    //验证签名
    public  function autograph()
    {
        $info = [
            'name'=> 'laowang',
            'email' => '33322@163.com',
            'tel' => '15235942622'
        ];
        $json_str = json_encode($info);
        $key = openssl_get_privatekey('file://'.storage_path('app/keys/private.pem'));
        //发送端使用自己的私钥签名
        openssl_sign($json_str,$signature,$key);
        $b64 = base64_encode($signature);
//        echo $b64;
        //发送请求
        $url = "http://client.1809a.com/qianming?sin=".urlencode($b64); //解决签名 + 问题
        //初始化 创建新资源
        $ch = curl_init();
        //设置url选型
        curl_setopt($ch, CURLOPT_URL, $url);
        //发送post请求
        curl_setopt($ch, CURLOPT_POST, 1);
        //发送数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_str);
        //字符串文本
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:text/plain']);
        //抓取 URL 并把它传递给浏览器
        $rs = curl_exec($ch);  //data 数据
//        var_dump($rs);
        //错误码
//        var_dump(curl_error($ch));
        // 关闭 cURL 资源，并且释放系统资源
        curl_close($ch);
    }
    //周考
    public function  user()
    {
        return view('user');
    }
    //注册
    public  function userinfo(Request $request)
    {
        $name = $request->input('name');
        $pass = $request->input('pass');
        $email = $request->input('email');
        $res = DB::table('t_user')->where(['email'=> $email])->first();
//        var_dump($res);die;

        if($res){
                $response = [
                    'errno'=> '8000',
                    'msg'=> '邮箱已存在'
                ];
                die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        //发送至api服务器
        $info = [
            'email'=> $email,
            'pass'=> $pass,
            'name'=> $name
        ];
        $json_str = json_encode($info);

        $key = openssl_get_privatekey('file://'.storage_path('app/keys/private.pem'));
//         $a = openssl_error_string();
//         echo $a;die;
        openssl_private_encrypt($json_str,$crypted,$key,OPENSSL_PKCS1_PADDING);
        $b64 = base64_encode($crypted);

        $url = 'http://client.1809a.com/userinfo';
        //初始化 创建新资源
        $ch = curl_init();
        // 设置 URL 和相应的选项
        curl_setopt($ch, CURLOPT_URL, $url);
        //发送post请求
        curl_setopt($ch, CURLOPT_POST, 1);
        //发送数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $b64);
        //字符串文本
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:text/plain']);
        //抓取 URL 并把它传递给浏览器
        $rs = curl_exec($ch);  //data 数据
        // 关闭 cURL 资源，并且释放系统资源
        curl_close($ch);
    }
}
