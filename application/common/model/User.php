<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 * User 用户表
 */
class User extends Model
{
    public static $sessionUserIdKey = 'userid';
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
        $UserId = session('userid');
        
        // isset()和is_null()是一对反义词
        if (isset($UserId)) {
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
    利用session中储存的userid获取user对象
    */
    public static function getSessionUser() {
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
    public static function sessionUserId($userid) {
        session(self::$sessionUserIdKey, $userid);
    }




}
