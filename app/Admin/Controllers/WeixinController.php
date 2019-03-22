<?php

namespace App\Admin\Controllers;

use App\Model\WxUserModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp;

class WeixinController extends Controller
{
    /**
     * 获取用户信息
     */
    public function userinfo(Request $request)
    {
        $openid=$request->input('openid');
        $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".WxUserModel::getWXAccessToken()."&openid=".$openid."&lang=zh_CN";
        $data=json_decode(file_get_contents($url),true);
        print_r($data);
    }

    /**
     * @param Content $content 群发消息视图层
     * @return Content
     */
    public function msgview(Content $content){
        return $content
            ->header('Index')
            ->description('description')
            ->body(view('sendmsg.sendmsg'));
    }

    /**
     * @param Request $request 群发处理
     */
    public function sendmsg(Request $request)
    {
        $text=$request->input('text');
        $url="https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=".WxUserModel::getWXAccessToken();
        $client=new GuzzleHttp\Client(['base_url'=>$url]);
        $param = [
            "filter"=>[
                'is_to_all'=>true
            ],
            "text"=>[
                "content"=>$text
            ],
            "msgtype"=>"text",
        ];
        $r=$client->request('POST',$url,[
            'body' => json_encode($param, JSON_UNESCAPED_UNICODE)
        ]);
        $response_arr=json_decode($r->getBody(), true);
        if ($response_arr['errcode'] == 0) {
            echo "发送成功";
        } else {
            echo "发送失败";
            echo '</br>';
            echo $response_arr['errmsg'];
        }
    }

    /**
     * 创建用户标签视图
     */
    public function signview(Content $content){
        return $content
            ->header('Index')
            ->description('description')
            ->body(view('sign.sign'));
    }

    /**
     * 创建用户标签
     */
    public function usersign(Request $request)
    {
        $sign=$request->input('sign');
        $url="https://api.weixin.qq.com/cgi-bin/tags/create?access_token=".WxUserModel::getWXAccessToken();
        $client=new GuzzleHttp\Client(['base_url'=>$url]);
        $param = [
            "tag"=>[
                'name'=>$sign
            ]
        ];
        $r=$client->request('POST',$url,[
            'body' => json_encode($param, JSON_UNESCAPED_UNICODE)
        ]);
        $response_arr=json_decode($r->getBody(), true);
        //var_dump($response_arr);die;
        if (empty($response_arr['errcode'])) {
            echo "标签成功";
        } else {
            echo "标签失败";
            echo '</br>';
            echo $response_arr['errmsg'];
        }
    }

    /**
     * 已知标签
     */
    public function signinfo(Content $content)
    {
        $url="https://api.weixin.qq.com/cgi-bin/tags/get?access_token=".WxUserModel::getWXAccessToken();
        $data=json_decode(file_get_contents($url),true);
        //var_dump($data['tags']);
        return $content
            ->header('Index')
            ->description('description')
            ->body(view('sign.signinfo',['data'=>$data['tags']]));
    }

    /**
     * 创建菜单
     */
    public function menu()
    {
        $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".WxUserModel::getWXAccessToken();
        //echo $url;die;
        $client=new GuzzleHttp\Client(['base_url'=>$url]);
        $data=[
            "button"=>[
                [
                    "name"=>"❤小可爱❤",
                    "sub_button"=>[
                        [
                            "type"  => "view",      // view类型 跳转指定 URL
                            "name"  => "亲亲♡",
                            "url"   => "https://www.baidu.com"
                        ],
                        [
                            "type"  => "view",      // view类型 跳转指定 URL
                            "name"  => "抱抱❤",
                            "url"   => "https://www.baidu.com"
                        ],
                        [
                            "type"  => "view",      // view类型 跳转指定 URL
                            "name"  => "举高高☺",
                            "url"   => "https://www.baidu.com"
                        ]
                    ]
                ],
                [
                    "type"  => "view",      // view类型 跳转指定 URL
                    "name"  => "商城首页",
                    "url"   => "http://188.131.185.180/shop/public/index.php"
                ],
                [
                    "type"  => "click",      // view类型 跳转指定 URL随便买☺"url"   => "https://qzone.qq.com/"
                    "name"  => "联系客服",
                    "key"=>"kefu01"
                ]
            ]
        ];
        $r=$client->request('POST',$url,[
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ]);
        $response_arr=json_decode($r->getBody(), true);
        //var_dump($response_arr);
        if ($response_arr['errcode'] == 0) {
            echo "创建菜单成功";
        } else {
            echo "创建菜单失败";
            echo '</br>';
            echo $response_arr['errmsg'];
        }
    }
}
