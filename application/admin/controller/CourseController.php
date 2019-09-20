<?php
namespace app\admin\controller;
use app\admin\model\Course;            // 课程
use app\admin\model\Klass;
use app\admin\model\Teacher;             // 班级
use app\admin\model\KlassCourse;
use think\Controller;       // 班级课程
use think\facade\Request;
/**
 * 课程管理
 */
class CourseController extends Controller
{
    public function index()
    { 
        $klasses = Klass::all();
         $this->assign('klasses',$klasses);
         $teachers = Teacher::all();
         $this->assign('teachers',$teachers);
        $courses = Course::paginate(5);
        $this->assign('courses', $courses);
        return $this->fetch();

    }

    public function add()
    {
        $klasses = Klass::all();
       //实例化
        $course = new Course;
        //设置默认值
        
         $course->id = 0;
         $course->name = '';
        $this->assign('course', $course);
        $this->assign('klasses',$klasses);
        
        // 调用edit模板
        return $this->fetch('edit');
    }

    public function save()
    {
        // 存课程信息
        $Course = new Course();
        $Course->name = Request::instance()->post('name');
      if (!is_null($Course))
      {
        $result = $this->validate(
            [
                'name' => Request::instance()->post('name'),
                'klass_id' => Request::instance()->post('klass_id/a'),
            ],
            'app\admin\validate\Course.add');
        if (true !== $result)
        {
            dump($result);
        }
        else {

           $Course = new Course();
           $Course->name = Request::instance()->post('name');

        // -------------------------- 新增班级课程信息 -------------------------- 
        // 接收klass_id这个数组
        $klassIds = Request::instance()->post('klass_id/a');       // /a表示获取的类型为数组
        $Course->save();


        //保存中间表
        if (!is_null($klassIds)) {
            if (!$Course->Klasses()->saveAll($klassIds)) {
                return $this->error('课程-班级信息保存错误：' . $Course->Klasses()->getError());
            }
        }
        // -------------------------- 新增班级课程信息(end) -------------------------- 
        
     
        return $this->success('操作成功', url('index'));
    }
 }
}

    public function edit()
    {
       $klasses = Klass::all();

        $id = Request::instance()->param('id/d');
        $course = Course::get($id);
        if (is_null($course)) {
            return $this->error('不存在ID为' . $id . '的记录');
        }
        $this->assign('klasses',$klasses);
        $this->assign('course', $course);
        return $this->fetch();
    }

    public function update()
    { 
        $id = Request::instance()->post('id/d');
        $Course = Course::get($id);
        // 获取当前课程
        if (is_null($Course)) {
            return $this->error('不存在ID为' . $id . '的记录');
        }

        // 更新课程名
        $Course->name = Request::instance()->post('name');
        if (!is_null($Course)){
            $result = $this->validate(
                [
        'name' => Request::instance()->post('name'),
        'klass_id' => Request::instance()->post('klass_id/a'),    
          ],
          'app\admin\validate\Course.edit');
            if (true !== $result)
            {
                dump($result);
            }
            else
            {
           $Course->name = $Course->name;
        // 删除原有信息
        $map = ['course_id'=>$id];

        // 执行删除操作。由于可能存在 成功删除0条记录，故使用false来进行判断，而不能使用
        // if (!KlassCourse::where($map)->delete()) {
        // 我们认为，删除0条记录，也是成功
        if (false === $Course->KlassCourses()->where($map)->delete()) {
            return $this->error('删除班级课程关联信息发生错误' . $Course->KlassCourses()->getError());
        }

        // 增加新增数据，执行添加操作。
        $klassIds = Request::instance()->post('klass_id/a');
        if (!is_null($klassIds)) {
            if (!$Course->Klasses()->saveAll($klassIds)) {
                return $this->error('课程-班级信息保存错误：' . $Course->Klasses()->getError());
            }
        }

        return $this->success('更新成功', url('index'));
    }

}   
}
 public function delete()
  {
            // 获取要删除对象的id
            $id = Request::instance()->param('id/d');
            
            // 判断是否成功接收
            if(is_null($Course= Course::get($id))){
              return $this->error('不存在id为:'.$id.'的课程，删除失败');
            }
            // 删除对象
            if (!$Course->delete()) {
                return $this->error('删除失败:' . $Course->getError());
            }

        // 进行跳转 
        return $this->success('删除成功', url('index')); 
  }
        }