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
                $arr = Redis::set($redis_token_key,$token);
                print_r($arr);die;
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
    //quit
    public function quit(){
        $uid = $_POST['uid'];
        $redis_token_key = "str:hb_u_token".$uid;
        $quit = Redis::del($redis_token_key);
        if($quit){
            $response = [
                'error'	=>	0,
                'msg'	=>	'ok'
            ];
        }else{
            $response = [
                'error'	=>	404,
                'msg'	=>	'fail'
            ];
        }
        echo json_encode($response);
    }
    //修改密码
    public function updpwd(){
        $id = $_POST['id'];
        $oldpwd = md5($_POST['oldpwd']);
        $newpwd = md5($_POST['newpwd']);
        $res = HBModel::where(['id'=>$id])->first()->toArray();//查看用户
        //print_r($res);die;
        if($res){
            //修改密码
            if($oldpwd!=$res['password']){
                $response = [
                    'error' =>  405,
                    'msg'   =>  'oldpwd error',
                ];
            }else{
                $arr = HBModel::where(['id'=>$id])->update(['password'=>$newpwd]);
                if($arr){
                    $response = [
                        'error' =>  0,
                        'mag'   =>  'ok'
                    ];
                }else{
                    $response = [
                        'error' =>  507,
                        'mag'   =>  'upd error'
                    ];
                }
            }
        }else{
            $response = [
                'error' =>  506,
                'msg'   =>  'this user not found'
            ];
        }
        echo json_encode($response);

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
                'msg'   =>  'ok',
            ];
        }
        echo json_encode($response);
    }
    //添加好友
    public function addFriend(){
        //本人uid   好友account
        $uid = $_POST['uid'];
        $account = $_POST['account'];
        $res = HBModel::orwhere(['name'=>$account])->orwhere(['email'=>$account])->orwhere(['tel'=>$account])->first();

        $where = ['id' =>  $uid];
        $info = HBModel::where($where)->first();

        if($info['friend']){
            $friend = explode(',', $info['friend']);
            if (in_array($res['id'], $friend)) {
                $response = [
                    'error' => 408,
                    'msg' => 'exist'
                ];
                echo json_encode($response);
                die;
            }
        }
            $new_friend = trim($info['friend'] . ',' . $res['id'] . ',', ',');
            $data = HBModel::where($where)->update(['friend' => $new_friend]);
            if ($data) {
                $response = [
                    'error' => 0,
                    'msg' => 'ok'
                ];
            } else {
                $response = [
                    'error' => 407,
                    'msg' => 'add fail'
                ];
            }
            echo json_encode($response);

    }
}
