<?php
namespace app\controller\admin;

use app\BaseController;
use think\Facade\Db;
use think\facade\Request;

class Order extends BaseController
{
    public function index($method)
    {
        switch($method)
        {
            case 'value':
                return $this->value();
            break;
            case 'query':
                return $this->query();
            break;
        }
    }

    /* 订单查询 */
    public function value()
    {
        $value = Request::post('value');
        $query = Db::table('order')->where('phone|water','like', '%'.$value.'%')->select();
        $params = array(
            'status' => true,
            'data'   => $query
        );
        return json($params);
    }

    /* 订单列表 */
    public function query()
    {
        $query  = Db::table('order')->select();
        $params = array(
            'status' => true,
            'data'   => $query
        );
        return json($params);
    }
}
