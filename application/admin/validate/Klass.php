<?php
namespace app\admin\validate;
use think\Validate;

class Klass extends Validate
{
    protected $rule = [
        'name'  => 'require|length:2,25',
        'teacher_id' => 'require',
    ];
    protected $scene = [
  'edit' => ['teacher_id'],
  'add' => ['name','teacher_id'],
    ];
}