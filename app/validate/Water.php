<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class Water extends Validate
{
    protected $rule = [
        'water' => 'require|length:32'
    ];

    protected $message = [
        'water.require' => 'water错误',
    ];
}