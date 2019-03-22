<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class WxUserModel extends Model
{
    //
    public $table = 'wx_user';
    public $timestamps =false;

    protected static $redis_weixin_access_token='str:weixin_access_token';

    public static function getWXAccessToken()
    {
        $access_token=Redis::get(self::$redis_weixin_access_token);
        if(!$access_token)
        {
            $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WEIXIN_APPID')."&secret=".env('WEIXIN_APPSECRET');
            $data=json_decode(file_get_contents($url),true);
            $access_token=$data['access_token'];
            Redis::set(self::$redis_weixin_access_token,$access_token);
            Redis::setTimeout(self::$redis_weixin_access_token,3600);
        }
        return $access_token;
    }
}
