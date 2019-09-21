<?php
namespace app\admin\validate;
use think\Validate;

class Student extends Validate
{
    protected $rule = [
        'name'  => 'require|length:2,25',
         'num'  => 'require|unique:student|length:6',
         'sex' => 'in:0,1',
        'email' => 'require|email',
    ];
    protected $scene = [
  'edit' => ['name','sex','email'],
  'add' => ['name','num','sex','email'],
    ];
}