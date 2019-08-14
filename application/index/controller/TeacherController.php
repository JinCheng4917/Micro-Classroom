<?php
namespace app\index\controller;
use app\common\model\Teacher;  // 教师模型
use app\common\model\Student;  // 学生模型
use app\common\model\KlassSignIn;
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
    //显示随机提问时的学生信息
    public function show()
    {
       $htmls = $this->fetch();
        return $htmls;
    }
    //查看签到信息
    public function seeSignin()
    {
        Db::connect('yunzhi_teacher')->table('yunzhi_klass_signin')->find();        

        $KlassSignIn = new KlassSignIn;
        $KlassSignIns = $KlassSignIn->select();
        $this->assign('KlassSignIns',$KlassSignIns);

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
    //查看本学期学生信息
    public function seeStudents()
    {
        // 这是我使用的2个对接数据库的方法之一，但是我不知道哪个正确，
        // 还是说都正确？
        Db::connect('yunzhi_teacher')->table('yunzhi_student')->find();
        // 实例化Teacher        
        $Student = new Student();

        $Students = $Student->select();

        // 向V层传数据
        $this->assign('Students',$Students);

        // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;  
        return $this->fetch();
    }
    //录入成绩总体表单
    public function putScore()
    {
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
}