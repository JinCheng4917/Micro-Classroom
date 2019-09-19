<?php
namespace app\admin\validate;
use think\Validate;

class Term extends Validate
{
    protected $rule = [
       'name' => 'require',
    ];
}