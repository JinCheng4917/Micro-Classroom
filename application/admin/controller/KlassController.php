<?php
namespace app\admin\controller;
use think\facade\Request;                  // 请求
use app\admin\model\Klass;         // 班级
use app\admin\model\Teacher;  // 教师
 use think\Controller;    
class KlassController extends Controller
{
    public function index()
    {
        $klasses = Klass::paginate(5);
        $this->assign('klasses', $klasses);
        return $this->fetch();
    }
    
    public function add()
    {

        // 获取所有的教师信息
        $teachers = Teacher::all();
       $this->assign('teachers', $teachers);
        
        //实例化
        $klass = new Klass;
        $klass-> id = 0;
        $klass-> name = "";
        $klass-> teacher_id = '';
        $this->assign('klass',$klass);
        return $this->fetch('edit');
       }

    public function save()
    {
        // 实例化请求信息
        $Request = Request::instance();

        // 实例化班级并赋值
        $Klass = new Klass();
        $Klass->name = $Request->post('name');
        $Klass->teacher_id = $Request->post('teacher_id/d');

        // 添加数据
       if (!is_null($Klass)){
        $result = $this->validate(
            [
                'name' => Request::instance()->post('name'),
                'teacher_id' => Request::instance()->post('teacher_id'),
            ],
            'app\admin\validate\Klass.add');
        if ( true !== $result)
        {
            dump($result);
        }
        else {
            $postData = Request::instance()->post();
            //实例化Klass空对象
            $Klass = new Klass;
            //为对象赋值
            $Klass->name = $postData['name'];
            $Klass->teacher_id = $postData['teacher_id'];
            $Klass->save();
            return $this->success('操作成功',url('index'));
        }
       }
    }

    public function edit()
    {
        $id = Request::instance()->param('id/d');
        $klass = Klass::get($id);
        // 获取所有的教师信息
        $teachers = Teacher::all();
        $this->assign('teachers', $teachers);

       
        $this->assign('klass', $klass);
        return $this->fetch();
    }

    public function update()
    {
        $id = Request::instance()->post('id/d');

        // 获取传入的班级信息
        $Klass = Klass::get($id);
        if (is_null($Klass)) {
            return $this->error('系统未找到ID为' . $id . '的记录');
        }

        // 数据更新
          if (!is_null($Klass)){
        $result = $this->validate(
            [
                'teacher_id' => Request::instance()->post('teacher_id'),
            ],
            'app\admin\validate\Klass.edit');
        if ( true !== $result)
        {
            dump($result);
        }
        else {
            $postData = Request::instance()->post();
            //为对象赋值
            $Klass->name = $Klass->name;
            $Klass->teacher_id = $postData['teacher_id'];
            $Klass->save();
            return $this->success('操作成功',url('index'));
        }
    }
}
    }