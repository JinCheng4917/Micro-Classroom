<?php
namespace app\common\model;
use think\Model;
/**
 * 课程
 */
class TeacherCourse extends Model
{
  public function Course()
    {
        return $this->belongsTo('Course');
    }
}