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
    $openIdList = $app->user->list($nextOpenId = null); 
    dump($openIdList['data']['openid'][1]);
    dump($openIdList['data']['openid'][2]);
    dump($openIdList['data']['openid'][0]);


    //当前日期
    $date = date('Y-m-d');
    
        //获取当前登陆学生的id
        // $id = session('studentId'); 
        $id = 136;
        //获取课表的所有信息
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
        $day    = date('w',strtotime($date));
        
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
        // dump($course);
       // dump($course['0']['course_id']);
        // dump($course);
      $course1 = Course::where('id','4')->select();
      dump($course1[0]['name']);

      $course_name = $course1[0]['name'];
      $course_room = $course1[0]['room'];

      // 获取1970年距今的s数
      $times =time();

      //将s数转换成时分秒格式
      $time = date('H:i:s', $times);

      


    $template_id = $template["template_list"]['1']['template_id'];
    if (is_null($template_id)) {
      return $this->error('template_id is null,please add template');
    } else {
      $app->template_message->send([
        // 'touser' => 'oTOWXwt0Y-8ALy_d6sejRixNQZJ0',  //小号2
        'touser' => 'oTOWXwoq0e2L6Aa8SZt2zoafONak',  //大号
        // 'touser' => 'oTOWXwtWpe-Iv_DTbmD5qLd7kKAU',     //文达
                      
        'template_id' => $template_id,
        'data' => [
            'date' => $date,
            'klassroom' => $course_room,
            'time' => $time,
            'name' => $course_name
           
        ],
      ]);
      }
    
    return 'success';
  }


  
}