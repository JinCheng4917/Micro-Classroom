<?php
namespace app\wechat\validate;
use think\Validate;     // 内置验证类

class Openid extends Validate
{
    protected $rule = [
        'openid' => 'require',
    ];
    protected $scene = [
      'insert' => ['openid'],
    ];


}