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
            case 'advert':
                return $this->advert();
            break;
            case 'tabs':
                return $this->tabs();
            break;
        }
    }

    /* banner数据 */
    public function advert()
    {
        $query = Db::table('advert')->order('sort', 'desc')->select();
        $params = array(
            'status' => true,
            'data'   => $query
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
