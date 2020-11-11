<?php
namespace app\controller\api;

use app\BaseController;
use think\Facade\Db;
use think\facade\Cache;
use think\facade\Request;

use app\validate\Openid;
use app\validate\Word;
use app\validate\Digital;
use think\exception\ValidateException;

class Address extends BaseController
{
    public function index($method)
    {
        switch($method)
        {
            case 'add':
                return $this->add();
            break;
            case 'edit':
                return $this->edit();
            break;
            case 'del':
                return $this->del();
            break;
            case 'update':
                return $this->update();
            break;
            case 'list':
                return $this->list();
            break;
        }
    }

    /* 新增地址 */
    public function add()
    {
        $openid   = Request::post('openid');
        $name     = Request::post('name');
        $phone    = Request::post('phone');
        $province = Request::post('province');
        $city     = Request::post('city');
        $county   = Request::post('county');
        $road     = Request::post('road');
        $time     = time();
        try {
            validate(Openid::class)->check([
                'openid' => $openid
            ]);
            validate(Word::class)->check([
                'Word' => $name
            ]);
            validate(Digital::class)->check([
                'digital' => $phone
            ]);

            $data = array(
                'openid'   => $openid,
                'name'     => $name,
                'phone'    => $phone,
                'province' => $province,
                'city'     => $city,
                'county'   => $county,
                'road'     => $road,
                'active'   => 0,
                'time'     => $time
            );
            $active = array(
                'active' => 1
            );

            Db::name('address')->where('openid', $openid)->update($active);
            Db::name('address')->insert($data);
            $params = array(
                'status'  => true,
                'message' => '地址新增成功～',
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

    /* 编辑地址 */
    public function edit()
    {
        $openid   = Request::post('openid');
        $id       = Request::post('id');
        $name     = Request::post('name');
        $phone    = Request::post('phone');
        $province = Request::post('province');
        $city     = Request::post('city');
        $county   = Request::post('county');
        $road     = Request::post('road');
        try {
            validate(Openid::class)->check([
                'openid' => $openid
            ]);
            validate(Digital::class)->check([
                'digital' => $id
            ]);
            validate(Word::class)->check([
                'Word' => $name
            ]);
            validate(Digital::class)->check([
                'digital' => $phone
            ]);

            $data = array(
                'openid'   => $openid,
                'name'     => $name,
                'phone'    => $phone,
                'province' => $province,
                'city'     => $city,
                'county'   => $county,
                'road'     => $road,
                'active'   => 0
            );
            $active = array(
                'active' => 1
            );

            Db::name('address')->where('openid', $openid)->update($active);
            Db::name('address')->where(['openid' => $openid, 'id' => $id])->update($data);
            $params = array(
                'status'  => true,
                'message' => '地址编辑成功～',
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

    /* 删除地址 */
    public function del()
    {
        $openid = Request::post('openid');
        $id     = Request::post('id');
        try {
            validate(Openid::class)->check([
                'openid' => $openid
            ]);
            validate(Digital::class)->check([
                'digital' => $id
            ]);
            $query = Db::name('address')->where(['openid' => $openid, 'id' => $id])->find();
            if ($query['active'] == 0) {
                Db::name('address')->where(['openid' => $openid, 'id' => $id])->delete();
                $latest = Db::name('address')->where('openid', $openid)->field('max(id)')->select();
                $maxid = (string)$latest[0]['max(id)'];
                if ($maxid) {
                    Db::name('address')->where(['openid' => $openid, 'id' => $maxid])->update(['active' => 0]);
                }
                $params = array(
                    'status'  => true,
                    'message' => '地址删除成功～',
                );
                return json($params);
            } else {
                Db::name('address')->where(['openid' => $openid, 'id' => $id])->delete();
                $params = array(
                    'status'  => true,
                    'message' => '地址删除成功～',
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

    /* 更新默认地址 */
    public function update()
    {
        $openid = Request::post('openid');
        $id     = Request::post('id');
        try {
            validate(Openid::class)->check([
                'openid' => $openid
            ]);
            validate(Digital::class)->check([
                'digital' => $id
            ]);
            Db::name('address')->where('openid', $openid)->update(['active' => 1]);
            Db::name('address')->where(['openid' => $openid, 'id' => $id])->update(['active' => 0]);
            $params = array(
                'status'  => true,
                'message' => '默认地址更新成功～'
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

    /* 新增地址 */
    public function list()
    {
        $openid = Request::post('openid');
        try {
            validate(Openid::class)->check([
                'openid' => $openid
            ]);
            $result = array();
            $query = Db::table('address')->where('openid', $openid)->select();
            foreach ($query as $val) {
                $list = array(
                    'id'        => $val['id'],
                    'tel'       => substr_replace($val['phone'], '*****', '3', '5'),
                    'name'      => $val['name'],
                    'province'  => $val['province'],
                    'city'      => $val['city'],
                    'county'    => $val['county'],
                    'road'      => $val['road'],
                    'address'   => $val['province'].$val['city'].$val['county'].$val['road'],
                    'isDefault' => $val['active'] == 0 ? true : false,
                );
                array_push($result, $list);
            }
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
}