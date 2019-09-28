<?php
namespace app\admin\controller;
use app\common\model\Course;            // 课程
use app\common\model\Klass;
use app\common\model\Teacher; 
use app\common\model\TeacherCourse;            // 班级
use app\common\model\KlassCourse;
use app\common\model\Score;
use app\common\model\Term;
use think\Controller;       // 班级课程
use think\facade\Request;
/**
 * 课程管理
 */
class CourseController extends Controller
{
  public function index()
  { 
         // 获取查询信息
    $name = Request::instance()->get('name');
        // 实例化Teacher
    $Course = new Course; 
    trace($Course, 'debug');
        // 按条件查询数据并调用分页
    $courses = Course::where('name', 'like', '%' . $name . '%')->paginate(5);
    $this->assign('courses', $courses);
    $klasses = Klass::all();
    $this->assign('klasses',$klasses);
    $teachers = Teacher::all();
    $this->assign('teachers',$teachers);
    
    return $this->fetch();

  }

  public function add()
  {
    $klasses = Klass::all();
    $teachers = Teacher::all();
        //实例化
    $terms = Term::all();
    $course = new Course;
        //设置默认值
    
    $course->id = 0;
    $course->name = '';
    $course->room = '';
    $course->term_id = 0;
    $this->assign('teachers', $teachers);
    $this->assign('terms', $terms);
    $this->assign('course', $course);
    $this->assign('klasses',$klasses);
    
        // 调用edit模板
    return $this->fetch('edit');
  }

