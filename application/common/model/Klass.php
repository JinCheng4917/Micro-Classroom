<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 * 
 */
class Klass extends Model
{
   
    /*
     * 获取对应的教师（辅导员）信息
     */
    public function Teacher()
    {
        return $this->belongsTo('teacher');
    }
     public function Student()
    {
        return $this->hasMany('Student');
    }
     public function Term()
    {
        return $this->belongsTo('term');
    }
    public function Courses()
    {
        return $this->belongsToMany('Course', 'klass_course');
    }
}