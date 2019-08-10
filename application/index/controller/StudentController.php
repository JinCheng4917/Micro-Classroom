<?php
namespace app\index\Controller;
use app\common\model\Student;  // 教师模型
use think\facade\Request;   
use think\Controller;   // 请求

class StudentController extends IndexController
{
	public function index()
	{
		 // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;
	}
}