<?php
namespace app\admin\controller;
use app\common\model\Student;
use app\common\model\Klass;
use app\common\model\Score;
use app\common\model\Teacher;
use app\common\model\College;
use app\common\model\Term;
use think\facade\Request;
use think\Controller; 

class StudentController extends Controller
{
 protected $batchValidate = true;
 public function index()
 {
  $student = Student::all();
       // 获取查询信息
  $name = Request::instance()->get('name');
        // 实例化Teacher
  $Klass = new Klass; 
  trace($Klass, 'debug');
        // 按条件查询数据并调用分页
  $klasses = Klass::where('name', 'like', '%' . $name . '%')->paginate(50); 
  $this->assign('student',$student);
  $this->assign('klasses', $klasses);
  return $this->fetch();
}

public function add()
{ 
  $id = Request::instance()->param();
  $klasses = Klass::get($id);
  $colleges = College::all();
  $teacheres = Teacher::all();
  $terms = Term::all();
  $Student=new Student;
  $Student->id=0;
  $Student->name='';
  $Student->num='';
  $Student->sex= 0;
  $Student->major = '';
  $Student->college_id=0;
  $Student->klass_id = $id;
  $Student->email='';
  $Student->term_id=0;
  $this->assign('Student',$Student); 
  $this->assign('klasses',$klasses); 
  $this->assign('colleges',$colleges);
  $this->assign('terms',$terms); 
  $this->assign('teacheres',$teacheres); 
  return $this->fetch('edit');
}

public function save()
{
  $termId = Request::instance()->post('term_id');
    //接收前台传过来的对应学生的班级的课程id
  $courseIds = Request::instance()->post('course_id');
  //接受对应的课程的教师的id
  $teacherIds = Request::instance()->post('teacher_id');
  //实例化
  $Student = new Student;
  $Score = new Score;
    //验证是否添加成功
  $result = $this->validate(
    [
      'name' => Request::instance()->post('name'),
      'num' => Request::instance()->post('num'),
      'sex' => Request::instance()->post('sex'),
      'college_id' => Request::instance()->post('college_id'),
      'klass_id' => Request::instance()->post('klass_id'),
      'email' => Request::instance()->post('email'),
      'major' => Request::instance()->post('major'),
    ],
    'app\admin\validate\Student.add');
  if (true !== $result) {
    dump($result);
  }else
  {

     // 接收传入数据
    $postData = Request::instance()->post();    
            // 实例化Teacher空对象
    $Student = new Student();
            // 为对象赋值
    $Student->name = $postData['name'];
    $Student->num = $postData['num'];
    $Student->sex = $postData['sex'];
    $Student->college_id = $postData['college_id'];
    $Student->klass_id = $postData['klass_id'];
    $Student->email = $postData['email'];
    $Student->major = $postData['major'];
    $Student->term_id = $postData['term_id'];
            // 新增对象至数据表
    $Student->save();
      //接收前台传过来的学期的id

    for ($i=0; $i <count($teacherIds); $i++) { 
      $Score = new Score();
      $Score->teacher_id =  $teacherIds[$i];
      $Score->term_id =  $termId;
      $Score->course_id = $courseIds[$i];
      $Score->student_id = $Student->id;
      $Score->usual_score = 0;
      $Score->exam_score = 0;
      $Score->total_score = 0;
      $Score->save();
    }
    return $this->success('操作成功',url('/admin/student/studentlist/id/'. $Student->klass_id));
  }
}

public function edit()
{
  $terms = Term::all();
  $this->assign('terms',$terms); 
  $colleges = College::all();
  $this->assign('colleges',$colleges);
  
  $teacheres = Teacher::all();
  $this->assign('teacheres',$teacheres); 
  
  $id = Request::instance()->param('id/d');
  $student = Student::get($id);
  $klassid = $student->klass_id;
  $klass = Klass::where('id',$klassid)->select();
  $klasses = $klass[0];
  $this->assign('klasses',$klasses);
  $this->assign('Student',$student);
  return $this->fetch();
}

public function update()
{
  $id = Request::instance()->post('id/d');
    //获取edit传入的学生信息
    //检查ID是否存在
  if (is_null($Student=Student::get($id))) {
    return $this->error('未找到ID为：'.$id.'的学生');
  }
    //更新数据
  if (!is_null($Student)){
    $result = $this->validate(
      [
       'name' => Request::instance()->post('name'),
       'sex' => Request::instance()->post('sex'),
       'college_id' => Request::instance()->post('college_id'),
       'major' => Request::instance()->post('major'),
       'klass_id' => Request::instance()->post('klass_id'),
       'email' => Request::instance()->post('email'), 
     ],
     'app\admin\validate\Student.edit');
    if ( true !== $result)
    {
      dump($result);
    }
    else{
       // 接收传入数据
      $postData = Request::instance()->post();    
      
            // 为对象赋值
      $Student->name = $postData['name'];
      $Student->num = $Student->num;
      $Student->sex = $postData['sex'];
      $Student->college_id = $postData['college_id'];
      $Student->major = $postData['major'];
      $Student->klass_id = $postData['klass_id'];
      $Student->email = $postData['email'];
      $Student->term_id = $Student->term_id;
            // 新增对象至数据表
      $Student->force()->save();
      return $this->success('操作成功',url('/admin/student/studentlist/id/'. $Student->klass_id));
    }
  }
}

public function studentList()
{
  $id = Request::instance()->param('id');
      //获取查询信息
  $student = Student::where('klass_id',$id)->select();
    //分页和查询
  $this->assign('id',$id);
  $this->assign('students',$student);
  return $this->fetch();
} 
public function delete()
{
            // 获取要删除对象的id
  $id = Request::instance()->param('id/d');
  
            // 判断是否成功接收
  if(is_null($Student=Student::get($id))){
    return $this->error('不存在id为:'.$id.'的学生，删除失败');
  }
            // 删除对象
  if (!$Student->delete()) {
    return $this->error('删除失败:' . $Student->getError());
  }
  $Score = Score::where("student_id",$id);
  $Score->delete();
        // 进行跳转 
  return $this->success('删除成功'); 
}
}


