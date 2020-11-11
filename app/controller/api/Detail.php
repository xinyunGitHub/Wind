<?php
namespace app\controller\api;

use app\BaseController;
use think\Facade\Db;
use think\facade\Cache;
use think\facade\Request;

use app\validate\Unique;
use think\exception\ValidateException;

class Detail extends BaseController
{
    public function index($method)
    {
        switch($method)
        {
            case 'inform':
                return $this->inform();
            break;
            case 'sku':
                return $this->sku();
            break;
        }
    }

    /* 商品详情 */
    public function inform()
    {
        $unique = Request::post('unique');
        try {
            validate(Unique::class)->check([
                'unique' => $unique
            ]);
            $query = Db::table('detail')->where('unique', $unique)->find();
            $figure = Db::table('figure')->where('unique', $unique)->select();
            $thumb = Db::table('thumb')->where('unique', $unique)->select();
            $result = array(
                'inform' => $query,
                'figure' => $figure,
                'thumb'  => $thumb
            );
            $params = array(
                'status' => true,
                'data'   => $result,
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
    /* 商品sku */
    public function sku()
    {
        $unique = Request::post('unique');
        try {
            validate(Unique::class)->check([
                'unique' => $unique
            ]);
            $result = array();
            $skukey = Db::table('skukey')->where('unique', $unique)->select();
            foreach ($skukey as $key => $val) {
                $skuvalue = Db::table('skuvalue')->where('sku', $val['id'])->select();
                $skulist = array();
                foreach ($skuvalue as $list) {
                    $treevalue = array(
                        'id'   => $list['id'],
                        'name' => $list['value'],
                    );
                    array_push($skulist, $treevalue);
                }
                $treekey = array(
                    'k'   => $val['type'],
                    'k_s' => (string)$val['id'],
                    'v'   => $skulist
                );
                array_push($result, $treekey);
            }
            $params = array(
                'status' => true,
                'data'   => $result,
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