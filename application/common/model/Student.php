<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 * Teacher 教师表
 */
class Student extends Model
{
	public static $sessionUserIdKey = 'studentId';
    protected $auto = ['name', 'num', 'ip', 'email','username','email','id','phone'];
    

     // 创建静态方法findByOpenId，若用户openid存在user表中，则返回非空，不存在则为空
   
    public static function findByOpenId($openid)
    {
        return self::where('openid', $openid)->find();
    }

    /**
     * 用户登录
    **/
    static public function isLogin()
    {
        $studentId = session('studentId');
        
        // isset()和is_null()是一对反义词
        if (isset($studentId)) {
            return true;
        } else {
            return false;
        }
    }  



   /*
    利用session中储存的userid获取user对象
    */
    public static function getSessionStudent() {
        $id = session(self::$sessionUserIdKey);
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
    public static function sessionUserId($studentId) {
        session(self::$sessionUserIdKey, $studentId);
    }
    

    
   
    //注销
    static public function logOut()
    {
        // 销毁session中数据
        session('studentId', null);
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
