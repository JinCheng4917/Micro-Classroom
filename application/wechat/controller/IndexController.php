<?php
namespace app\wechat\controller;
use app\service\Wechat;//引入我们刚才新建在service下的wechat类
use think\Controller;
use think\facade\Request;
use think\Db;
use app\common\model\Student;
use app\wechat\validate;
use EasyWeChat\Factory;  //使用easywechat类
use app\common\model\Klass;
use app\common\model\Term;
use app\common\model\College;

class IndexController extends Controller
{
   public function index()
   {
        $openid = session('openid');
        $this->assign('openid',$openid);
        return $this->fetch();
   }

    /**
     * 微信按钮跳转授权
     */
    // 微信授权跳转，想获取用户信息的时候就调用这个方法
    public function weChatAccredit()
    {
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/Micro-Classroom/public/wechat/index/getWeChatInfo';

        $we_chat = new Wechat(); //实例化类
        $we_chat->accredit($url); //调用方法 
    }
    /**
     * 获取微信用户信息
     */

    public function getWeChatInfo()
    {
        $we_chat = new Wechat();//实例化微信类
        $code = Request::instance()->get('code');  //获取跳转后的code
        if (is_null($code)) {

            $this->error('code为空，请重新点击按钮','http://'.$_SERVER['HTTP_HOST'].'/Micro-Classroom/public/wechat/index/weChatAccredit');
        } else {
            $access_token = $we_chat->getAccessToken($code); //根据code获取特殊token
            // 需要注意的是，这里的access_token返回的是一个数组，里面有openid和access_token等项

            //根据access_token和openid获取到用户信息
            // $weChatStudentInfo = $we_chat->getWeChatUserInfo($access_token['access_token'],$access_token['openid']);

            // $openid = $weChatStudentInfo['openid'];
            $openid = $access_token['openid'];
            session('openid', $openid);
            $this->searchStudent($openid);
                 
            // 其实并不用把openid传过去，只要把这里获取的openid和表单传值获取的openid传回来
        }
        

    }

    /**
     *  判断用户openid是否存在Student表中
     */   
    public function searchStudent($openid)
    {
        // 在Student表中查找是否存在该openid

            // 若不存在，返回true则跳转至绑定界面，让用户输入信息，提交后绑定至Student表中
            // 代码重构后，这里直接先把不存在的openid存入Student表，其余信息使用update方法
        if (is_null(Student::findByopenid($openid))) {
            $Student = $this->insert($openid);

        }
            // 若存在，则让用户直接登录（需要先绑定个人信息）
            $Student = Student::findByopenid($openid);
            
            // 直接获取用户id，存session，登录
            session('studentId',$Student->id);

            $Student = Student::getSessionStudent();
            if (is_null($Student['num'])|is_null($Student->name)) {

                return $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/Micro-Classroom/public/wechat/index/edit');

            }
            else {
                return $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/Micro-Classroom/public/index/student/index');
            }
    }

    /**
     *  微信新用户绑定个人信息
     */   


    public function insert($openid)
    {
      
        // 实例化Student对象
        $Student = new Student();

        // 在用户未绑定信息前先将openid存入Student表
        $Student->openid = $openid;

        // 创建空值
        $Student->id = 0;
        $Student->name = '';
        $Student->phone = '';
        $Student->email = '';
        $Student->num = Null;


            $Student->save();
            session('studentId', $Student->id);
 
    }

    /*
    编辑个人信息
    */
    public function edit()
    {
        $Student = Student::getSessionStudent();

        $Klass = Klass::all();
        $klasses = [];
          for ($i=0; $i < count($Klass); $i++) { 
            $map['id'] = $Klass[$i]->id;
            $klasses[$i] = Klass::get($map);

            }         

        $Term = Term::all();
        $terms = [];
          for ($i=0; $i < count($Term); $i++) { 
                $map['id'] = $Term[$i]->id;
                $terms[$i] = Term::get($map);

            }         

        $College = College::all();
        $colleges = [];
          for ($i=0; $i < count($College); $i++) { 
            $map['id'] = $College[$i]->id;
            $colleges[$i] = College::get($map);

            }  
        $this->assign('Student',$Student);
        $this->assign('klasses',$klasses);
        $this->assign('terms',$terms);
        $this->assign('colleges',$colleges);




        return $this->fetch();
    }

    /*
    更新个人信息（edit调用）
    */
    public function update()
    {
        
        // 利用静态方法获取Student对象
        $Student = Student::getSessionStudent();

        // 写入要更新的数据
        $Student->name = Request::instance()->post('name');
        // $Student->username = Request::instance()->post('username');
        $Student->num = Request::instance()->post('num');
        $Student->email = Request::instance()->post('email');
        $Student->phone = Request::instance()->post('phone');
        $Student->major = Request::instance()->post('major');
        $Student->klass_id = Request::instance()->post('klass_id');
        $Student->term_id = Request::instance()->post('term_id');
        $Student->college_id = Request::instance()->post('college_id');

  
                

                $result = $this->validate(
            [
                'name'    =>  $Student->name,
                'num'     =>  $Student->num,
                'klass_id'     =>  $Student->klass_id,
                'term_id'     =>  $Student->term_id,
                'college_id'     =>  $Student->college_id,
            ],
            'app\wechat\validate\User');
        if (true !== $result) {
            // 验证失败 输出错误信息
            return $this->error($result);
        } else {
            // 保存对象（注意，这是必须的）
            $Student->save();
            // 重定向
             $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/Micro-Classroom/public/index/student/index');
        }
        

    }




}