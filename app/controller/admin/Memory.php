<?php
namespace app\controller\admin;

use app\BaseController;
use think\Facade\Db;
use think\facade\Request;

class Memory extends BaseController
{
    public function index($method)
    {
        switch($method)
        {
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
    /* 删除商品Sku */
    public function del()
    {
        $unique = Request::post('unique');
        try {
            $data = Db::name('skukey')->where('unique', $unique)->select();
            foreach ($data as $list) {
                Db::name('skuvalue')->where('sku', $list['id'])->delete();
            }
            Db::name('skukey')->where('unique', $unique)->delete();
            $params = array(
                'status' => true,
                'message' => 'Sku数据配置删除成功～'
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
    /* 编辑商品Sku */
    public function edit()
    {
        $unique = Request::post('unique');
        $sku = Request::post('sku');

        try {
            // 清除之前的数据
            $delete = Db::name('skukey')->where('unique', $unique)->select();
            foreach ($delete as $d) {
                Db::name('skuvalue')->where('sku', $d['id'])->delete();
            }
            Db::name('skukey')->where('unique', $unique)->delete();

            foreach ($sku as $key => $val) {
                // 重新写入的数据
                $skukey = [
                    'sort'   => $key,
                    'unique' => $unique,
                    'type'   => $val['type']
                ];
                Db::name('skukey')->insert($skukey);
                $query = Db::name('skukey')->where(['unique' => $unique, 'sort' => $key])->field('id')->find();

                foreach ($val['value'] as $list => $item) {
                    $skuvalue = [
                        'sku'    => $query['id'],
                        'sort'   => $list,
                        'value'  => $item
                    ];
                    Db::name('skuvalue')->insert($skuvalue);
                }
            }
            $params = array(
                'status' => true,
                'message'=> 'Sku编辑成功～'
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
    /* 查询商品Sku */
    public function query()
    {
        try {
            $detail = Db::name('detail')->select();
            $skukey = Db::name('skukey')->select();
            $skuvalue = Db::name('skuvalue')->select();
            $data = array(
                'detail' => $detail,
                'skukey' => $skukey,
                'skuvalue' => $skuvalue
            );
            $params = array(
                'status' => true,
                'data' => $data
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
}
