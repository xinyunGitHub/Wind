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

// 后台接口
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

Route::rule('apg/memory/:method', '\app\controller\admin\Memory@index', 'POST')
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


// 前端接口
Route::rule('api/token/:method', '\app\controller\api\Token@index', 'POST')
    ->allowCrossDomain([
        'Access-Control-Allow-Origin' => '*'
    ]);

Route::rule('api/home/:method', '\app\controller\api\Home@index', 'POST')
    ->allowCrossDomain([
        'Access-Control-Allow-Origin' => '*'
    ]);

Route::rule('api/detail/:method', '\app\controller\api\Detail@index', 'POST')
    ->allowCrossDomain([
        'Access-Control-Allow-Origin' => '*'
    ]);

Route::rule('api/address/:method', '\app\controller\api\Address@index', 'POST')
    ->allowCrossDomain([
        'Access-Control-Allow-Origin' => '*'
    ]);

Route::rule('api/order/:method', '\app\controller\api\Order@index', 'POST')
    ->allowCrossDomain([
        'Access-Control-Allow-Origin' => '*'
    ]);