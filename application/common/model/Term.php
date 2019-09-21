<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 * Term 学期表
 */
class Term extends Model
{
public function index()
{
	
}
public function Course()
{
	return $this->belongsTo('course');
}
}