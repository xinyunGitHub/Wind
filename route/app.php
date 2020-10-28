<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::rule('apg/manage/:method', '\app\controller\admin\Manage@index', 'POST')
    ->allowCrossDomain([
        'Access-Control-Allow-Origin' => '*'
    ]);

Route::rule('apg/user/:method', '\app\controller\admin\User@index', 'POST')
    ->allowCrossDomain([
        'Access-Control-Allow-Origin' => '*'
    ]);

Route::rule('apg/goods/:method', '\app\controller\admin\Goods@index', 'POST')
    ->allowCrossDomain([
        'Access-Control-Allow-Origin' => '*'
    ]);

Route::rule('apg/detail/:method', '\app\controller\admin\Detail@index', 'POST')
    ->allowCrossDomain([
        'Access-Control-Allow-Origin' => '*'
    ]);

Route::rule('apg/inventory/:method', '\app\controller\admin\Inventory@index', 'POST')
    ->allowCrossDomain([
        'Access-Control-Allow-Origin' => '*'
    ]);

Route::rule('apg/address/:method', '\app\controller\admin\Address@index', 'POST')
    ->allowCrossDomain([
        'Access-Control-Allow-Origin' => '*'
    ]);

Route::rule('apg/order/:method', '\app\controller\admin\Order@index', 'POST')
    ->allowCrossDomain([
        'Access-Control-Allow-Origin' => '*'
    ]);