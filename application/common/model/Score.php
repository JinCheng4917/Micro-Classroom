<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 * Teacher 教师表
 */
class Score extends Model
{
public function index()
{
	
}

    /**
     * 一对多关联
     */
    public function Student()
    {
        return $this->belongsTo('Student');
    }
    public function Course()
    {
    	return $this->belongsTo('Course');
    }
}