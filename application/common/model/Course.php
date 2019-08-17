<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 * Teacher 教师表
 */
class Course extends Model
{
public function index()
{
	
}
public function Teacher()
{
	return $this->belongsTo('teacher');
}
}