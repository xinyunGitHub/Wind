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
    public function generate($count) {
        switch(strlen($count)) {
            case 1:
                $count = $count.'00000';
            break;
            case 2:
                $count = $count.'0000';
            break;
            case 3:
                $count = $count.'000';
            break;
            case 4:
                $count = $count.'00';
            break;
            case 5:
                $count = $count.'0';
            break;
        }
        $arr = str_split($count);
        $unique = '';
        foreach ($arr as $val) {
            switch($val)
            {
                case '0':
                    $unique = $unique.'x';
                break;
                case '1':
                    $unique = $unique.'a';
                break;
                case '2':
                    $unique = $unique.'d';
                break;
                case '3':
                    $unique = $unique.'m';
                break;
                case '4':
                    $unique = $unique.'w';
                break;
                case '5':
                    $unique = $unique.'c';
                break;
                case '6':
                    $unique = $unique.'s';
                break;
                case '7':
                    $unique = $unique.'z';
                break;
                case '8':
                    $unique = $unique.'y';
                break;
                case '9':
                    $unique = $unique.'h';
                break;
            }
        }
        return strtoupper(hash('crc32', $unique));
    }

    /* 新增商品信息 */
    public function add()
    {
        // 商品ID规则
        $biggest = Db::table('goods')->field('max(id)')->select();
        $count   = (string)$biggest[0]['max(id)'] ? (string)$biggest[0]['max(id)'] : 0;
        $unique  = $this->generate($count);

        $thumb = Request::post('thumb');
        $title = Request::post('title');
        $price = Request::post('price');
        $tally = Request::post('tally');
        $type  = Request::post('type');

        try {
            $data = [
                'unique' => $unique,
                'thumb'  => $thumb,
                'title'  => $title,
                'price'  => $price,
                'tally'  => $tally,
                'type'   => $type,
            ];
            Db::name('goods')->insert($data);
            $list = Db::table('goods')->where('unique', $unique)->find();
            $params = array(
                'status'  => true,
                'data'    => $list,
                'message' => '商品新建成功～'
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
        $type = Request::post('type');
        $data = [
            'thumb' => $thumb,
            'title' => $title,
            'price' => $price,
            'tally' => $tally,
            'type'  => $type,
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
