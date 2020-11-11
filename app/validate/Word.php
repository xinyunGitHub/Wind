<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class Word extends Validate
{
    protected $rule = [
        'Word' => 'require|max:200'
    ];

    protected $message = [
        'Word.require' => 'Word错误',
    ];
}