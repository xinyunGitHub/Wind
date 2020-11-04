<?php
namespace app\controller\api;

use app\BaseController;
use think\Facade\Db;
use think\facade\Cache;
use think\facade\Request;

class Home extends BaseController
{
    public function index($method)
    {
        switch($method)
        {
            case 'swipe':
                return $this->swipe();
            break;
            case 'tabs':
                return $this->tabs();
            break;
        }
    }

    /* banner数据 */
    public function swipe()
    {
        $params = array(
            'status' => false,
            'data'   => $data
        );
        return json($params);
    }
    /* 首页列表数据 */
    public function tabs()
    {
        try {
            $query = Db::table('goods')->select();
            $params = array(
                'status' => true,
                'data'   => $query
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
