<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 * Teacher 教师表
 */
class Score extends Model
{
   public function Teacher()
    {
        return $this->belongsTo('Teacher');
    }
    public function Student()
    {
        return $this->belongsTo('Student');
    }
    public function Course()
    {
    	return $this->belongsTo('Course');
    }
    public function Term()
    {
        return $this->belongsTo('Term');
    }
}