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
use think\Db;
use app\common\model\Remind;
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

    // 获取session中存的config
    $config = session('config');
    $app = Factory::officialAccount($config);

    // 获取所有关注公众号用户的openid
    $openIdList = $app->user->list($nextOpenId = null); 

    //获取最大用户数 
    $maxUserNum = count($openIdList)-1;
    session('maxUserNum',$maxUserNum);

    // 获取注册用户的id，储存进id数组
    for ($i=0; $i<$maxUserNum; $i++) {
    // 从student表中根据openid获取学生的id

      $studentId = Student::where('openid',$openIdList['data']['openid'][$i])->value('id');
     
      if (!is_null(Student::returnKlassInfo($studentId))) {
        $studentInfo = Student::returnKlassInfo($studentId);

        // 调用send方法用微信发送模板消息
        $this->send($studentInfo['name'],$studentInfo['room'],$openIdList['data']['openid'],$studentInfo['courseNumber'],$studentInfo['timeId']);

      } 

    }

    return $this->fetch();
  }

  // 发送上课提醒
  public function send($courseName,$courseRoom,$openid,$courseNumber,$timeId) {
    $config = session('config');
    $app = Factory::officialAccount($config);
    $date = date('Y-m-d H:i:s');
    // 获取所有模板列表
    $template = $app->template_message->getPrivateTemplates();
    
    // 选择指定的后台模板
    $template_id = $template["template_list"]['1']['template_id'];

    // 从session中取出maxUserNum
    $maxUserNum = session('maxUserNum');
    // 利用循环发送消息
    for ($i=0; $i<$maxUserNum ; $i++) {
      if (is_null($template_id)) {
        return $this->error('template_id is null,please add template');
      } else {
          for ($j=0; $j<$courseNumber; $j++) {
            $app->template_message->send([
            // 'touser' => 'oTOWXwt0Y-8ALy_d6sejRixNQZJ0',  //小号2
            'touser' => $openid[$i],  //大号
            // 'touser' => 'oTOWXwtWpe-Iv_DTbmD5qLd7kKAU',     //文达
                        
            'template_id' => $template_id,
            'data' => [
              'date' => $date,
              'klassroom' => $courseRoom[$j],
              'time' => $timeId[$j],
              'name' =>  $courseName[$j],
              // 'teacher' => $teacherName[$j]
             
              ],
           ]);
          }
        
      }
    }
      
    
    return 'success';


  }




  
}