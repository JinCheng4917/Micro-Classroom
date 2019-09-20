<?php
namespace app\admin\controller;
use app\admin\model\Student;
use app\admin\model\Klass;
use app\admin\model\Teacher;
use think\facade\Request;
use think\Controller; 

class StudentController extends Controller
{
   protected $batchValidate = true;
  public function index()
  {
    //获取查询信息
    $name = Request::instance()->get('name');

    //实例化Course
    $Student = new Student;
    //分页和查询
    $students= $Student->where('name','like','%'.$name.'%')->paginate(3, false, [
                'query'=>[
                    'name' => $name,            //切页后保留查询时的关键字
                    ],
                ]);
    $this->assign('students',$students);
    return $this->fetch();
  }

  public function add()
  {
    $klasses = Klass::all();
    $this->assign('klasses',$klasses);
    $teacheres = Teacher::all();
    $this->assign('teacheres',$teacheres); 
    $Student=new Student;
    $Student->id=0;
    $Student->name='';
    $Student->num='';
    $Student->sex= 0;
    $Student->klass_id=0;
    $Student->email='';
    $this->assign('Student',$Student);
    return $this->fetch('edit');
  }

  public function save()
  {
    //实例化
    $Student = new Student();
    //验证是否添加成功
    $result = $this->validate(
      [
        'name' => Request::instance()->post('name'),
        'num' => Request::instance()->post('num'),
        'sex' => Request::instance()->post('sex'),
        'klass_id' => Request::instance()->post('klass_id'),
        'email' => Request::instance()->post('email'),
      ],
      'app\admin\validate\Student.add');
    if (true !== $result) {
      dump($result);
    }else
    {
         // 接收传入数据
            $postData = Request::instance()->post();    
            // 实例化Teacher空对象
            $Student = new Student();
            // 为对象赋值
            $Student->name = $postData['name'];
            $Student->num = $postData['num'];
            $Student->sex = $postData['sex'];
            $Student->klass_id = $postData['klass_id'];
            $Student->email = $postData['email'];
            // 新增对象至数据表
            $saves = $Student->save();
        return $this->success('操作成功',url('index'));
}
  }

  public function edit()
  {
     $klasses = Klass::all();
    $this->assign('klasses',$klasses);
    $teacheres = Teacher::all();
    $this->assign('teacheres',$teacheres); 
    // $Student = new Student();
    
    $id = Request::instance()->param('id/d');
    $student = Student::get($id);

    $this->assign('Student',$student);
   return $this->fetch();
  }

  public function update()
  {
    $id = Request::instance()->post('id/d');
    //获取edit传入的学生信息
    //检查ID是否存在
    if (is_null($Student=Student::get($id))) {
      return $this->error('未找到ID为：'.$id.'的学生');
    }
    //更新数据
  if (!is_null($Student)){
    $result = $this->validate(
      [
        'name' => Request::instance()->post('name'),
        'sex' => Request::instance()->post('sex'),
        'klass_id' => Request::instance()->post('klass_id'),
        'email' => Request::instance()->post('email'), 
      ],
      'app\admin\validate\Student.edit');
    if ( true !== $result)
    {
      dump($result);
    }
    else{
       // 接收传入数据
            $postData = Request::instance()->post();    
           
            // 为对象赋值
            $Student->name = $postData['name'];
            $Student->num = $Student->num;
            $Student->sex = $postData['sex'];
            $Student->klass_id = $postData['klass_id'];
            $Student->email = $postData['email'];
            // 新增对象至数据表
            $Student->force()->save();
        return $this->success('操作成功',url('index'));
    }
  }
}
   

  public function delete()
  {
            // 获取要删除对象的id
            $id = Request::instance()->param('id/d');
            
            // 判断是否成功接收
            if(is_null($Student=Student::get($id))){
              return $this->error('不存在id为:'.$id.'的学生，删除失败');
            }
            // 删除对象
            if (!$Student->delete()) {
                return $this->error('删除失败:' . $Student->getError());
            }

        // 进行跳转 
        return $this->success('删除成功', url('index')); 
  }
  }
  

