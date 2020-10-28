<?php
namespace app\controller\admin;

use app\BaseController;
use think\Facade\Db;
use think\facade\Request;

class Inventory extends BaseController
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
    /* 新增商品库存 */
    public function add()
    {
        $unique = Request::post('unique');
        $sku = Request::post('sku');

        try {
            foreach ($sku as $key => $val) {
                $skuKey = [
                    'unique' => $unique,
                    'sort' => $key,
                    'value' => $val['type']
                ];
    
                Db::name('skukey')->insert($skuKey);
                $query = Db::name('skukey')->where(['unique' => $unique, 'sort' => $key])->field('id')->select();
    
                foreach ($val['value'] as $item) {
                    $skuValue = [
                        'sku' => $query[0]['id'],
                        'attribute' => $item['name']
                    ];
                    Db::name('skuvalue')->insert($skuValue);
                }
            }
            $params = array(
                'status' => true,
                'message'   => 'sku更新成功～'
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
    /* 删除商品库存 */
    public function del()
    {
        $unique = Request::post('unique');
        $data = Db::name('skukey')->where('unique', $unique)->select();
        foreach ($data as $list) {
            Db::name('skuvalue')->where('sku', $list['id'])->delete();
        }
        Db::name('skukey')->where('unique', $unique)->delete();
    }
    /* 编辑商品库存 */
    public function edit()
    {
        $unique = Request::post('unique');
        $sku = Request::post('sku');
        try {
            foreach ($sku as $key => $val) {
                Db::name('skukey')->where('id', $val['id'])->update(['sort' => $key, 'value' => $val['type']]);
                foreach ($val['value'] as $item) {
                    Db::name('skuvalue')->where('id', $item['id'])->update(['attribute' => $item['name']]);
                }
            }
        } catch(Exception $err) {
            $params = array(
                'status' => false,
                'message' => $err
            );
            return json($params); 
        }
    }
    /* 查询商品库存 */
    public function query()
    {
        $unique = Request::post('unique');
        $query = Db::name('skukey')->where('unique', $unique)->select();
        $data = array();
        foreach ($query as $key => $item) {
            $value = Db::name('skuvalue')->where('sku', $item['id'])->select();
            $item['vaule'] = $value;
            $data[$key] = $item;
        }
        $params = array(
            'status' => false,
            'data' => $data
        );
        return json($params);
    }
}
