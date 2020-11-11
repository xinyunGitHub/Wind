<?php
namespace app\controller\api;

use app\BaseController;
use think\Facade\Db;
use think\facade\Cache;
use think\facade\Request;

use app\validate\Openid;
use app\validate\Unique;
use app\validate\Digital;
use app\validate\Word;
use app\validate\Water;
use think\exception\ValidateException;

class Order extends BaseController
{
    public function index($method)
    {
        switch($method)
        {
            case 'address':
                return $this->address();
            break;
            case 'inventory':
                return $this->inventory();
            break;
            case 'water':
                return $this->water();
            break;
        }
    }
    /* 查询默认收获地址 */
    public function address()
    {
        $openid = Request::post('openid');
        try {
            validate(Openid::class)->check([
                'openid' => $openid
            ]);
            $query = Db::table('address')->where(['openid' => $openid, 'active' => 0])->find();
            $result = array(
                'id'      => $query['id'],
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
    /* 生成订单信息 */
    public function inventory()
    {
        $openid    = Request::post('openid');
        $addressId = Request::post('addressId');
        $gather    = Request::post('gather');
        $count     = Request::post('count');
        $goodsId   = Request::post('goodsId');
        $unique    = Request::post('unique');
        $amount    = Request::post('amount');
        $time      = time();

        try {
            validate(Openid::class)->check([
                'openid' => $openid
            ]);
            validate(Digital::class)->check([
                'digital' => $addressId
            ]);
            validate(Word::class)->check([
                'Word' => $gather
            ]);
            validate(Digital::class)->check([
                'digital' => $count
            ]);
            validate(Digital::class)->check([
                'digital' => $goodsId
            ]);
            validate(Unique::class)->check([
                'unique' => $unique
            ]);
            validate(Digital::class)->check([
                'digital' => $amount
            ]);
            $address = Db::name('address')->where(['id' => $addressId, 'openid' => $openid])->find();
            $goods   = Db::name('detail')->where(['unique' => $unique, 'id' => $goodsId])->find();

            if ($goods['price'] * $count == $amount OR $goods['price'] * $count - 10 == $amount) { // 检验总价是否正确
                $biggest = Db::table('order')->field('max(id)')->select();
                $maxid = (string)$biggest[0]['max(id)'] ? (string)$biggest[0]['max(id)'] : 0;
                $water = strtoupper(hash('haval128,3', $openid.$maxid));
                $data = array(
                    'openid'  => $openid,
                    'name'    => $address['name'],
                    'phone'   => $address['phone'],
                    'area'    => $address['province']. $address['city']. $address['county'],
                    'address' => $address['province']. $address['city']. $address['county'].$address['road'],
                    'unique'  => $goods['unique'],
                    'title'   => $goods['title'],
                    'price'   => $goods['price'],
                    'amount'  => $amount,
                    'gather'  => $gather,
                    'count'   => $count,
                    'water'   => $water,
                    'time'    => $time
                );
                Db::name('order')->insert($data);
                $params = array(
                    'status'  => true,
                    'data'    => $water,
                    'message' => '订单创建成功～'
                );
                return json($params);
            }
        } catch(Exception $err) {
            $params = array(
                'status'  => false,
                'message' => $err
            );
            return json($params); 
        }
    }
    /* 支付流水 */
    public function water()
    {
        $openid = Request::post('openid');
        $water = Request::post('water');

        try {
            validate(Openid::class)->check([
                'openid' => $openid
            ]);
            validate(Water::class)->check([
                'water' => $water
            ]);
            $params = array(
                'status'  => true,
                'message' => '查询支付流水～'
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