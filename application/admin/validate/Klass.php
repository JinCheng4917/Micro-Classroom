<?php
namespace app\admin\validate;
use think\Validate;

class Klass extends Validate
{
    protected $rule = [
        'name'  => 'require|length:2,25',
        'teacher_id' => 'require',
        'klass_id' =>'require',
        'course_id' => 'require',
        'date_id' => 'require',
        'time_id' => 'require',
    ];
    protected $scene = [
  'edit' => ['teacher_id'],
  'add' => ['name','teacher_id'],
  'courseSave' => [ 'klass_id','course_id','date_id','time_id'],
    ];
}