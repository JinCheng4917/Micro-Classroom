<?php
namespace app\index\controller;
use app\common\model\Klass;
use app\common\model\Teacher;
use think\facade\Request;
use think\Controller;

class KlassController extends Controller
{
	public function index()
	{
		//获取要查询的信息

		// 实例化班级

		// 设置每页条数

		// 按条件查询数据并调用分页

		// 将数据传给v层

		// 取回打包后的数据

		// 将数据返还给用户

		// 猜测：返回打包后的数据？


	}

	public function add()
	{
		// 实例化该类的对象
		Klass = new Klass;

		//设置默认值
		$Klass->id = 0;
		$Klass->name = '';
		$Klass->teacher_id = 1;

		// 获取全体老师
		$teachers = Teacher::all();

		// 传入v层
		$this->assign('teachers',$teachers);
		$this->assign('Klass',$Klass);

		// 调用edit模板
		return $this->fetch('edit');
	}

	public function edit()
	{
		// 请求获取ID
		$id = Request::instance()->param('id/d');

		// 获取所有的教师信息
		$teachers = Teacher::all();
		$this->assign('teachers',$teachers);

		// 获取用户操作的班级信息
		if(false === $Klass = Klass::get($id))
		{
			return $this->error('系统未找到ID为'.$id.'的记录');
		}

		$this->assign('Klass',$Klass);
		return $this->fetch();
	}

	public function save()
	{
		// 实例化请求信息

		// 实例化班级并赋值
		$Klass = new Klass;


		// 新增数据
		if(!$this->saveKlass(Klass)){
			return $this->error('操作失败'.$Klass->getError());
		}

		// 成功跳转至index触发器
		return $this->success('操作成功',url('index'));
	}

	private function saveKlass(Klass &$Klass, $isUpdate = false)
	{
		//写入要更新的数据
		$Klass->teacher_id = Request::instance()->post('teacher_id');

		if(!$isUpdate){
			$Klass->name = Request::instance()->post('name');
		}

		// 更新或保存
		return $Klass->validate(true)->save();	

	}


	public function update()
	{
		//接收数据，去要更新的关键字信息
		$id = Request::instance()->post('id/d');

		// 获取当前对象
		$Klass = Klass::get($id);

		if(!is_null($Klass)){
			if(!$this->saveKlass($Klass,true)){
				return $this->error('操作失败'.$Klass->getError());
			}
		} else {
			return $this->error('当前操作记录不存在');
		}

		// 成功跳转至index触发器
		return $this->error('当前操作记录不存在');

	}

	public function delete()
	{
		// 获取从表单传入的id值
		$id = Request::instance()->param('id/d');

		if(is_null($id)||0 === $id){
			return $this->error('未获取到ID信息');
		}

		// 获取要删除的对象
		$Klass = Klass::get($id);

		if(!$Klass->delete()){
			return $this->error('删除失败'.$Klass->getError());
		}
		return $this->success('删除成功',url('index'));
	}

}