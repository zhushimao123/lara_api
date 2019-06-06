<?php

namespace App\Http\Controllers\test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class ApitestController extends Controller
{
    public function  register()
    {
        return view('reg.register ');
    }
    //注册执行
    public function regdo(Request $request)
    {
        $name = $request->name;
        $file = $request->file('myfile');
        $pass = $request->pwd;
        $tel = $request->tel;
        $regs = $request->regs;
        //上传文件
        $ext = $file->getClientOriginalExtension(); //获取原始文件的扩展名
//        $file_name = $file->getClientOriginalName(); //原始文件的名字
//        $size = $file->getSize();//原声文件的大小
        $path = 'upload';
        //生成新的文件名字
        $new_file_name = date('ymd') . '_' . Str::random(10) . '.' . $ext;
        //保存文件
        $rs = $file->storeAs($path, $new_file_name);
        $result = DB::table('t_test')->where(['pass'=>$pass])->first(); //身份证 //唯一
        if($result){
            $uid = $result-> uid;
            if($result-> name != $name   || $tel != $result->tel || $regs != $result-> regs)
            {
                DB::table('t_test')->where(['uid'=>$uid])->delete();
                $response = [
                    'errno' => '400743',
                    'msg' => '注册信息与上次注册信息不一致，您可重新注册，'
                ];
                die(json_encode($response, JSON_UNESCAPED_UNICODE));
            }
             if( $result->is_shen ==1){
                     setcookie('uid',$uid,time()+3600);

                    $res2 = DB::table('t_open')->where(['uid'=>$uid])->first();
                    if(!$res2){
                        $appid = date('ymd') . Str::random(10) . $uid;
                        $key = 'user:' . $uid;
                        $uinfo = [
                            'appid' => $appid,
                            'key' => $key,
                            'uid' => $uid
                        ];
                        $res = DB::table('t_open')->insert($uinfo);
                        if (!$res) {
                            $response = [
                                'errno' => '40001',
                                'msg' => 'openid and key 生成失败'
                            ];
                            die(json_encode($response, JSON_UNESCAPED_UNICODE));
                        }
                    }

                   return view('test.test',['result'=>$result]);
           }else{
               $response = [
                   'errno' => '40009',
                   'msg' => '未通过审批'
               ];
               die(json_encode($response, JSON_UNESCAPED_UNICODE));
           }
        }else {
            //入库
            $info = [
                'name' => $name,
                'file' => '/' . $path . '/' . $new_file_name,
                'pass' => $pass,
                'tel' => $tel,
                'regs' => $regs
            ];
            $res = DB::table('t_test')->insert($info); //添加
            if (!$res) {
                $response = [
                    'errno' => '40001',
                    'msg' => '企业注册失败'
                ];
                die(json_encode($response, JSON_UNESCAPED_UNICODE));
            } else {
                //生成appid 和key
                //刷新 重新提交表单       添加  /后台 通过  改 1   注册
                $uid = DB::getPdo()->lastInsertId($res);
                $is_show = DB::table('t_test')->where(['uid' => $uid])->first();
                if ($is_show->is_shen !== 1) {
                    $response = [
                        'errno' => '40009',
                        'msg' => '未通过审批'
                    ];
                    die(json_encode($response, JSON_UNESCAPED_UNICODE));
                }
            }
        }
    }
    //token信息
    public function getToken()
    {
        $keys = 'access_token';
        $val = date('ymd') . Str::random(10) .'_';
        $redis_key = Redis::get($keys);
        if($redis_key){
                echo $redis_key;
        }else{
            Redis::set($keys,$val);
            Redis::expire($keys,3600);
        }
        echo 111;
        $k = 'saddkey';
        Redis::Sadd($k,$redis_key);
    }
    //显示客户端ip请求
    public function userip()
    {
//        echo "<pre>";print_r($_SERVER);echo "<pre>";
        $user_ip = $_SERVER['SERVER_ADDR'];
        $response = [
            'errno'=> 'ok',
            'msg'=> $user_ip
        ];
        die(json_encode($response));
    }
    //显示客户端ui
    public  function   userui()
    {
        $user_ui = $_SERVER['HTTP_USER_AGENT'];
        $response = [
            'errno'=> 'ok',
            'msg' => $user_ui
        ];
        die(json_encode($response));
    }
}
