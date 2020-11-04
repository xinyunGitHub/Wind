<?php
namespace app\controller\api;

use app\BaseController;
use think\Facade\Db;
use think\facade\Cache;
use think\facade\Request;

class Order extends BaseController
{
    public function index($method)
    {
        switch($method)
        {
            case 'address':
                return $this->address();
            break;
        }
    }
    /* 查询默认收获地址 */
    public function address()
    {
        $openid = Request::post('openid');
        try {
            $query = Db::table('address')->where(['openid' => $openid, 'active' => 0])->find();
            $result = array(
                'id' => $query['id'],
                'address' => $query['province'].$query['city'].$query['county'].$query['road'],
                'contact' => $query['name'].' '.substr_replace($query['phone'], '*****', '3', '5')
            );
            $params = array(
                'status' => true,
                'data'   => $result
            );
            return json($params);
        } catch(Exception $err) {
            $params = array(
                'status'  => false,
                'message' => $err
            );
            return json($params); 
        }
    }
}