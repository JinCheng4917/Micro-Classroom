<?php
namespace app\admin\controller;
use think\facade\Request;                  // 请求
use app\common\model\Klass; 
use app\common\model\Course;
use app\common\model\CourseList;  //课表
use app\common\model\KlassCourse;  //中间表
use app\common\model\Teacher;  // 教师
use app\common\model\Date;
use app\common\model\Time;
use app\common\model\Term;
use app\common\model\Week;
use app\common\model\Score;
use think\Controller;    
class KlassController extends Controller
{
    /*
    **获取班级信息并传输到主页
    */
    public function index()
    {
          // 获取查询信息
        $name = Request::instance()->get('name');
        // 实例化Teacher
        $Klass = new Klass; 
        trace($Klass, 'debug');
        // 按条件查询数据并调用分页
        $klasses = Klass::where('name', 'like', '%' . $name . '%')->paginate(50); 
        $this->assign('klasses', $klasses);
        return $this->fetch();
    }

    public function add()
    {

        // 获取所有的教师信息
        $teachers = Teacher::all();
        $terms = Term::all();
        $this->assign('teachers', $teachers);
         $this->assign('terms', $terms);
        //实例化
        $klass = new Klass;
        $klass->id = 0;
        $klass->term_id = 0;
        $klass->name = "";
        $klass->teacher_id = '';
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
                $Klass->term_id = $postData['term_id'];
                $Klass->save();
                return $this->success('操作成功',url('index'));
            }
        }
    }

    public function edit()
    {
       $terms = Term::all();
         $this->assign('terms', $terms);
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
                return $this->success('操作成功');
            }
        }
    }
    public function delete()
    {
            // 获取要删除对象的id
        $id = Request::instance()->param('id/d');
        
            // 判断是否成功接收
        if(is_null($Klass=Klass::get($id))){
          return $this->error('不存在id为:'.$id.'的课程，删除失败');
      }
            // 删除对象
      if (!$Klass->delete()) {
        return $this->error('删除失败:' . $Klass->getError());
    }
    $Score = Score::where("klass_id",$id);
  $Score->delete();
        // 进行跳转 
    return $this->success('删除成功'); 
}
/*
**
 */
public function courseList()
{
    //获取当前班级的id
    $id = Request::instance()->param('id/d');
    //获得相关课程的所有信息
    $klasses = Klass::get($id);
    //从课表中找到对应班级的所有信息
    for ($m=1; $m < 12; $m++) { 
        //筛选出每周一对应时段的课程id
        $oneCourseListId[$m]=array_unique (CourseList::where('klass_id',$id)->where('date_id',1)->where('time_id',$m)->column('course_id'));
        //筛选出每周一对应时段的周次id
        $oneCourseListWeek[$m]=array_unique (CourseList::where('klass_id',$id)->where('date_id',1)->where('time_id',$m)->    order('id')->column('week_id'));
        $twoCourseListId[$m] = array_unique (CourseList::where('klass_id',$id)->where('date_id',2)->where('time_id',$m)->column('course_id'));
        $twoCourseListWeek[$m]=array_unique (CourseList::where('klass_id',$id)->where('date_id',2)->where('time_id',$m)->    order('id')->column('week_id'));
        $threeCourseListId[$m]=array_unique (CourseList::where('klass_id',$id)->where('date_id',3)->where('time_id',$m)->column('course_id'));
        $threeCourseListWeek[$m]=array_unique (CourseList::where('klass_id',$id)->where('date_id',3)->where('time_id',$m)->    order('id')->column('week_id'));
        $fourCourseListId[$m] = array_unique (CourseList::where('klass_id',$id)->where('date_id',4)->where('time_id',$m)->column('course_id'));
        $fourCourseListWeek[$m]=array_unique (CourseList::where('klass_id',$id)->where('date_id',4)->where('time_id',$m)->    order('id')->column('week_id'));
        $fiveCourseListId[$m]=array_unique (CourseList::where('klass_id',$id)->where('date_id',5)->where('time_id',$m)->column('course_id'));
        $fiveCourseListWeek[$m]=array_unique (CourseList::where('klass_id',$id)->where('date_id',5)->where('time_id',$m)->    order('id')->column('week_id'));
        $sixCourseListId[$m] = array_unique (CourseList::where('klass_id',$id)->where('date_id',6)->where('time_id',$m)->column('course_id'));
        $sixCourseListWeek[$m]=array_unique (CourseList::where('klass_id',$id)->where('date_id',6)->where('time_id',$m)->    order('id')->column('week_id'));
        $sevenCourseListId[$m] = array_unique (CourseList::where('klass_id',$id)->where('date_id',7)->where('time_id',$m)->column('course_id'));
        $sevenCourseListWeek[$m]=array_unique (CourseList::where('klass_id',$id)->where('date_id',7)->where('time_id',$m)->    order('id')->column('week_id'));
    }
    //判断相关信息是否为空，不为空便赋值
    for ($n=1; $n < 12; $n++) { 
         if(!empty($oneCourseListId[$n])){
            $oneCourse[$n] = Course::get( $oneCourseListId[$n][0]); 
          }
         else{
          $oneCourse[$n] = null;  
         }
         if(!empty($twoCourseListId[$n])){
        $twoCourse[$n] = Course::get( $twoCourseListId[$n][0]); 
         }
         else{
          $twoCourse[$n] = null;  
         }
         if(!empty($threeCourseListId[$n])){
         $threeCourse[$n] = Course::get( $threeCourseListId[$n][0]); 
         }
         else{
           $threeCourse[$n] = null;  
         }
         if(!empty($fourCourseListId[$n])){
             $fourCourse[$n] = Course::get( $fourCourseListId[$n][0]); 
         }
         else{
           $fourCourse[$n] = null;  
         }
         if(!empty($fiveCourseListId[$n])){
             $fiveCourse[$n] = Course::get( $fiveCourseListId[$n][0]); 
         }
         else{
         $fiveCourse[$n] = null;  
         }
         if(!empty($sixCourseListId[$n])){
             $sixCourse[$n] = Course::get( $sixCourseListId[$n][0]); 
         }
         else{
         $sixCourse[$n] = null;  
         }
         if(!empty($sevenCourseListId[$n])){
             $sevenCourse[$n] = Course::get( $sevenCourseListId[$n][0]); 
         }
         else{
           $sevenCourse[$n] = null;  
         }
         }
$this->assign('oneCourse',$oneCourse);
$this->assign('oneCourseListWeek',$oneCourseListWeek);
$this->assign('twoCourse',$twoCourse);
$this->assign('twoCourseListWeek',$twoCourseListWeek);
$this->assign('threeCourse',$threeCourse);
$this->assign('threeCourseListWeek',$threeCourseListWeek);
$this->assign('fourCourse',$fourCourse);
$this->assign('fourCourseListWeek',$fourCourseListWeek);
$this->assign('fiveCourse',$fiveCourse);
$this->assign('fiveCourseListWeek',$fiveCourseListWeek);
$this->assign('sixCourse',$sixCourse);
$this->assign('sixCourseListWeek',$sixCourseListWeek);
$this->assign('sevenCourse',$sevenCourse);
$this->assign('sevenCourseListWeek',$sevenCourseListWeek);
$this->assign('id',$id);
$this->assign('klasses',$klasses);
return $this->fetch();
}

