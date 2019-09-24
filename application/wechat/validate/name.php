<?php
namespace app\wechat\validate;
use think\Validate;     // 内置验证类

class name extends Validate
{
    protected $rule = [
        'name' => 'require',
    ];
    protected $scene = [
      'insert' => ['name'],
    ];


}