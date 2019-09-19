<?php
namespace app\common\model;
use think\Model;
/**
 * 课程
 */
class Course extends Model
{
    /**
     * 多对多关联
     * @author 梦云智 http://www.mengyunzhi.com
     * @DateTime 2016-10-24T16:26:58+0800
     */
    public function Klasses()
    {
        return $this->belongsToMany('Klass', 'klass_course');
    }
     public function Teachers()
    {
        return $this->belongsToMany('Teacher', 'teacher_course');
    }
    /**
     * 一对多关联
     */
    public function KlassCourses()
    {
        return $this->hasMany('KlassCourse');
    }
    public function TeacherCourses()
    {
        return $this->hasMany('TeacherCourse');
    }
    
    public function getIsChecked(Klass &$Klass)
    {
        // 取课程ID
        $courseId = (int)$this->id;
        $klassId = (int)$Klass->id;

        // 定制查询条件
        $map = array();
        $map['klass_id'] = $klassId;
        $map['course_id'] = $courseId;

        // 从关联表中取信息
        $KlassCourse = KlassCourse::get($map);
        if (is_null($KlassCourse)) {
            return false;
        } else {
            return true;
        }
    }

    public function teacherIsChecked(Teacher &$Teacher)
    {
        // 取课程ID
        $courseId = (int)$this->id;
        $teacherId = (int)$Teacher->id;

        // 定制查询条件
        $map = array();
        $map['teacher_id'] = $teacherId;
        $map['course_id'] = $courseId;

        // 从关联表中取信息
        $TeacherCourse = TeacherCourse::get($map);
        if (is_null($TeacherCourse)) {
            return false;
        } else {
            return true;
        }
    }
      public function Klass()
    {
        return $this->belongsTo('klass');
    }
    public function Term()
    {
        return $this->belongsTo('term');
    }
}