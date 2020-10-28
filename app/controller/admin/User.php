<?php
namespace app\controller\admin;

use app\BaseController;
use think\Facade\Db;
use think\facade\Request;

class User extends BaseController
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
    /* 新增用户 */
    public function add()
    {
        $openid = Request::post('openid');
        $nickname = Request::post('nickname');
        $sex = Request::post('sex');
        $province = Request::post('province');
        $city = Request::post('city');
        $country = Request::post('country');
        $headimgurl = Request::post('headimgurl');
        $privilege = Request::post('privilege');
        $unionid = Request::post('unionid');
        $data = [
            'openid' => $openid,
            'nickname' => $nickname,
            'sex' => $sex,
            'province' => $province,
            'city' => $city,
            'country' => $country,
            'headimgurl' => $headimgurl,
            'privilege' => $privilege,
            'unionid' => $unionid,
        ];

        if (Db::table('user')->where('openid', $openid)->find()) {
            $params = array(
                'status' => false,
                'message'   => '该用户已存在～'
            );
            return json($params);
        } else {
            if (Db::name('user')->insert($data)) {
                $list = Db::table('user')->where('openid', $openid)->find();
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
    /* 删除用户 */
    public function del()
    {
        $id = Request::post('id');
        if (Db::table('user')->delete($id)) {
            $params = array(
                'status' => true,
                'message'   => '用户删除成功～'
            );
            return json($params);
        } else {
            $params = array(
                'status' => false,
                'message'   => '用户删除失败～'
            );
            return json($params);
        }
    }
    /* 用户信息更新 */
    public function edit()
    {
        $id = Request::post('id');
        $openid = Request::post('openid');
        $nickname = Request::post('nickname');
        $sex = Request::post('sex');
        $province = Request::post('province');
        $city = Request::post('city');
        $country = Request::post('country');
        $headimgurl = Request::post('headimgurl');
        $privilege = Request::post('privilege');
        $unionid = Request::post('unionid');

        $data = [
            'openid' => $openid,
            'nickname' => $nickname,
            'sex' => $sex,
            'province' => $province,
            'city' => $city,
            'country' => $country,
            'headimgurl' => $headimgurl,
            'privilege' => $privilege,
            'unionid' => $unionid,
        ];

        if (Db::table('user')->where('id', $id)->update($data)) {
            $params = array(
                'status' => true,
                'message'   => '用户信息修改成功～'
            );
            return json($params);
        } else {
            $params = array(
                'status' => false,
                'message'   => '用户信息修改失败～'
            );
            return json($params);
        }
    }
    /* 查询用户列表 */
    public function query()
    {
        $query = Db::table('user')->select();
        $params = array(
            'status' => true,
            'data'   => $query
        );
        return json($params);
    }
}
