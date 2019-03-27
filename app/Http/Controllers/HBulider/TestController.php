<?php
namespace App\Http\Controllers\HBulider;

use App\Http\Controllers\Controller;
use App\Model\HBModel;
use Encore\Admin\Grid\Model;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    //reg
    public function reg(){
        $name = $_POST['name'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $data = [
            'name'  =>  $name,
            'password'  =>  $password,
            'email' =>  $email
        ];
        $res = HBModel::insertGetId($data);
        if(!$res){
            $response = [
                'error' =>  777,
                'msg'   =>  'error'
            ];
        }else{
            $response = [
                'error' =>  0,
                'msg'   =>  'ok'
            ];
        }
        echo json_encode($response);
    }
    //login
    public function login(){
        $name = $_POST['name'];
        $password = $_POST['password'];

        $res = HBModel::where(['name'=>$name])->first();
        if($res){
            if($password==$res['password']){
                $token = substr(md5(time().mt_rand(1,99999)),10,10);

                $id = $res['id'];
                $redis_token_key = "str:hb_u_token".$id;
                Redis::set($redis_token_key,$token);
                Redis::expire($redis_token_key,3600);
                $response = [
                    'error' =>  0,
                    'msg'   => 'ok',
                    'id'    =>  $id,
                    'token' =>  $token
                ];
            }else{
                $response = [
                    'error' =>  500,
                    'msg'   =>  'please check out your pwd'
                ];
            }
        }else{
            $response = [
                'error' =>  500,
                'msg'   =>  'account not found'
            ];
        }
        return $response;
    }
}
