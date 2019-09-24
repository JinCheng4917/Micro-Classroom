<?php
namespace app\wechat\controller;
use app\common\model\Student;  // 学生模型
use app\common\model\Teacher;
use app\common\model\Score;
use app\common\model\Course;
use app\common\model\CourseList;
use app\common\model\Term;
use app\common\model\Chat;
use think\facade\Request; 
use think\Controller;   // 请求
use EasyWeChat\Factory;  //使用easywechat类
class RemindController extends Controller
{
	public function index() {
		$config = [
	    'app_id' => 'wxec9127081c13831e',
	    'secret' => 'a6c9b838533981f7d3dacc9501baf1e8',

	    // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
	    'response_type' => 'array',

	    //...
		];

		session('config',$config);
		$app = Factory::officialAccount($config);
	}

	public function test() {
		$config = session('config')
		dump($config);

	}

}