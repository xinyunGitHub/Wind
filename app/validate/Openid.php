<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class Openid extends Validate
{
    protected $rule = [
        'openid' => 'require|length:28'
    ];

    protected $message = [
        'openid.require' => 'openid错误',
    ];
}