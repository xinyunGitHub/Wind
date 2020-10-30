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
    /* 新增商品上传接口 */
    public function upload()
    {
        // 获取表单上传文件
        $files = request()->file();
        try {
            validate(['image'=>'filesize:10240|fileExt:jpg|image:200,200,jpg'])
                ->check($files);
            $savename = [];
            foreach($files as $file) {
                $savename = \think\facade\Filesystem::disk('public')->putFile('goods', $file);
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
    /* 新增商品信息 */
    public function add()
    {
        $biggest = Db::table('goods')->field('max(id)')->select();
        $count = (string)(100000000 - $biggest[0]['max(id)']);
        $unique = sha1(md5(hash('ripemd160', $count)));
        $thumb = Request::post('thumb');
        $title = Request::post('title');
        $price = Request::post('price');
        $tally = Request::post('tally');
        $data = [
            'unique' => $unique,
            'thumb'  => $thumb,
            'title'  => $title,
            'price'  => $price,
            'tally'  => $tally,
        ];

        if (Db::table('goods')->where('unique', $unique)->find()) {
            $params = array(
                'status'  => true,
                'message' => '商品新建失败～'
            );
            return json($params);
        } else {
            if (Db::name('goods')->insert($data)) {
                $list = Db::table('goods')->where('unique', $unique)->find();
                $params = array(
                    'status' => true,
                    'data'   => $list,
                    'message' => '商品新建成功～'
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
    /* 删除商品信息 */
    public function del()
    {
        $unique = Request::post('unique');
        try {
            Db::table('goods')->where('unique', $unique)->delete();
            Db::table('detail')->where('unique', $unique)->delete();
            Db::table('figure')->where('unique', $unique)->delete();
            Db::table('thumb')->where('unique', $unique)->delete();
            // 删除sku
            $data = Db::name('skukey')->where('unique', $unique)->select();
            foreach ($data as $list) {
                Db::name('skuvalue')->where('sku', $list['id'])->delete();
            }
            Db::name('skukey')->where('unique', $unique)->delete();
            $params = array(
                'status'  => true,
                'message' => '商品信息删除成功～'
            );
            return json($params);
        } catch(Exception $err) {
            $params = array(
                'status'  => false,
                'message' => '商品信息删除失败～'
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

        try {
            Db::table('goods')->where('unique', $unique)->update($data);
            $params = array(
                'status'  => true,
                'data'    => $data,
                'message' => '商品信息修改成功～'
            );
            return json($params);
        } catch(Exception $err) {
            $params = array(
                'status'  => false,
                'message' => '商品信息修改失败～'
            );
            return json($params);
        }
    }
    /* 查询商品信息列表 */
    public function query()
    {
        $query = Db::table('goods')->select();
        $params = array(
            'status' => true,
            'data'   => $query
        );
        return json($params);
    }
}
