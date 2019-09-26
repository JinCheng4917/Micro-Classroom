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

    // 从student表中根据openid获取学生的id
    $maxUserNum = count($openIdList);
    session('maxUserNum',$maxUserNum);

    // 获取注册用户的id，储存进id数组
    for ($i=0;$i<$maxUserNum-1;$i++) {
      $studentId[$i] = Student::where('openid',$openIdList['data']['openid'][$i])->value('id');

    }   

   
        //获取课表的所有信息
        $id =107;
        $list = CourseList::all();
        //开学时间
        $date = '2019-08-26';
        //开学当天是周几 
        $w    = date('w',strtotime($date));//0代表周日  0-6 日-六
          
        //开学周的周一的日期
        $kx_week = date('Y-m-d',strtotime("$date -".($w ? $w - 1 : 6).' days'));//第一周周1日期
          
        //当前日期
        $date = date('Y-m-d'); 
          
        //当前是周几
        $day  = date('w',strtotime($date));
          
        //当前周次的周一的日期
        $current_week = date('Y-m-d',strtotime("$date -".($day ? $day - 1 : 6).' days'));
        //当前周次  
        $week = (strtotime($current_week) - strtotime($kx_week))/(3600*24*7) + 1;
        //获取与当前登录学生相关的信息，并筛选出班级的id
        $klassId = Student::where('id',$id)->column('klass_id');
        //根据班级的id和年月日信息找到当天的课程信息
        $courseList = CourseList::where('klass_id',$klassId[0])->where('week_id',$week)->where('date_id',$day)->select();
            if(!($courseList->count() === 0))
        {
          foreach ($courseList as $key => $value)
          {
        // $value即为courseList的id  用map这个数组承接
              $map['id'] = $value["id"];
       //用键值$key区分courseList对象  用get()方法获得id，从而获得courseList对象
              $course[$key] = CourseList::get($map);
          }
      } else {

          $course = 0;
      }
      $courseNumber = count($course)-1;
      for ($i=1;$i<=12; $i++) {
        if ($course!=0) {
          for ($j=0;$j<=$courseNumber;$j++) {
              if ($course[$j]->time_id == $i) {

                // 获取所有课程名
                $courseName[$j] =  $course[$j]->Course->name;

                // 获取所有教室名
                $courseRoom[$j] =  $course[$j]->Course->room;

                // 获取所有老师名
                // $courseTeachers[$j] = $course[$j]->Course->Teachers[0]->name;
                $timeId[$j] = $course[$j]->time_id;
                
              }
          }
        }
      }
      $this->send($courseName,$courseRoom,$openIdList['data']['openid'],$courseNumber,$timeId);

     
     
// dump($courseName);
    // 向对应id的学生发送模板消息

  

      // 获取课程总数
     
  
    
  
  // return $this->send($courseName,$courseRoom,$courseTeachers);
    
 // 取回打包后的数据

 // [0] => string(28) "oTOWXwtWpe-Iv_DTbmD5qLd7kKAU"
 //  [1] => string(28) "oTOWXwoq0e2L6Aa8SZt2zoafONak"
 //  [2] => string(28) "oTOWXwt0Y-8ALy_d6sejRixNQZJ0"

    return $this->fetch();
  }

  // 发送上课提醒
  public function send($courseName,$courseRoom,$openid,$courseNumber,$timeId) {
    $config = session('config');
    $app = Factory::officialAccount($config);
    $date = date('Y-m-d');
    // 获取所有模板列表
    $template = $app->template_message->getPrivateTemplates();
    
    // 选择指定的后台模板
    $template_id = $template["template_list"]['1']['template_id'];

    // 从session中取出maxUserNum
    $maxUserNum = session('maxUserNum');
    // 利用循环发送消息
    for ($i=0; $i<$maxUserNum-1; $i++) {
      if (is_null($template_id)) {
        return $this->error('template_id is null,please add template');
      } else {
          for ($j=0; $j<=$courseNumber; $j++) {
            $app->template_message->send([
            // 'touser' => 'oTOWXwt0Y-8ALy_d6sejRixNQZJ0',  //小号2
            'touser' => $openid[$i],  //大号
            // 'touser' => 'oTOWXwtWpe-Iv_DTbmD5qLd7kKAU',     //文达
                        
            'template_id' => $template_id,
            'data' => [
              'date' => $date,
              'klassroom' => $courseRoom[$j],
              'time' => $timeId[$j],
              'name' =>  $courseName[$j]
             
              ],
           ]);
          }
        
      }
    }
    return 'success';


  }




  
}