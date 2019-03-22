<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->get('/weixin/wxuser', 'WxUserController@index');
    //$router->get('/weixin/sendmsg', 'WeixinController@getWXAccessToken');
    $router->get('/weixin/userinfo', 'WeixinController@userinfo');
    //群发视图层
    $router->get('/weixin/sendmsg', 'WeixinController@msgview');
    //群发处理
    $router->post('/weixin/sendmsg', 'WeixinController@sendmsg');
    //用户标签
    $router->get('/weixin/usersign', 'WeixinController@signview');
    $router->post('/weixin/usersign', 'WeixinController@usersign');
    //已知标签列表
    $router->get('/weixin/signinfo', 'WeixinController@signinfo');
    //创建菜单
    $router->get('/weixin/menu', 'WeixinController@menu');
});
