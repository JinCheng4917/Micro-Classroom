<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 * KlassSignIn班级签到表
 */
class KlassSignin extends Model
{
	// 算出各个班级签到百分比
	public $proportion;
	public function getProportion() 
	{

		$KlassSignin = new KlassSignin;
		$student_signed_quantity = $this->getData('student_signed_quantity');
		$student_quantity = $this->getData('student_quantity');
		
		$proportion = (floor($student_signed_quantity * 10000 / $student_quantity) / 100) . '%';
	
		
		return $proportion;	
		
	}
		
			

	
	
}