<?php
namespace app\index\controller;
use app\common\model\Teacher;// 教师模型
use app\common\model\Student;//学生模型  
use app\common\model\Klass;
use app\common\model\Score;
use app\common\model\Course;
use app\common\model\Term;
use app\common\model\Chat;
use think\facade\Request;   
use think\Controller;   // 请求
use think\Db;
/**
 * 教师管理，继承think\Controller后，就可以利用V层对数据进行打包了。
 */
class TeacherController extends TeacherIndexController
{
   protected $batchValidate = true;
   //教师端主界面
    public function index()
    {
       $htmls = $this->fetch();
       return $htmls;
    }
    //上课签到
    public function getQR()
    {
       // $url = 'http://'.$_SERVER['HTTP_HOST'].'/index/student/StudentSignIn';
       $url = 'http://localhost/micro-classroom/public/index/student/signin';
       $this->assign('url',$url);
       dump($url);
        $htmls = $this->fetch();
        return $htmls;
    }
    //放大二维码
    public function largeQR()
    {
       $htmls = $this->fetch();
        return $htmls;
    }
    //随机提问
    public function randomQuestions()
    {
      $htmls = $this->fetch();
        return $htmls;
    }


    //查看签到信息
    public function seeSignin()
    {
      $htmls = $this->fetch();
        return $htmls; 
    }
  
    //查看学期末签到情况
     public function getSigninAll()
    {
      $htmls = $this->fetch();
        return $htmls; 
    }
    //查看当堂未签到学生信息
    public function getThis()
    {
      $htmls = $this->fetch();
        return $htmls; 
    }
    //查看期末未签到学生信息
    public function getAll()
    {
   $htmls = $this->fetch();
        return $htmls; 
    }
    //选择要查看学生信息的课程
    public function studentSelect()
    {
       //获取当前登陆教师的id
        $id = session('teacherId'); 
        //获取score表里的全部信息
        $score = Score::all();
        //获取与当前教师id相同的id的教师的全部信息，筛选出course_id字段
        $Teacherid = Score::where('teacher_id',$id)->column('course_id');
        //获取与当前教师id相同的id的教师的全部信息，筛选出term_id字段
        $Termsid = Score::where('teacher_id',$id)->column('term_id');
        //对course_id这个字段进行筛选
        $Courseid = array_unique($Teacherid);
        //对term_id这个字段进行筛选
        $TermId = array_unique($Termsid);
        //根据course_id这个字段进行逐个循环，根据id获取Course这个对象，以便得到course的name
        foreach ($Courseid as $key => $value)
           {
         // $value即为course的id  用map这个数组承接
                $map['id'] = $value;
         //用键值$key区分course对象  用get()方法获得id，从而获得course对象
                $temp[$key] = Course::get($map);
            }
         //根据term_id这个字段进行逐个循环，根据id获取Term这个对象，以便得到term的name
        foreach ($TermId as $key => $value)
           {
         // $value即为term的id  用map这个数组承接
                $map['id'] = $value;
         //用键值$key区分term对象  用get()方法获得id，从而获得term对象
                $term[$key] = Term::get($map);
            } 
           //将获得的对象数组传到v层             
                $this->assign('temp',$temp);
                $this->assign('term',$term);
                $htmls = $this->fetch();
                 return $htmls; 
    }
        //查看本学期学生信息
    public function seeStudents()
    {
        //从上一个V层获取用户所选择的课程的id，以便获取该课程的学生的信息
         $courseid = Request::instance()->post('course');  
         //从上一个V层获取用户所选择的学期的id，以便获取该课程的学生的信息
         $termid = Request::instance()->post('term');   
         //获取当前登陆教师的id
         $id = session('teacherId');  
         //获取score表里的全部信息 
         $score = Score::all();
         //获取与当前课程id相同的id的课程的全部信息
         $Courseid = Score::where('teacher_id',$id)->where('term_id',$termid)->where('course_id',$courseid)->select();
         //把课程的id传到V层，从而获取相关的学生信息
         $this->assign('Courseids',$Courseid);
         $htmls = $this->fetch();
         return $htmls;  
    }
    //进入录入成绩后选择科目
    public function selectCourse()
    {    
        //获取当前登陆教师的id
        $id = session('teacherId'); 
        //获取score表里的全部信息
        $score = Score::all();
   
        //获取与当前教师id相同的id的教师的全部信息，筛选出term_id字段
        $Termsid = Score::where('teacher_id',$id)->column('term_id');
    
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
                $htmls = $this->fetch();
                 return $htmls; 
    }
  //获取前台传过来的平时成绩数据并保存到数据表中
    public function usualScore() {
        
       $data = Request::instance()->param();
       $id = $data['id'];
       $value = $data['usualvalue'];
       $Score = Score::get($id);
       $Score->usual_score = $value;
       $Score->save();
       
    }
     //获取前台传过来的考试成绩数据并保存到数据表中
    public function examScore() {
        
       $data = Request::instance()->post();
       dump($data);
       $id = $data['id'];
       $value = $data['examvalue'];
       $Score = Score::get($id);
       $Score->exam_score = $value;
       $Score->save();
       
    }
    //获取前台传过来的总成绩数据并保存到数据表中
     public function totalScore() {
        
       $data = Request::instance()->post();
       dump($data);
       $id = $data['id'];
       $value = $data['totalValue'];
       $Score = Score::get($id);
       $Score->total_score = $value;
       $Score->save();
       
    }
    public function getCourse() {
        $termId = Request::instance()->param('term/d');
        $id = session('teacherId'); 
        $Course = Score::where('teacher_id',$id)->where('term_id',$termId)->column('course_id');
        $Courseid = array_unique($Course);
         foreach ($Courseid as $key => $value)
           {
         // $value即为Course的id  用map这个数组承接
                $map['id'] = $value;
         //用键值$key区分Course对象  用get()方法获得id，从而获得Course对象
                $courses[$key] = Course::get($map);
            } 
        return $courses;
    }
      //获取前台传入的平时成绩的权重值，计算出考试成绩的权重值后返回给前台
    public function getWeight()
    {
    $usualScore = Request::instance()->param('usualScore');
    $examScore = 100-($usualScore*100).'%';    
    return $examScore;
    }
    
