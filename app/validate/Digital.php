<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class Digital extends Validate
{
    protected $rule = [
        'digital' => 'require|number'
    ];

    protected $message = [
        'digital.require' => 'digital错误',
    ];
}