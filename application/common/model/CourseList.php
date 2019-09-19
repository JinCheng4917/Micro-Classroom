<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 * Teacher 教师表
 */
class CourseList extends Model
{
	public function Teacher()
	{
		return $this->belongsTo('teacher');
	}
	public function Klass()
	{
		return $this->belongsTo('klass');
	}
	public function Student()
	{
		return $this->belongsTo('student');
	}
	public function Course()
	{
		return $this->belongsTo('Course');
	}
	public function Time()
	{
		return $this->belongsTo('Time');
	}
	public function Date()
	{
		return $this->belongsTo('Date');
	}
}