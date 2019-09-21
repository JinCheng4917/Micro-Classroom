<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 * Teacher 教师表
 */
class Student extends Model
{
	protected $auto = ['name', 'num', 'ip', 'email'];
    
    /**
     * 用户登录
    **/
    static public function login($num, $password)
    {
        // 验证用户是否存在
        $stu = array('num' => $num);
        $Student = self::get($stu);
        
        if (!is_null($Student)) {
            // 验证密码是否正确
            if ($Student->checkPassword($password)) {
                // 登录
                session('studentId', $Student->getData('id'));
                return true;
            }
        }

        return false;
    }

    /**
     * 验证密码是否正确
     * @param  string $password 密码
     * @return bool           
     */
    public function checkPassword($password)
    {
        if ($this->getData('password') === $this::encryptPassword($password))
        {
            return true;
        } else {
            return false;
        }
    }

    
    static public function encryptPassword($password)
    {   
        if (!is_string($password)) {
            throw new \Exception("传入变量类型非字符串，错误码2", 2);
        }

        
        return sha1(md5($password) . 'mengyunzhi');
    }

    //注销
    static public function logOut()
    {
        // 销毁session中数据
        session('studentId', null);
        return true;
    }

  //判断用户是否已经登录
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