/*
** 录入课程信息
 */
public function addCourse()
{ 
    //获取到本班级的id
    $id = Request::instance()->param('klass_id');
    //获取到星期几
    $date = Request::instance()->param('date_id');
    //获取到节次
    $time = Request::instance()->param('time_id');
    //从星期几的表单中获取信息，以获得name
    $Date = Date::where('id',$date)->find();
    //从节次的表单中获取信息，以获得name
    $Time = Time::where('id',$time)->find();
    //获取到本班级的班级信息
    $klass = Klass::get($id);
    //从中间表中获取到相关班级的所有课程
    $courseId = KlassCourse::where('klass_id',$id)->select();
    $weekId = CourseList::where('klass_id',$id)->where('date_id',$date)->where('time_id',$time)->column('week_id');
    $CourseId = CourseList::where('klass_id',$id)->where('date_id',$date)->where('time_id',$time)->column('course_id');
    if(isset($CourseId))
    {
        $CourseId[0] = 1;
    }
    //获取所有周次,以便进行循环
    $week = Week::all();
    $this->assign('id',$id);
    $this->assign('CourseId',$CourseId);
    $this->assign('weekId',$weekId);
    $this->assign('klass',$klass);
    $this->assign('Date',$Date);
    $this->assign('Time',$Time);
    $this->assign('course',$courseId);
    $this->assign('week',$week);
    return $this->fetch();
}
/*
** 接收课程信息并保存
 */
public function courseSave()
{
    $data = Request::instance()->post();
  // 实例化请求信息
    $Request = Request::instance();

        // 实例化班级并赋值
    $courseList = new CourseList();
    $courseList->klass_id = $klass_id = $Request->post('klass_id');
    $courseList->course_id = $course_id = $Request->post('course_id');
    $weeks = $Request->post('week');
    $courseList->date_id = $date_id = $Request->post('date_id');
    $courseList->time_id = $time_id = $Request->post('time_id');
        // 添加数据
    if (!is_null($courseList)){
        $result = $this->validate(
            [
                'klass_id' => Request::instance()->post('klass_id'),
                'course_id' => Request::instance()->post('course_id'),
                'week_id' => Request::instance()->post('week'),
                'date_id' => Request::instance()->post('date_id'),
                'time_id' => Request::instance()->post('time_id'),
            ],
            'app\admin\validate\Klass.courseSave');
        if ( true !== $result)
        {
            dump($result);
        }
        if (false === $courseList->where('klass_id',$klass_id)->where('date_id',$date_id)->where('time_id',$time_id)->delete()) {
            return $this->error('删除班级课程关联信息发生错误' . $Course->KlassCourses()->getError());
        }
        else {
            for ($i=0; $i < count($weeks); $i++) { 


                $postData = Request::instance()->post();
            //实例化Klass空对象
                $CourseList = new CourseList;
            //为对象赋值
                $CourseList->klass_id =  $klass_id;
                $CourseList->course_id = $course_id;
                $CourseList->date_id = $date_id;
                $CourseList->time_id = $time_id;
                $CourseList->week_id = $weeks[$i];
                if(!$CourseList->save()){
                   return $this->error('操作失败',url('addCourse'));
               } 
           }
       }
   }
   return $this->success('操作成功',url('courseList?id=' . $courseList->klass_id));
}
}
