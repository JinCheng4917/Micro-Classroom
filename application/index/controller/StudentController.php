<?php
namespace app\index\Controller;
use app\common\model\Student;  // 教师模型
use app\common\model\User;
use think\facade\Request;   
use think\Controller;   // 请求

class StudentController extends UserController
{
	protected $batchValidate = true;

	public function __construct()
    {
	   	//调用父类构造函数
	   	parent::__construct();
	   	//验证用户是否登录

	   	if (!User::isLogin()){
	    
	   		return $this->error('请登录后再进行操作',url('Login/index'));
     	} 



 		
 	}
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
		 // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;
	}
	//留言
	public function putMessage()
	{
		 // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;
	}

	public function forget_password()
	{
		return $this->fetch();
	}

	public function register()
	{
		return $this->fetch();
	}

}