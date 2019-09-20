<?php
namespace app\index\controller;
use think\facade\Request;              // 请求
use think\Controller;
use app\common\model\Teacher;   // 教师模型
use app\common\model\Student;
use app\service\Wechat;

class LoginController extends Controller
{
    // 用户登录表单
    public function index()
    {
        // 显示登录表单
        return $this->fetch();
    }
     // 处理用户提交的登录数据
    public function teacherLogin()
    {
        // 接收post信息
        $postData = Request::instance()->post();
        // 直接调用M层方法，进行登录。
        if (Teacher::login($postData['username'], $postData['password'])) {
            return $this->success('login success', url('Teacher/index'));
        } else {
            return $this->error('username or password incorrent', url('index'));
        }
    }
 
    // 注销
    public function teacherLogOut()
    {
        if (Teacher::logOut()) {
            return $this->success('logout success', url('Login/index'));
        } else {
            return $this->error('logout error', url('Login/index'));
        }
    }
    
    public function studentLogin()
    {
        // 接收post信息
        $postData = Request::instance()->post();
        // 直接调用M层方法，进行登录。
        if (Student::login($postData['num'], $postData['password'])) {
            return $this->success('login success', url('Student/index'));
        } else {
            return $this->error('username or password incorrent', url('index'));
        }
    }
    public function studentLogOut()
    {
        if (Student::logOut()) {
            return $this->success('logout success', url('Login/index'));
        } else {
            return $this->error('logout error', url('Login/index'));
        }
    }
}