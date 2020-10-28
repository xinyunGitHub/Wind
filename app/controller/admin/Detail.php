<?php
namespace app\controller\admin;

use app\BaseController;
use think\Facade\Db;
use think\facade\Request;

class Detail extends BaseController
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

    /* 新增商品详情 */
    public function add()
    {
        $unique = Request::post('unique');
        $title = Request::post('title');
        $price = Request::post('price');
        $figure = Request::post('figure');
        $describe = Request::post('describe');

        $detail = [
            'unique' => $unique,
            'title' => $title,
            'price' => $price,
        ];

        try {
            Db::name('detail')->insert($detail);
            foreach ($figure as $val) {
                $list = [
                    'unique' => $unique,
                    'figure' => $val,
                ];
                Db::name('figure')->insert($list);
            }
            foreach ($describe as $des) {
                $item = [
                    'unique' => $unique,
                    'describe' => $des,
                ];
                Db::name('describe')->insert($item);
            }
            $params = array(
                'status' => true,
                'message' => '商品详情新建成功～'
            );
            return json($params); 
        } catch(Exception $err) {
            $params = array(
                'status' => false,
                'message' => $err
            );
            return json($params); 
        }
    }
    /* 删除商品详情 */
    public function del()
    {
        $unique = Request::post('unique');
        try {
            Db::table('detail')->where('unique', $unique)->delete();
            Db::table('figure')->where('unique', $unique)->delete();
            Db::table('describe')->where('unique', $unique)->delete();
            $params = array(
                'status' => true,
                'message'   => '商品信息删除成功～'
            );
            return json($params);
        } catch(Exception $err) {
            $params = array(
                'status' => false,
                'message' => $err
            );
            return json($params); 
        }
    }
    /* 更新商品详情 */
    public function edit()
    {
        $unique = Request::post('unique');
        $title = Request::post('title');
        $price = Request::post('price');
        $figure = Request::post('figure');
        $describe = Request::post('describe');

        $detail = [
            'title' => $title,
            'price' => $price,
        ];

        try {
            Db::name('detail')->where('unique', $unique)->update($detail);
            if (Db::table('figure')->where('unique', $unique)->delete()) {
                foreach ($figure as $val) {
                    $list = [
                        'unique' => $unique,
                        'figure' => $val
                    ];
                    Db::name('figure')->insert($list);
                }
            }

            if (Db::table('describe')->where('unique', $unique)->delete()) {
                foreach ($describe as $des) {
                    $item = [
                        'unique' => $unique,
                        'describe' => $des
                    ];
                    Db::name('describe')->insert($item);
                }
            }

            $params = array(
                'status' => true,
                'message' => '商品详情编辑成功～'
            );
            return json($params); 
        } catch(Exception $err) {
            $params = array(
                'status' => false,
                'message' => $err
            );
            return json($params); 
        }
    }
    /* 查询商品详情列表 */
    public function query()
    {
        $query = Db::table('detail')->select();
        $data = array();
        foreach ($query as $key => $que) {
            $unique = $que['unique'];

            $figure = array();
            $list = Db::table('figure')->where('unique', $unique)->select();
            foreach ($list as $keyfig => $fig) {
                $figure[$keyfig] = $fig['figure'];
            }
            $que['figure'] = $figure;

            $describe = array();
            $item = Db::table('describe')->where('unique', $unique)->select();
            foreach ($item as $keydes => $des) {
                $describe[$keydes] = $des['describe'];
            }
            $que['describe'] = $describe;

            $data[$key] = $que;
        }
        $params = array(
            'status' => true,
            'data'   => $data
        );
        return json($params);
    }
}
