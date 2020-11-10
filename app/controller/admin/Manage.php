<?php
namespace app\controller\admin;

use app\BaseController;
use think\Facade\Db;
use think\facade\Request;
use think\facade\Session;

class Manage extends BaseController
{
    public function index($method)
    {
        switch($method)
        {
            case 'login':
                return $this->login();
            break;
            case 'verify':
                return $this->verify();
            break;
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
    /* 登录后台 */
    public function login()
    {
        $account  = Request::post('account');
        $password = Request::post('password');
        try {
            $hash = Db::table('manage')->where('account', $account)->value('password');
            if (password_verify($password, $hash)) {
                $token = md5(rand(0,999));
                $expired = time();
                Db::table('manage')->where('account', $account)->data(['token' => $token, 'expired' => $expired])->update();
                $list = Db::table('manage')->where('account', $account)->field('name, account, token, expired')->find();
                $params = array(
                    'status'  => true,
                    'data'    => $list,
                    'message' => '欢迎进入cloud后台管理系统～'
                );
                return json($params);
            } else {
                $params = array(
                    'status'  => false,
                    'message' => '账户或密码错误～'
                );
                return json($params);
            }
        } catch(Exception $err) {
            $params = array(
                'status'  => false,
                'message' => '账户或密码错误～'
            );
            return json($params); 
        }
    }
    /* 登录验证 */
    public function verify()
    {
        $time = time();
        $account = Request::post('account');
        $token = Request::post('token');
        $expired = Request::post('expired');

        try {
            $verify = Db::table('manage')->where(['token' => $token, 'expired' => $expired])->field('account')->find();
            if ($verify['account'] == $account) {
                if (($time - $expired) < 3600) { // 超过一小时重新登录
                    $params = array(
                        'status' => true,
                        'data'   => array(
                            'token'   => $token,
                            'expired' => $expired
                        )
                    );
                    return json($params);
                } else {
                    $params = array(
                        'status'  => false,
                        'message' => '登录超时～'
                    );
                    return json($params);
                }
            } else {
                $params = array(
                    'status'  => false,
                    'message' => '登录超时～'
                );
                return json($params);
            }
        } catch(Exception $err) {
            $params = array(
                'status'  => false,
                'message' => '登录超时～'
            );
            return json($params);
        }
    }
    /* 新增管理员 */
    public function add()
    {
        $name = Request::post('name');
        $account = Request::post('account');
        $password = Request::post('password');
        $time     = time();
        $data = [
            'name'     => $name,
            'account'  => $account,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'time'     => $time
        ];

        if (Db::table('manage')->where('account', $account)->find()) {
            $params = array(
                'status'  => false,
                'message' => '该账户已存在～'
            );
            return json($params);
        } else {
            if (Db::name('manage')->insert($data)) {
                $list = Db::table('manage')->where('account', $account)->field('id, name, account')->find();
                $params = array(
                    'status'  => true,
                    'data'    => $list,
                    'message' => '账户创建成功～'
                );
                return json($params);
            } else {
                $params = array(
                    'status'  => false,
                    'message' => '未知错误～'
                );
                return json($params);
            }
        }
    }
    /* 删除管理员 */
    public function del()
    {
        $id = Request::post('id');
        if ($id != 1) {
            if (Db::table('manage')->delete($id)) {
                $params = array(
                    'status'  => true,
                    'message' => '账户删除成功～'
                );
                return json($params);
            } else {
                $params = array(
                    'status'  => false,
                    'message' => '账户删除失败～'
                );
                return json($params);
            }
        } else {
            $params = array(
                'status'  => false,
                'message' => '超级管理员不允许删除～'
            );
            return json($params); 
        }
    }
    /* 编辑管理员 */
    public function edit()
    {
        $id = Request::post('id');
        $name = Request::post('name');
        $account = Request::post('account');
        $password = Request::post('password');
        $change = Request::post('change');

        if ($password == $change) {
            $params = array(
                'status'  => false,
                'message' => '新旧密码相同～'
            );
            return json($params);
        } else {
            try {
                $hash = Db::table('manage')->where('id', $id)->value('password');
                if (password_verify($password, $hash)) {
                    Db::table('manage')->where('id', $id)->update(['name' => $name, 'password'=> password_hash($change, PASSWORD_DEFAULT)]);
                    $list = array(
                        'name' => $name
                    );
                    $params = array(
                        'status'  => true,
                        'data'    => $list,
                        'message' => '账号修改成功～'
                    );
                    return json($params);  
                } else {
                    $params = array(
                        'status'  => false,
                        'message' => '密码错误～'
                    );
                    return json($params);  
                }
            } catch(Exception $err) {
                $params = array(
                    'status'  => false,
                    'message' => '账户编辑失败～'
                );
                return json($params);
            }
        }
    }
    /* 查询管理员 */
    public function query()
    {
        $query = Db::table('manage')->field('id, name, account, time')->select();
        $params = array(
            'status' => true,
            'data'   => $query
        );
        return json($params);
    }
}
