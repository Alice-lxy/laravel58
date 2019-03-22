<?php

namespace App\Http\Controllers\User;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    /** 注册*/
    public function reg(){
        return view('users.reg');
    }
    public function doReg(Request $request){
        /*echo __METHOD__;
        echo '<pre>';print_r($_POST);echo '</pre>';*/
        //exit;
        $name = $request->input('name');
        $new_name = UserModel::where(['name'=>$name])->first();
//		dump($new_name);exit;
        if($new_name){
            exit('此用户名已存在');
        }

        $pwd = $request->input('pwd');
        $pwd1 = $request->input('pwd1');
        if($pwd !== $pwd1 ){
            exit('确认密码与密码保持一致');
        }
        $pwd = password_hash($request->input('pwd'),PASSWORD_BCRYPT);
        /*echo $pwd;echo '<br/>';
        $res = password_verify($request->input('pwd'),'$2y$10$/8FuGHIhkIwi353vl0mBFOkn5AfrR03gzqwqwd8gnTcNsRcITU/QO');
        var_dump($res);exit;*/
        $data = [
            'name' => $request->input('name'),
            'pwd' => $pwd,
            'age' => $request->input('age'),
            'email' => $request->input('email')
        ];
        $id = UserModel::insertGetId($data);
        //var_dump($id);
        if($id){
            setcookie('id',$id,time()+86400,'/','larvel.com',false,true);//名，值，过期时间，路径，域名，secure，httponly(默认安全true)
            $token = substr(md5(time().mt_rand(1,99999)),10,10);
            $request->session()->put('u_token',$token);
            $request->session()->put('uid',$id);

            $request->session()->put('name',$id['name']);

            echo 'successly';
            header("refresh:1;'http://larvel.com/goods'");
        }else{
            echo 'fail';
        }
    }

    /** 登录*/
    public function login(){
        return view('users.login');
    }
    public function doLogin(Request $request){
        //echo '<pre>';print_r($_POST);echo '</pre>';die;
        //echo __METHOD__;
        $name = $request->input('name');
        $where = [
            'name' => $name,
        ];
        $res = UserModel::where($where)->first();

        if($res){
            if(password_verify($request->input('pwd'),$res['pwd'])){
                $token = substr(md5(time().mt_rand(1,99999)),10,10);
                setcookie('id',$res['id'],time()+86400,'/','lxy.qianqianya.xyz',false,true);
                setcookie('token',$token,time()+86400,'/','lxy.qianqianya.xyz',false,true);

                $redis_token_key = "str:u_token_key".$res['id'];
                Redis::set($redis_token_key,$token);
                Redis::expire($redis_token_key,3600);

                $data = [
                    'error' => 0,
                    'msg'   => 'ok',
                    'token' => $token
                ];
                header("refresh:1,url='https://lxy.qianqianya.xyz/goods'");
            }else{
                $data = [
                    'error' =>  5000,
                    'msg'   => 'password error'
                ];
            }
            echo json_encode($data);
        }else{
            $data = [
                'error' => 8888,
                'msg'   => 'account error'
            ];
            echo json_encode($data);
        }
    }

    public function center(Request $request){
        echo 'center';
    }
}
