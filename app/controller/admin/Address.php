<?php
namespace app\controller\admin;

use app\BaseController;
use think\Facade\Db;
use think\facade\Request;

class Address extends BaseController
{
    public function index($method)
    {
        switch($method)
        {
            case 'phone':
                return $this->phone();
            break;
            case 'query':
                return $this->query();
            break;
        }
    }

    /* 根据手机号查询收货地址 */
    public function phone()
    {
        $phone  = Request::post('phone');
        $query  = Db::table('address')->where('phone', 'like', '%'.$phone.'%')->select();
        $params = array(
            'status' => true,
            'data'   => $query
        );
        return json($params);
    }

    /* 查询收货地址列表 */
    public function query()
    {
        $query  = Db::table('address')->select();
        $params = array(
            'status' => true,
            'data'   => $query
        );
        return json($params);
    }
}
