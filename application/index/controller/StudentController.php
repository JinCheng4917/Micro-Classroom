<?php
namespace app\index\controller;
use app\common\model\Student;  // 学生模型
use app\common\model\Teacher;
use app\common\model\Score;
use app\common\model\Course;
use app\common\model\CourseList;
use app\common\model\Term;
use think\facade\Request; 
use app\common\model\Chat;
use think\Controller;   // 请求

class StudentController extends StudentIndexController
{
	protected $batchValidate = true;
	public function index()
	{
		 // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;
    }
	//查看当天的课表
    public function courseList()
    {
        //获取当前登陆学生的id
        $id = session('studentId'); 
        //获取课表的所有信息
        $list = CourseList::all();
        //开学时间
        $date = '2019-08-26';
        //开学当天是周几 
        $w    = date('w',strtotime($date));//0代表周日  0-6 日-六
        
        //开学周的周一的日期
        $kx_week = date('Y-m-d',strtotime("$date -".($w ? $w - 1 : 6).' days'));//第一周周1日期
        
        //当前日期
        $date = date('Y-m-d'); 
        
        //当前是周几
        $day    = date('w',strtotime($date));
        
        //当前周次的周一的日期
        $current_week = date('Y-m-d',strtotime("$date -".($day ? $day - 1 : 6).' days'));
        //当前周次  
        $week = (strtotime($current_week) - strtotime($kx_week))/(3600*24*7) + 1;
        //获取与当前登录学生相关的信息，并筛选出班级的id
        $klassId = Student::where('id',$id)->column('klass_id');
        //根据班级的id和年月日信息找到当天的课程信息
        $courseList = CourseList::where('klass_id',$klassId[0])->where('week_id',$week)->where('date_id',$day)->select();
        if(!($courseList->count() === 0))
        {
            foreach ($courseList as $key => $value)
            {
         // $value即为courseList的id  用map这个数组承接
                $map['id'] = $value["id"];
         //用键值$key区分courseList对象  用get()方法获得id，从而获得courseList对象
                $course[$key] = CourseList::get($map);
            }
        } else {
            
            $course = 0;
        }
        $this->assign('course',$course);
		 // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;
    }
	//扫码登录
    public function signin()
    {
		 // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;
    }
	//查询分数
    public function getScore()
    {
		//获取当前登陆学生的id
        $id = session('studentId'); 
        //获取score表里的全部信息
        $score = Score::all();
        //获取与当前学生id相同的id的学生的全部信息，筛选出term_id字段
        $Termsid = Score::where('student_id',$id)->column('term_id');
        //对term_id这个字段进行筛选
        $TermId = array_unique($Termsid);
        //根据term_id这个字段进行逐个循环，根据id获取Term这个对象，以便得到term的name
        foreach ($TermId as $key => $value)
        {
         // $value即为term的id  用map这个数组承接
            $map['id'] = $value;
         //用键值$key区分term对象  用get()方法获得id，从而获得term对象
            $term[$key] = Term::get($map);
        } 
           //将获得的对象数组传到v层             
        $this->assign('term',$term);
		 // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;
    }
	//从前台获取的term信息，筛选出course_id属性后获取course对象返回给后台
    public function getCourse() {
	 	//从前台获取term数组
        $termId = Request::instance()->param('term/d');
        $id = session('studentId'); 
        $Course = Score::where('student_id',$id)->where('term_id',$termId)->column('course_id');
        $Courseid = array_unique($Course);
        foreach ($Courseid as $key => $value)
        {
         // $value即为term的id  用map这个数组承接
            $map['id'] = $value;
         //用键值$key区分term对象  用get()方法获得id，从而获得term对象
            $courses[$key] = Course::get($map);
        } 
        return $courses;
    }
	//显示查询出的成绩的界面
    public function scoreDisplay()
    {
		 //从上一个V层获取用户所选择的课程的id，以便获取该课程的学生的信息
       $courseid = Request::instance()->post('course');  
         //从上一个V层获取用户所选择的学期的id，以便获取该课程的学生的信息
       $termid = Request::instance()->post('term');   
         //获取当前登录学生的id
       $id = session('studentId');  
         //获取score表里的全部信息 
       $score = Score::all();
         //获取与当前课程id相同的id的课程的全部信息
       $Courseid = Score::where('student_id',$id)->where('term_id',$termid)->where('course_id',$courseid)->select();
       $this->assign('Courseids',$Courseid);
       $htmls = $this->fetch();
       return $htmls;  
      // 取回打包后的数据
       $htmls = $this->fetch();

        // 将数据返回给用户
       return $htmls;
   }
/*
**留言选择教师
 */
   public function putMessage()
   {
        //获取当前登陆学生的id
    $id = session('studentId'); 
        //获取score表里的全部信息
    $score = Score::all();

        //获取与当前学生id相同的id的学生的全部信息，筛选出course_id字段
    $Courseid = Score::where('student_id',$id)->column('course_id');
    
        //对course_id这个字段进行筛选
    $CourseId = array_unique($Courseid);

         //根据course_id这个字段进行逐个循环，根据id获取Course这个对象，以便得到course的name
    foreach ($CourseId as $key => $value)
    {
         // $value即为course的id  用map这个数组承接
        $map['id'] = $value;
         //用键值$key区分term对象  用get()方法获得id，从而获得course对象
        $course[$key] = Course::get($map);
    } 
           //将获得的对象数组传到v层             

    $this->assign('course',$course);
         // 取回打包后的数据
    $htmls = $this->fetch();

        // 将数据返回给用户
    return $htmls;
}
public function getName() {
    $CourseId = Request::instance()->param('course/d');
    $id = session('studentId'); 
    $Teacher = Score::where('student_id',$id)->where('course_id',$CourseId)->column('teacher_id');
    $Teacherid = array_unique($Teacher);
    foreach ($Teacherid as $key => $value)
    {
         // $value即为term的id  用map这个数组承接
        $map['id'] = $value;
         //用键值$key区分term对象  用get()方法获得id，从而获得term对象
        $teachers[$key] = Teacher::get($map);
    } 
    return $teachers;
}
/*
**  在线留言界面 
 */
public function sentMessage()
{
    $id = session('studentId');
    
    $this->assign('id',$id);
    
   
    $teacherId = Request::instance()->param('teacher_id');
    $chats = Chat::where('student_id',$id)->where('teacher_id',$teacherId)->select();
    $this->assign('teacherId',$teacherId);
    $this->assign('chats',$chats);
 // 取回打包后的数据\
    $htmls = $this->fetch();
 // 将数据返回给用户
    return $htmls;
}
/*
**保存留言
 */
public function saveMessage()
{
    //获取当前登陆的学生id
    $studentId = session('studentId'); 
    //获取教师的id
    $teacherId = Request::instance()->param('teacher_id');
    //获取学生的留言
    $studentChat = Request::instance()->param('student_chat');
    //如果非空则赋值并保存
    $chat = new Chat;
    if (!is_null($studentId)&& !is_null($teacherId))
    {
    $chat->student_id = $studentId;
    $chat->teacher_id = $teacherId;
    $chat->student_chat = base64_encode($studentChat);
    $chat->save();
    return $this->success('发送成功',url('sentMessage') . '?teacher_id=' . $teacherId);
}
}
/*
**删除留言
 */
public function deleteMessage()
{
$teacherId = Request::instance()->param('teacher_id');
$studentId = session('studentId'); 
$chats = Chat::where('teacher_id',$teacherId)->where('student_id',$studentId);
if(! $chats->delete())
{
return $this->error('清空失败:' . $Student->getError());
}
return $this->success('清空成功' , url('sentMessage') . '?teacher_id=' . $teacherId);
}
}