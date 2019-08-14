<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Teacher;   //引入教师
class IndexController extends Controller
{
    public function __construct()
    {
   //调用父类构造函数
   parent::__construct();
   //验证用户是否登录
   if (!Teacher::isLogin()){
   return $this->error('请登录后再进行操作',url('Login/index'));
     }
   }
   public function index()
   {
   	
   }
}

