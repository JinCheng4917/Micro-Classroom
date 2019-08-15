<?php
namespace app\index\controller;
use app\common\model\Teacher;// 教师模型
use app\common\model\Student;//学生模型  
use app\common\model\Klass;
use think\facade\Request;   
use think\Controller;   // 请求
use think\Db;
/**
 * 教师管理，继承think\Controller后，就可以利用V层对数据进行打包了。
 */
class TeacherController extends IndexController
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
    public function usualScore() {
        
       $data = Request::instance()->post();
       $id = $data['id'];
       $value = $data['value'];

       $Student = Student::get($id);
       $Student->usual_score = $value;
       $Student->save();
       
    }
    public function examScore() {
        
       $data = Request::instance()->post();
       $id = $data['id'];
       $value = $data['value'];

       $Student = Student::get($id);
       $Student->exam_score = $value;
       $Student->save();
       
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
    //查看本学期学生信息
    public function seeStudents()
    {
     $htmls = $this->fetch();
        return $htmls;  
    }
    //录入成绩总体表单
    public function putScore()
    {
        $students = Student::all();
        $this->assign('students',$students);
     $htmls = $this->fetch();
        return $htmls;  
    }
    //录入成绩个人表单
    public function scoreList()
    {
      $htmls = $this->fetch();
        return $htmls;  
    }
    //查看留言
    public function message()
    {
    $htmls = $this->fetch();
        return $htmls;    
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