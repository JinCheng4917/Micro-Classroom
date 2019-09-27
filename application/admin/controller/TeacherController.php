<?php
namespace app\admin\controller;
use app\common\model\Teacher;  // 教师模型
use app\common\model\TeacherCourse;
use app\common\model\CourseList;
use app\common\model\Klass;
use app\common\model\Term;
use think\facade\Request;   
use think\Controller;   // 请求
/**
 * 教师管理，继承think\Controller后，就可以利用V层对数据进行打包了。
 */
class TeacherController extends Controller
{
 protected $batchValidate = true;
 public function index()
 {
        // 获取查询信息
    $name = Request::instance()->get('name');
        // 实例化Teacher
    $Teacher = new Teacher; 
    trace($Teacher, 'debug');
        // 按条件查询数据并调用分页
    $teachers = Teacher::where('name', 'like', '%' . $name . '%')->paginate(50); 
        // 向V层传数据
    $this->assign('teachers', $teachers);
        // 取回打包后的数据
    $htmls = $this->fetch();
        // 将数据返回给用户
    return $htmls;
}
public function add()
{
    $terms = Term::all();
    $klass = Klass::all();
        // 实例化
    $Teacher = new Teacher;
        // 设置默认值
    $Teacher->id = 0;
    $Teacher->name = '';
    $Teacher->term_id = 0;
    $Teacher->username = '';
    $Teacher->sex = 0;
    $Teacher->email = '';
    $this->assign('terms',$terms);
    $this->assign('klass',$klass);
    $this->assign('Teacher', $Teacher);
        // 调用edit模板
    return $this->fetch('edit');
}
public function delete()
{
    try {
            // 获取get数据
        $Request = Request::instance();
        $id = Request::instance()->param('id/d');
        
            // 判断是否成功接收
        if (is_null($id) || 0 === $id) {
            throw new \Exception('未获取到ID信息', 1);
        }
            // 获取要删除的对象
        $Teacher = Teacher::get($id);
            // 要删除的对象存在
        if (is_null($Teacher)) {
            throw new \Exception('不存在id为' . $id . '的教师，删除失败', 1);
        }
            // 删除对象
        if (!$Teacher->delete()) {
            return $this->error('删除失败:' . $Teacher->getError());
        }
        // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
    } catch (\think\Exception\HttpResponseException $e) {
        throw $e;
        // 获取到正常的异常时，输出异常
    } catch (\Exception $e) {
        return $e->getMessage();
    } 
        // 进行跳转
    return $this->success('删除成功', $Request->header('referer')); 
}
public function edit()
{

    try {
         $terms = Term::all();
        $klass = Klass::all();
            // 获取传入ID
        $id = Request::instance()->param('id/d');
            // 在Teacher表模型中获取当前记录
        if (null === $Teacher = Teacher::get($id))
        {
                // 由于在$this->error抛出了异常，所以也可以省略return(不推荐)
            $this->error('系统未找到ID为' . $id . '的记录');
        } 

            // 将数据传给V层
        $this->assign('klass',$klass);
        $this->assign('terms',$terms);
        $this->assign('Teacher', $Teacher);
            // 获取封装好的V层内容
        $htmls = $this->fetch();
            // 将封装好的V层内容返回给用户
        return $htmls;
        // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
    } catch (\think\Exception\HttpResponseException $e) {
        throw $e;
        // 获取到正常的异常时，输出异常
    } catch (\Exception $e) {
        return $e->getMessage();
    } 
}
public function update()
{
   
        // 接收数据，取要更新的关键字信息
    $id = Request::instance()->post('id/d');
        // 获取当前对象
    $Teacher = Teacher::get($id);
    if (!is_null($Teacher)) {
        $result = $this->validate(
          [
             'name' => Request::instance()->post('name'),
             'sex' => Request::instance()->post('sex'),
             'email' => Request::instance()->post('email'),
         ],
         'app\admin\validate\Teacher.edit');
        if ( true !== $result) 
        {
            dump($result);
        } 
        
        else {
            // 接收传入数据
            $postData = Request::instance()->post();    
            // 为对象赋值
            $Teacher->name = $postData['name'];
            $Teacher->username = $Teacher->username;
            $Teacher->sex = $postData['sex'];
            $Teacher->email = $postData['email'];
            $Teacher->force()->save();
            // 成功跳转至index触发器
            $map = ['teacher_id'=>$id];
            $klassIds = Request::instance()->post('Klass_id/a'); 
            if (false === $Teacher->TeacherKlasses()->where($map)->delete()) {
                return $this->error('删除教师课程关联信息发生错误' . $Teacher->TeacherKlasses()->getError());
            }
            if (!is_null($klassIds)) {
                if (!$Teacher->Klasses()->saveAll($klassIds)) {
                    return $this->error('课程-班级信息保存错误：' . $Teacher->Klasses()->getError());
                }
            } 
            unset($TeacherKlass);
            return $this->success('操作成功', url('index'));
        }
        
    }
}

public function save()
{
        // 实例化
    $Teacher = new Teacher;
    $result = $this->validate(
      [
        'name' => Request::instance()->post('name'),
        'username' => Request::instance()->post('username'),
        'sex' => Request::instance()->post('sex'),
        'email' => Request::instance()->post('email'),
    ],
    'app\admin\validate\Teacher.add');
    if ( true !== $result) 
    {
        dump($result);
        return $this->error('保存教师错误');
    } 
    else {
             // 接收传入数据
        $postData = Request::instance()->post();    
            // 实例化Teacher空对象
        $Teacher = new Teacher();
            // 为对象赋值
        $Teacher->name = $postData['name'];
        $Teacher->username = $postData['username'];
        $Teacher->sex = $postData['sex'];
        $Teacher->email = $postData['email'];
        $Teacher->term_id = $postData['term_id'];
            // 新增对象至数据表
        $Teacher->save();
        $klassIds = Request::instance()->post('Klass_id/a'); 
        dump($klassIds); 
        if (!is_null($klassIds)) {
            if (!$Teacher->Klasses()->saveAll($klassIds)) {
                return $this->error('课程-班级信息保存错误：' . $Teacher->Klasses()->getError());
            }
        }
        return $this->success('操作成功', url('index'));
        
    }
} 
}        