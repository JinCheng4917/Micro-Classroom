<?php
namespace app\admin\controller;
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
  public function __construct() {
    //继承父类构造函数 
    parent::__construct();

    // 定义变量数组config，将其存入session
    $config = [
      'app_id' => 'wxec9127081c13831e',
      'secret' => 'a6c9b838533981f7d3dacc9501baf1e8',

      // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
      'response_type' => 'array',

      //...
    ];

    session('config', $config);
  }




  public function index() {
    
    $config = session('config');
    $app = Factory::officialAccount($config);

    $template = $app->template_message->getPrivateTemplates();
    $list = $app->user->list($nextOpenId = null); 
    dump($list['data']['openid'][0]);
    dump($list['data']['openid'][1]);


    $template_id = $template["template_list"]['1']['template_id'];
    if (is_null($template_id)) {
      return $this->error('template_id is null,please add template');
    } else {
      $app->template_message->send([
        'touser' => 'oTOWXwoq0e2L6Aa8SZt2zoafONak',
        'template_id' => $template_id,
        'data' => [
            'date' => '123',
            'klassroom' => '123',
            'time' => '12.2.3',
            'name' => 'asdf'
           
        ],
      ]);
      }
    
    return 'success';
  }


  
}