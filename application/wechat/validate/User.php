<?php
namespace app\wechat\validate;
use think\Validate;     // 内置验证类

class User extends Validate
{
    protected $rule = [
        'name'  => 'require|length:2,25',
        'username' => '|max:25',
        'num' => 'require|number',
        'email' => 'email',
        

        
    ];
    protected $scene = [
      'insert' => ['name','username','num','email'],
      'add' => ['name','username','num','email'],
      'update' =>['name','num','email'],
    ];


}