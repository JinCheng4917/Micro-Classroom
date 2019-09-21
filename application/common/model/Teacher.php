<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 * Teacher 教师表
 */
class Teacher extends Model
{
    protected $auto = ['name', 'username', 'ip', 'email'];
    
    /**
     * 用户登录
    **/
    static public function login($username, $password)
    {
        // 验证用户是否存在
        $map = array('username' => $username);
        $Teacher = self::get($map);
        
        if (!is_null($Teacher)) {
            // 验证密码是否正确
            if ($Teacher->checkPassword($password)) {
                // 登录
                session('teacherId', $Teacher->getData('id'));
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
        session('teacherId', null);
        return true;
    }

  //判断用户是否已经登录
    static public function isLogin()
    {
        $teacherId = session('teacherId');

        // isset()和is_null()是一对反义词
        if (isset($teacherId)) {
            return true;
        } else {
            return false;
        }
    }
      public function TeacherKlasses()
    {
        return $this->hasMany('TeacherKlass');
    }
    public function getIsChecked(Klass &$Klass)
    {
        // 取课程ID
        $teacherId = (int)$this->id;
        $klassId = (int)$Klass->id;

        // 定制查询条件
        $map = array();
        $map['klass_id'] = $klassId;
        $map['teacher_id'] = $teacherId;

        // 从关联表中取信息
        $TeacherKlass = TeacherKlass::get($map);
        if (is_null($TeacherKlass)) {
            return false;
        } else {
            return true;
        }
    }
     public function Courses()
    {
        return $this->belongsToMany('Course', 'teacher_course');
    }
    public function Klasses()
    {
        return $this->belongsToMany('Klass', 'teacher_klass');
    }
    public function Klass()
    {
        return $this->belongsTo('Klass');
    }
     public function Course()
    {
        return $this->hasMany('Course');
    }
     public function Student()
    {
        return $this->belongsTo('Student');
    }
     public function Term()
    {
        return $this->belongsTo('Term');
    }
}