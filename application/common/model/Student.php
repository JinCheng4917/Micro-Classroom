<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
use app\common\model\CourseList;
/**
 * student 学生表
 */
class Student extends Model
{

    public static $sessionidKey = 'id';
    protected $auto = ['name', 'username', 'openid', 'email','id','phone'];
    /*
     创建静态方法findByOpenId，若用户openid存在user表中，则返回非空，不存在则为空
    */
    public static function findByOpenId($openid)
    {
        return self::where('openid', $openid)->find();
    }

    //判断用户是否已经登录
    static public function isLogin()
    {
        $id = session('id');
        
        // isset()和is_null()是一对反义词
        if (isset($id)) {
            return true;
        } else {
            return false;
        }
    }

    static public function haveBound($name,$num)
    {
        return self::where(['name' => $name,
                            'num' => $num])
                            ->find();
    }

    /*
    利用session中储存的id获取user对象
    */
    public static function getSessionUser() {
        $id = session(self::$sessionidKey);
        if (is_null($id)) {
            return null;
        }  else {
            // 返回用id获取的user对象
            return self::get($id);
        }    
    }

    /*
    将用户id储存
    */
    public static function sessionId($id) {
        session(self::$sessionidKey, $id);
    }

    static public function courseList()
    {
        //获取当前登陆学生的id
        $id = session('id'); 
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
        $this->assign('course',$course);
         // 取回打包后的数据
        $htmls = $this->fetch();

        // 将数据返回给用户
        return $htmls;
    }



    


 

   

    //注销
    static public function logOut()
    {
        // 销毁session中数据
        session('id', null);
        return true;
    }



    protected $dateFormat = 'Y年m月d日';    // 日期格式

    /**
     * 输出性别的属性
     
     */
    public function getSexAttr($value)
    {
        $status = array('0'=>'男','1'=>'女');
        $sex = $status[$value];
        if (isset($sex))
        {
            return $sex;
        } else {
            return $status[0];
        }
    } 
     public function getCreateTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }

     public function Klass()
    {
        return $this->belongsTo('Klass');
    }
    public function Course()
    {
        return $this->belongsTo('Course');
    }
    public function College()
    {
        return $this->belongsTo('College');
    }
      public function Term()
    {
        return $this->belongsTo('Term');
    }
}
