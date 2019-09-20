<?php
namespace app\admin\model;
use think\Model;

/**
 * 班级
 */
class Klass extends Model
{
   
    /**
     * 获取对应的教师（辅导员）信息
    
     */
    public function Teacher()
    {
        return $this->belongsTo('teacher');
    }
}