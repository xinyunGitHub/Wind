<?php
namespace app\controller\api;

use app\BaseController;
use think\Facade\Db;
use think\facade\Cache;
use think\facade\Request;

use app\validate\Openid;
use think\exception\ValidateException;

class Actual extends BaseController
{
    public function index($method)
    {
        switch($method)
        {
            case 'info':
                return $this->info();
            break;
        }
    }

    /* 新增地址 */
    public function info()
    {
        $openid = Request::post('openid');
        try {
            validate(Openid::class)->check([
                'openid' => $openid
            ]);
            if (Db::table('user')->where('openid', $openid)->find()) {
                if (!(Db::table('order')->where('openid', $openid)->find())) {
                    $firstBuy = true;
                } else {
                    $firstBuy = false;
                }
                $params = array(
                    'status'  => true,
                    'data'    => array(
                        'firstBuy' => $firstBuy
                    )
                );
                return json($params);
            } else {
                $params = array(
                    'status' => false,
                    'code'   => 1000
                );
                return json($params); 
            }
        } catch(Exception $err) {
            $params = array(
                'status'  => false,
                'message' => $err
            );
            return json($params); 
        }
    }
}