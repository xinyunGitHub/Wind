<?php
namespace app\controller\admin;

use app\BaseController;
use think\Facade\Db;
use think\facade\Request;

class Goods extends BaseController
{
    public function index($method)
    {
        switch($method)
        {
            case 'add':
                return $this->add();
            break;
            case 'del':
                return $this->del();
            break;
            case 'edit':
                return $this->edit();
            break;
            case 'query':
                return $this->query();
            break;
        }
    }
    /* 新增商品信息 */
    public function add()
    {
        $biggest = Db::table('list')->field('max(id)')->select();
        $count = (string)(100000000 - $biggest[0]['max(id)']);
        $unique = sha1(md5(hash('ripemd160', $count)));
        $thumb = Request::post('thumb');
        $title = Request::post('title');
        $price = Request::post('price');
        $tally = Request::post('tally');
        $data = [
            'unique' => $unique,
            'thumb' => $thumb,
            'title' => $title,
            'price' => $price,
            'tally' => $tally,
        ];

        if (Db::table('list')->where('unique', $unique)->find()) {
            $params = array(
                'status' => true,
                'message'   => '商品新建失败～'
            );
            return json($params);
        } else {
            if (Db::name('list')->insert($data)) {
                $list = Db::table('list')->where('unique', $unique)->find();
                $params = array(
                    'status' => true,
                    'data'   => $list
                );
                return json($params);
            } else {
                $params = array(
                    'status' => false,
                    'message' => '未知错误～'
                );
                return json($params);
            }
        }
    }
    /* 删除商品信息 */
    public function del()
    {
        $unique = Request::post('unique');
        if (Db::table('list')->where('unique', $unique)->delete()) {
            $params = array(
                'status' => true,
                'message'   => '商品信息删除成功～'
            );
            return json($params);
        } else {
            $params = array(
                'status' => false,
                'message'   => '商品信息删除失败～'
            );
            return json($params);
        }
    }
    /* 更新商品信息 */
    public function edit()
    {
        $unique = Request::post('unique');
        $thumb = Request::post('thumb');
        $title = Request::post('title');
        $price = Request::post('price');
        $tally = Request::post('tally');
        $data = [
            'thumb' => $thumb,
            'title' => $title,
            'price' => $price,
            'tally' => $tally,
        ];

        if (Db::table('list')->where('unique', $unique)->update($data)) {
            $params = array(
                'status' => true,
                'message'   => '商品信息修改成功～'
            );
            return json($params);
        } else {
            $params = array(
                'status' => false,
                'message'   => '商品信息修改失败～'
            );
            return json($params);
        }
    }
    /* 查询商品信息列表 */
    public function query()
    {
        $query = Db::table('list')->select();
        $params = array(
            'status' => true,
            'data'   => $query
        );
        return json($params);
    }
}
