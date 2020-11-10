<?php
namespace app\controller\admin;

use app\BaseController;
use think\Facade\Db;
use think\facade\Request;

class Detail extends BaseController
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

    /* 新增商品详情上传接口 */
    public function upload()
    {
        // 获取表单上传文件
        $files = request()->file();
        try {
            validate(['image'=>'filesize:10240|fileExt:jpg|image:200,200,jpg'])
                ->check($files);
            $savename = [];
            foreach($files as $file) {
                $savename = \think\facade\Filesystem::disk('public')->putFile('detail', $file);
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
    /* 新增商品详情 */
    public function add()
    {
        $unique   = Request::post('unique');
        $title    = Request::post('title');
        $price    = Request::post('price');
        $figure   = Request::post('figure');
        $describe = Request::post('describe');
        $time     = time();

        $detail = [
            'unique' => $unique,
            'title'  => $title,
            'price'  => $price,
            'time'   => $time
        ];

        try {
            if (Db::table('detail')->where('unique', $unique)->find()) {
                $params = array(
                    'status'  => false,
                    'message' => '同一商品不可重复添加商品详情～'
                );
                return json($params);
            } else {
                Db::name('detail')->insert($detail);
                foreach ($figure as $val) {
                    $list = [
                        'unique' => $unique,
                        'figure' => $val,
                    ];
                    Db::name('figure')->insert($list);
                }
                foreach ($describe as $des) {
                    $item = [
                        'unique'   => $unique,
                        'describe' => $des,
                    ];
                    Db::name('thumb')->insert($item);
                }
                $list = Db::table('detail')->where('unique', $unique)->find();
                $list['figure']   = $figure;
                $list['describe'] = $describe;
                $params = array(
                    'status'  => true,
                    'data'    => $list,
                    'message' => '商品详情新建成功～'
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
    /* 删除商品详情 */
    public function del()
    {
        $unique = Request::post('unique');
        try {
            Db::table('detail')->where('unique', $unique)->delete();
            Db::table('figure')->where('unique', $unique)->delete();
            Db::table('thumb')->where('unique', $unique)->delete();
            $params = array(
                'status'  => true,
                'message' => '商品信息删除成功～'
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
    /* 更新商品详情 */
    public function edit()
    {
        $unique = Request::post('unique');
        $title = Request::post('title');
        $price = Request::post('price');
        $figure = Request::post('figure');
        $describe = Request::post('describe');

        $detail = [
            'title' => $title,
            'price' => $price,
        ];

        try {
            Db::name('detail')->where('unique', $unique)->update($detail);
            if (Db::table('figure')->where('unique', $unique)->delete()) {
                foreach ($figure as $val) {
                    $list = [
                        'unique' => $unique,
                        'figure' => $val
                    ];
                    Db::name('figure')->insert($list);
                }
            }

            if (Db::table('thumb')->where('unique', $unique)->delete()) {
                foreach ($describe as $des) {
                    $item = [
                        'unique'   => $unique,
                        'describe' => $des
                    ];
                    Db::name('thumb')->insert($item);
                }
            }

            $list = array(
                'title'    => $title,
                'price'    => $price,
                'figure'   => $figure,
                'describe' => $describe
            );

            $params = array(
                'status'  => true,
                'data'    => $list,
                'message' => '商品详情编辑成功～'
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
    /* 查询商品详情列表 */
    public function query()
    {
        $query = Db::table('detail')->select();
        $data = array();
        foreach ($query as $key => $que) {
            $unique = $que['unique'];

            $figure = array();
            $list = Db::table('figure')->where('unique', $unique)->select();
            foreach ($list as $keyfig => $fig) {
                $figure[$keyfig] = $fig['figure'];
            }
            $que['figure'] = $figure;

            $describe = array();
            $item = Db::table('thumb')->where('unique', $unique)->select();
            foreach ($item as $keydes => $des) {
                $describe[$keydes] = $des['describe'];
            }
            $que['describe'] = $describe;

            $data[$key] = $que;
        }
        $params = array(
            'status' => true,
            'data'   => $data
        );
        return json($params);
    }
}
