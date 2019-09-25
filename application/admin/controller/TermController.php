<?php
namespace app\admin\controller;
use think\facade\Request;                  // 请求
use app\common\model\Term; 
use app\common\model\Score;
 use think\Controller;    
class TermController extends Controller
{
    public function index()
{  // 获取查询信息
        $name = Request::instance()->get('name');
        // 实例化Teacher
        $Term = new Term; 
        trace($Term, 'debug');
        // 按条件查询数据并调用分页
        $term = Term::where('name', 'like', '%' . $name . '%')->paginate(50);
        $this->assign('term', $term);
        return $this->fetch();
    }
    
    public function add()
    {
        
        //实例化
        $Term = new Term;
        $Term-> id = 0;
        $Term-> name = "";
        $this->assign('Term',$Term);
        return $this->fetch('edit');
       }

    public function save()
    {
        // 实例化请求信息
        $Request = Request::instance();

        // 实例化班级并赋值
        $term = new Term();
        $term->name = $Request->post('name');

        // 添加数据
       if (!is_null($term)){
        $result = $this->validate(
            [
                'name' => Request::instance()->post('name'),
            ],
            'app\admin\validate\Term.add');
        if ( true !== $result)
        {
            dump($result);
        }
        else {
            $postData = Request::instance()->post();
            //实例化Klass空对象
            $Term = new Term;
            //为对象赋值
            $Term->name = $postData['name'];
        
            $Term->save();
            return $this->success('操作成功',url('index'));
        }
       }
    }

 public function delete()
  {
            // 获取要删除对象的id
            $id = Request::instance()->param('id/d');
            
            // 判断是否成功接收
            if(is_null($Term=Term::get($id))){
              return $this->error('不存在id为:'.$id.'的学期，删除失败');
            }
            // 删除对象
            if (!$Term->delete()) {
                return $this->error('删除失败:' . $Term->getError());
            }
     $Score = Score::where("term_id",$id);
  $Score->delete();
        // 进行跳转 
        return $this->success('删除成功', url('index')); 
  }
    }