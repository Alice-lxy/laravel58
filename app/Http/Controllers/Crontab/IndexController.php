<?php

namespace App\Http\Controllers\Crontab;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //
    public function orderDel(){
        echo date('Y-m-d H:i:s')."此订单已删除";
    }
}