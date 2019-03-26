<?php
namespace App\Http\Controllers\HBulider;

use App\Http\Controllers\Controller;
use App\Model\HBModel;
use Encore\Admin\Grid\Model;

class TestController extends Controller
{
    public function login(){
        $name = $_POST['name'];
        $password = $_POST['password'];

        $res = HBModel::where(['name'=>$name])->first();
        if($res){
            if($password==$res['password']){
                $token = substr(md5(time().mt_rand(1,99999)),10,10);
                $response = [
                    'error' =>  0,
                    'msg'   => 'ok',
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