  public function save()
  {
   $klasses = Request::instance()->post('klass_id/a');
      // 存课程信息
   $Course = new Course();
   $Course->name = Request::instance()->post('name');
   if (!is_null($Course))
   {
    $result = $this->validate(
      [
        'name' => Request::instance()->post('name'),
        'klass_id' => Request::instance()->post('klass_id/a'),
        'teacher_id' => Request::instance()->post('teacher_id/a'),
      ],
      'app\admin\validate\Course.add');
    if (true !== $result)
    {
      dump($result);
    }
    else {

     $Course = new Course();
     $Course->name = Request::instance()->post('name');
     $Course->room = Request::instance()->post('room');
     $termId = Request::instance()->post('term_id');
     $Course->term_id = $termId;

        // -------------------------- 新增班级课程信息 -------------------------- 
        // 接收klass_id这个数组
     $klassIds = Request::instance()->post('klass_id/a');  
     $teacherId = Request::instance()->post('teacher_id');
     $Course->save();

     


      //使用循环得到klass的id的数组
     for ($i=0; $i < count($klasses); $i++) { 
      $klassIds[$i] =  $klasses[$i]; 
    }
      //使用循环利用klass_id得到klass的数组
    for ($i=0; $i < count($klassIds); $i++) { 
     $Klass[$i]  = Klass::get( $klassIds[$i]);
   }
    //使用循环得到klass里的student的信息
   if (!isset($Klass[$i]->Student))
   {
     for ($i=0; $i < count($Klass); $i++){
      for ($m=0; $m <count($Klass[$i]->Student); $m++) { 
       $Id[$i][$m] = $Klass[$i]->Student[$m]->id;
     }
   }
 }

 for ($i=0; $i < count($Klass); $i++){
  for ($m=0; $m <count($Klass[$i]->Student); $m++)
  {
   $teacherId = Request::instance()->post('teacher_id');
   $Score  = new Score;
   $Score->teacher_id =  $teacherId;
   $Score->term_id =  $termId;
   $Score->course_id = $Course->id;
   $Score->student_id = $Id[$i][$m];
   $Score->usual_score = 0;
   $Score->exam_score = 0;
   $Score->total_score = 0;
   $Score->save();
 }
}
        //保存中间表
if (!is_null($klassIds)) {
  if (!$Course->Klasses()->saveAll($klassIds)) {
    return $this->error('课程-班级信息保存错误：' . $Course->Klasses()->getError());
  }
}
         //保存中间表
if (!is_null($teacherId)) {
  $TeacherCourse = new TeacherCourse;
  $TeacherCourse->teacher_id =  $teacherId;
  $TeacherCourse->course_id =  $Course->id; 
  $TeacherCourse->term_id = $termId;
  $TeacherCourse->save();
}
}
        // -------------------------- 新增班级课程信息(end) -------------------------- 

return $this->success('操作成功', url('index'));
}
}
public function edit()
{ 
  $teachers = Teacher::all();
  $klasses = Klass::all();
  $terms = Term::all();
  $id = Request::instance()->param('id/d');
  $course = Course::get($id);
  if (is_null($course)) {
    return $this->error('不存在ID为' . $id . '的记录');
  }
  $this->assign('teachers',$teachers);
  $this->assign('klasses',$klasses);
  $this->assign('course', $course);
  $this->assign('terms', $terms);
  return $this->fetch();
}

public function update()
{ 
  $id = Request::instance()->post('id/d');
  
  $Course = Course::get($id);
  $Course->room = Request::instance()->post('room');
  $termId = Request::instance()->post('term_id');
        // 获取当前课程
  if (is_null($Course)) {
    return $this->error('不存在ID为' . $id . '的记录');
  }

        // 更新课程名
  $Course->name = Request::instance()->post('name');
  if (!is_null($Course)){
    $result = $this->validate(
      [
        'name' => Request::instance()->post('name'),
        'klass_id' => Request::instance()->post('klass_id/a'),  
        'room' => Request::instance()->post('room'),  
      ],
      'app\admin\validate\Course.edit');
    if (true !== $result)
    {
      dump($result);
    }
    else
    {
     $Course->name = $Course->name;
     $Course->room = Request::instance()->post('room');
        // 删除原有信息
     $map = ['course_id'=>$id];

        // 执行删除操作。由于可能存在 成功删除0条记录，故使用false来进行判断，而不能使用
        // if (!KlassCourse::where($map)->delete()) {
        // 我们认为，删除0条记录，也是成功
     if (false === $Course->KlassCourses()->where($map)->delete()) {
      return $this->error('删除班级课程关联信息发生错误' . $Course->KlassCourses()->getError());
    }
        // 增加新增数据，执行添加操作。
    $klasses = Request::instance()->post('klass_id/a');
    $oldTeacherId = Request::instance()->param('oldTeacher');
    $teacherId = Request::instance()->post('teacher_id');
 //使用循环得到klass的id的数组
    for ($i=0; $i < count($klasses); $i++) 
    { 
      $klassIds[$i] =  $klasses[$i]; 
    }
      //使用循环利用klass_id得到klass的数组
    for ($i=0; $i < count($klassIds); $i++) 
    { 
     $Klass[$i]  = Klass::get( $klassIds[$i]);
   }
    //使用循环得到klass里的student的信息
   if (!isset($Klass[$i]->Student))
   {
     for ($i=0; $i < count($Klass); $i++)
     {
      for ($m=0; $m <count($Klass[$i]->Student); $m++)
      { 
       $Id[$i][$m] = $Klass[$i]->Student[$m]->id;
     }
   }
 } 

 for ($i=0; $i < count($Klass); $i++){
  for ($m=0; $m <count($Klass[$i]->Student); $m++)
  {
    $Score = Score::where("course_id",$id)->where('student_id', $Id[$i][$m])->where('term_id',  $termId)->find();
    $Score->teacher_id =  $teacherId;
    $Score->term_id =  $termId;
    $Score->course_id = $Course->id;
    $Score->student_id = $Id[$i][$m];
    $Score->save();
  }
}
if (!is_null( $klasses)) {
  if (!$Course->Klasses()->saveAll( $klasses)) {
    return $this->error('课程-班级信息保存错误：' . $Course->Klasses()->getError());
  }
}
unset($KlassCourse);  
if (!is_null($teacherId)) {
  $TeacherCourse = TeacherCourse::where("course_id",$id)->find();
  
  if(!empty($TeacherCourse))
  {
    $TeacherCourse->teacher_id =  $teacherId;
    $TeacherCourse->course_id =  $Course->id; 
    $TeacherCourse->term_id = $termId;
    $TeacherCourse->save();
  }
} 
$Course->save();
}

return $this->success('更新成功', url('index'));

}   
}
public function delete()
{
            // 获取要删除对象的id
  $id = Request::instance()->param('id/d');

            // 判断是否成功接收
  if(is_null($Course= Course::get($id))){
    return $this->error('不存在id为:'.$id.'的课程，删除失败');
  }
            // 删除对象
  if (!$Course->delete()) {
    return $this->error('删除失败:' . $Course->getError());
  }
  $Score = Score::where("course_id",$id);
   $CourseList = CourseList::where("course_id",$id);
  $KlassCourse = KlassCourse::where('course_id',$id);
  $TeacherCourse = TeacherCourse::where('course_id',$id);
  $Score->delete();
  $KlassCourse->delete();
  $CourseList->delete();
  $TeacherCourse->delete();
        // 进行跳转 
  return $this->success('删除成功', url('index')); 
}
}