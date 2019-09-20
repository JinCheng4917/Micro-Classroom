<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 * KlassSignIn 班级签到表
 */
class KlassSignIn extends Model
{
	// 定义变量$proportion
	public $proportion;

	// 定义函数getProportion，用于计算签到人数占总人数的百分比
	public function getProportion()
	{
		// 实例化对象
		$KlassSignin = new KlassSignin;

		// 从表中获取签到人数
		$student_signed_quantity = $this->getData('student_signed_quantity');

		// 获取班级总人数
		$student_quantity = $this->getData('student_quantity');

		// 利用floor取整，计算百分比，*10000再/100是为了只获取2位小数
		$proportion = (floor($student_signed_quantity * 10000 / $student_quantity) / 100) . '%';


		// 返回占比
		return $proportion;
	}
}




