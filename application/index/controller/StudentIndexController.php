<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Student; 
class StudentIndexController extends Controller
{
    public function __construct()
    {
   //调用父类构造函数
   parent::__construct();
   //验证用户是否登录
   if (!Student::isLogin()){
    
   return $this->error('请登录后再进行操作',url('wechat/index/weChatAccredit'));
     }
 
 }
}

