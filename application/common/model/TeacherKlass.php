<?php
namespace app\common\model;
use think\Model;
/**
 * 课程
 */
class TeacherKlass extends Model
{
     public function Klass()
    {
        return $this->belongsTo('klass');
    }
}