<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class Unique extends Validate
{
    protected $rule = [
        'unique' => 'require|length:8'
    ];

    protected $message = [
        'unique.require' => 'unique错误',
    ];
}