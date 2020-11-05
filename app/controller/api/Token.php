<?php
namespace app\controller\api;

use app\BaseController;
use think\Facade\Db;
use think\facade\Cache;
use think\facade\Request;

class Token extends BaseController
{
    public function index($method)
    {
        switch($method)
        {
            case 'inform':
                return $this->inform();
            break;
        }
    }

    /* 获取微信用户信息 */
    public function inform()
    {
        $appid  = 'wxa946b29f09f66f42';
        $secret = '1ac233dcefdca2a949f5b015c55211bc';
        $code   = Request::post('code');
        try {
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
            $result = json_decode(file_get_contents($url), 1);
            $access_token = $result['access_token'];
            $openid = $result['openid'];
            $inform = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
            $fetch = json_decode(file_get_contents($inform), 1);
            $data = array(
                'openid'      => $fetch['openid'],
                'nickname'    => $fetch['nickname'],
                'sex'         => $fetch['sex'],
                'province'    => $fetch['province'],
                'city'        => $fetch['city'],
                'country'     => $fetch['country'],
                'headimgurl'  => $fetch['headimgurl'],
                // 'unionid'  => $fetch['unionid'] ? $fetch['unionid'] : ''
            );
            Db::name('user')->insert($data);
            $params = array(
                'status' => true,
                'data'   => $fetch,
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
