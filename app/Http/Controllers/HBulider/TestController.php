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
        $password = md5($_POST['password']);
        $email = $_POST['email'];
        $tel = $_POST['tel'];
        $data = [
            'name'  =>  $name,
            'email' =>  $email,
            'tel'   =>  $tel,
            'password'  =>  $password
        ];

        $res = HBModel::insertGetId($data);
        //print_r($res);die;
        if(!$res){
            $token = substr(md5(time().mt_rand(1,99999)),10,10);

            $id = $res['id'];
            $redis_token_key = "str:hb_u_token".$id;
            Redis::set($redis_token_key,$token);
            Redis::expire($redis_token_key,3600);
            $response = [
                'error' =>  0,
                'msg'   => 'ok',
                'uid'    =>  $id,
                'name'  =>  $name,
                'token' =>  $token
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
        $account = $_POST['account'];
        $password = md5($_POST['password']);

        $res = HBModel::orwhere(['name'=>$account])->orwhere(['email'=>$account])->orwhere(['tel'=>$account])->first();
        //print_r($res);die;
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
                    'uid'    =>  $id,
                    'name'  =>  $account,
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
    public function test(){
        echo md5(111);
    }
    //搜索好友
    public function searchFriend(){
        $account = $_POST['account'];
        $res = HBModel::orwhere(['name'=>$account])->orwhere(['email'=>$account])->orwhere(['tel'=>$account])->first();
        if(!$res){
            $response = [
                'error' =>  505,
                'msg'   =>  'this friend not found'
            ];
        }else{
            $response = [
                'error' =>  0,
                'msg'   =>  'ok'
            ];
        }
        echo json_encode($response);
    }
    //添加好友
   /* public function addFriend(){
        $uid = $_POST['uid'];
        $where = ['id' =>  $uid];
        $info = HBModel::where($where)->first();
        $friend = $info['friend'];
        print_r($friend);
    }*/
}
