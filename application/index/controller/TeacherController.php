<?php
namespace app\index\controller;
use app\common\model\Teacher;// 教师模型
use app\common\model\Student;//学生模型  
use app\common\model\Klass;
use think\facade\Request;   
use think\Controller;   // 请求
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
}