    public function putScore()
   {  
         //从上一个V层获取用户所选择的课程的id，以便获取该课程的学生的信息
         $courseid = Request::instance()->post('course');  
         //从上一个V层获取用户所选择的学期的id，以便获取该课程的学生的信息
         $termid = Request::instance()->post('term');   
         //获取当前登陆教师的id
         $id = session('teacherId');  
         //获取score表里的全部信息 
         $score = Score::all();
         //获取与当前课程id相同的id的课程的全部信息
         $Courseid = Score::where('teacher_id',$id)->where('term_id',$termid)->where('course_id',$courseid)->select();
         //把课程的id传到V层，从而获取相关的学生信息
         $this->assign('Courseids',$Courseid);
         $htmls = $this->fetch();
         return $htmls;  
    }
    //查看留言者
    public function message()
    {
    $id = session('teacherId'); 
    $studentIds = Chat::where('teacher_id',$id)->column('student_id');
    $studentId = array_unique($studentIds);
    if(isset($studentId))
    {
         foreach ($studentId as $key => $value)
           {
         // $value即为term的id  用map这个数组承接
                $map['id'] = $value;
         //用键值$key区分term对象  用get()方法获得id，从而获得term对象
                $student[$key] = Student::get($map);
            } 
          $this->assign('student',$student);
    }
    $htmls = $this->fetch();
        return $htmls;    
    }
  /*
  **查看留言
   */
public function getMessage()
{
    $teacherId = session('teacherId');
    $studentId = Request::instance()->param('student_id');
    $chats = Chat::where('teacher_id',$teacherId)->where('student_id',$studentId)->select();
    $this->assign('studentId',$studentId);
    $this->assign('teacherId',$teacherId);
    $this->assign('chats',$chats);
    return $this->fetch();
}
/*
**保存留言
 */
public function saveMessage()
{
   //获取当前登陆的学生id
    $teacherId = session('teacherId');
    //获取教师的id
    $studentId = Request::instance()->param('student_id');
    //获取学生的留言
    $teacherChat = Request::instance()->param('teacher_chat');
    //如果非空则赋值并保存
    $chat = new Chat;
    if (!is_null($studentId)&& !is_null($teacherId))
    {
    $chat->student_id = $studentId;
    $chat->teacher_id = $teacherId;
    $chat->teacher_chat = base64_encode($teacherChat);
    $chat->save();
    return $this->success('发送成功',url('getMessage') . '?student_id=' . $studentId);
}
}
/*
** 清空消息
 */
public function deleteMessage()
{
$teacherId = session('teacherId');
$studentId = Request::instance()->param('student_id'); 
$chats = Chat::where('teacher_id',$teacherId)->where('student_id',$studentId);
if(! $chats->delete())
{
return $this->error('清空失败:' . $Student->getError());
}
return $this->success('清空成功' , url('sentMessage') . '?student_id=' . $studentId);
}
    // change password
    public function alter_password()
    {
        $htmls = $this->fetch();
        return $htmls;
    }
    // forget password
    public function forget_password()
    {
        $htmls = $this->fetch();
        return $htmls;
    }

    public function register()
    {
        $htmls = $this->fetch();
        return $htmls;
    }

    // 随机签到-显示随机提问时的学生信息
    public function show()
    {
        // 连接2个数据库
        Db::connect('yunzhi_teacher')->table('yunzhi_student')->find();

        // 从数据库获取全体学生姓名，学号，放入array
        $Student = new Student;

        $Students = $Student->select();

        // 获取所有的id，并且放进一个数组$studentIds
        $studentIds = Db::table('yunzhi_student')->where('id','>',0)->column('id');
 
        // 计算yunzhi_teacher表中用户总数，$number为一个整数
        // $number = Db::table('yunzhi_student')->count();
        
        // 打乱这个id数组的顺序
        shuffle($studentIds);

        // 幸运id为打乱顺序的数组的第一个
        $LuckyNumber = $studentIds[0];

        // 通过id找出其对应的学生姓名
        $LuckyName = Db::table('yunzhi_student')->where('id',$LuckyNumber)->value('name');

        // 通过id找出其对应学生学号
        $LuckyNumber = Db::table('yunzhi_student')->where('id',$LuckyNumber)->value('num');
        $this->assign('LuckyName',$LuckyName);
        $this->assign('LuckyNumber',$LuckyNumber);
        
        return $this->fetch('show');
        
    }

}