<?php
namespace app\controller\admin;

use app\BaseController;
use think\Facade\Db;
use think\facade\Request;

class Advert extends BaseController
{
    public function index($method)
    {
        switch($method)
        {
            case 'upload':
                return $this->upload();
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
    /* 新增广告上传接口 */
    public function upload()
    {
        // 获取表单上传文件
        $files = request()->file();
        try {
            validate(['image'=>'filesize:10240|fileExt:jpg|image:200,200,jpg'])
                ->check($files);
            $savename = [];
            foreach($files as $file) {
                $savename = \think\facade\Filesystem::disk('public')->putFile('advert', $file);
                $params = array(
                    'status' => true,
                    'data'   => $savename
                );
                return json($params);
            }
        } catch (\think\exception\ValidateException $e) {
            echo $e->getMessage();
        }
    }

    /* 新增首页广告数据 */
    public function add()
    {
        $thumb = Request::post('thumb');
        $sort  = Request::post('sort');
        $route = Request::post('route');
        $time  = time();

        try {
            $data = [
                'thumb' => $thumb,
                'sort'  => $sort,
                'route' => $route,
                'time'  => $time
            ];
            Db::name('advert')->insert($data);
            $list   = Db::table('advert')->where('thumb', $thumb)->find();
            $params = array(
                'status'  => true,
                'data'    => $list,
                'message' => '广告新建成功～'
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

    /* 删除商品信息 */
    public function del()
    {
        $id = Request::post('id');
        try {
            Db::table('advert')->where('id', $id)->delete();
            $params = array(
                'status'  => true,
                'message' => '广告信息删除成功～'
            );
            return json($params);
        } catch(Exception $err) {
            $params = array(
                'status'  => false,
                'message' => '广告信息删除失败～'
            );
            return json($params);
        }
    }

    /* 更新广告信息 */
    public function edit()
    {
        $id    = Request::post('id');
        $thumb = Request::post('thumb');
        $sort  = Request::post('sort');
        $route = Request::post('route');
        $data = [
            'thumb' => $thumb,
            'sort'  => $sort,
            'route' => $route
        ];

        try {
            Db::table('advert')->where('id', $id)->update($data);
            $params = array(
                'status'  => true,
                'data'    => $data,
                'message' => '广告信息修改成功～'
            );
            return json($params);
        } catch(Exception $err) {
            $params = array(
                'status'  => false,
                'message' => '广告信息修改失败～'
            );
            return json($params);
        }
    }

    /* 查询首页广告数据 */
    public function query()
    {
        $query  = Db::table('advert')->select();
        $params = array(
            'status' => true,
            'data'   => $query
        );
        return json($params);
    }
}
