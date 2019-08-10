<?php
namespace app\index\controller;

use think\Controller;
use think\facade\Request; //简单的事情重复做，5.1中Request方法被移到了facade目录下

use app\common\model\Teacher;



class TeacherController extends IndexController
{
	public function  index()
	{
		// 验证用户是否登录
		if (!Teacher::isLogin()) {
			return $this->error('plz login first',url('Login/index'));
		}

		// 获取查询信息
		$name = input('get.name');

		// 设置每页显示条数
		$pageSize = 5;

		// 实例化Teacher
		$Teacher = new Teacher;

		// 按条件查询数据并调用分页
		$Teachers = $Teacher->where('name','like','%'.$name.'%')->paginate($pageSize,false,['query'=>['name'=>$name,
							],
				]);

		// 向v层传递数据
		$this->assign('Teachers',$Teachers);

		// 取回打包后的数据
		$htmls = $this->fetch();

		// 将数据返回给用户
		return $htmls;

		// fetch
		return $this->fetch();

	}

	public function add()
	{
		// 实例化
		$Teacher = new Teacher;

		// 设置默认值
		$Teacher->id = 0;
		$Teacher->name = '';
		$Teacher->username = '';
		$Teacher->sex = 0;
		$Teacher->email = '';
		$this->assign('Teacher',$Teacher);

		// 调用edit模板
		return $this->fetch('edit');


	}

	public function save()
	{
		// 实例化
		$Teacher = new Teacher;

		// 新增数据
		if (!$this->saveTeacher($Teacher)) {
			return $this->error('save faild'.$Teacher->getError());
		}
		// 成功后跳转至index触发器
		return $this->success('save success', url('index'));

	}

	public function delete()
	{
		try {
			// 实例化请求
			$Request = Request::instance();

			// 获取get数据
			$id = Request::instance()->param('id/d');

			// 判断是否成功接收
			if (0 === $id) {
				throw new \Exception('未获取到ID信息',1);
			}
			// 获取要删除的对象
			$Teacher = Teacher::get($id);

			// 确认要删除的对象存在
			if (is_null($Teacher)) {
				throw new \Exception('不存在ID为'.$id.'的教师，删除失败',1); 
			}

			// 删除对象
			if (!$Teacher->delete()) {
				return $this->error('删除失败'.$Teahcer->getError());
			}


		// 获取到ThinkPHP的内置异常时,直接向上抛出,交给ThinkPHP处理
		} catch (\think\Exception\HttpResponseException $e) {
			throw $e;

		// 获取到正常的异常时,输出异常
		} catch (\Exception $e) {
			return $e->getMessage();
		}

		// 进行跳转
		return $this->success('删除成功',$Request->header('referer'));


	}


	public function edit()
	{
		try {
			// 获取传入ID
			$id = Request::instance()->param('id/d');

			// 在Teacher表模型中获取当前记录
			if (null === $Teacher = Teacher::get($id))
			{
				$this->error('系统为找到ID为'.$id.'的记录');
			}

			// 将数据传给v层
			$this->assign('Teacher',$Teacher);

			// 获取封装好的V层内容
			$htmls = $this->fetch();

			// 将封装好的v层内容返回给用户
			return $htmls;

		// 获取到ThinkPHP内置异常时，直接向上抛出，交给ThinkPHP处理
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
            if (!$this->saveTeacher($Teacher,true)) {
                return $this->error('操作失败' . $Teacher->getError());
            }
        } else {
            return $this->error('当前操作的记录不存在');
        }
    
        // 成功跳转至index触发器
        return $this->success('操作成功', url('index'));



	}

	private function saveTeacher(Teacher &$Teacher,$isUpdate = false)
	{
        // 写入要更新的数据
        $Teacher->name = Request::instance()->post('name');
        if (!$isUpdate) {
            $Teacher->username = Request::instance()->post('username');
        }
        $Teacher->sex = Request::instance()->post('sex/d');
        $Teacher->email = Request::instance()->post('email');

        // 更新或保存
        $this->validate($Teacher->getData(),'app\common\validate\Teacher');
        return $Teacher->save($Teacher->getData());


	}









}