<?php
namespace Admin\Controller;

class AdminController extends CommonController {

	public function add()
	{
		if(IS_GET){
			// 获取已有的角色信息
			$role = D('Role')->select();
			$this->assign('role',$role);
			$this->display();
		}else{
			$model = D('Admin');
			$result = $model->insert(I('post.username'),I('post.password'),I('post.role_id'));
			if($result === false){
				$this->error($model->getError());
			}
			$this->success('ok');
		}
	}

	public function index()
	{
		$model = D('Admin');
		$data = $model->listData();
		$this->assign('data',$data);
		$this->display();
	}

	public function dels()
	{
		$admin_id = I('get.admin_id',0,'intval');
		if($admin_id<=1){
			$this->error('参数错误');
		}
		D('Admin')->dels($admin_id);
		$this->success('ok');
	}

	public function edit()
	{
		$model = D('Admin');
		if(IS_GET){
			// 获取用户信息
			$admin_id = I('get.admin_id',0,'intval');
			$info = $model ->getUserRole($admin_id);
			$this->assign('info',$info);
			// 获取所有的角色信息
			$role = D('Role')->select();
			$this->assign('role',$role);
			$this->display();exit();
		}
		// 实现入库修改
		$admin_id = I('post.admin_id',0,'intval');
		if($admin_id<=1){
			$this->error('参数错误');
		}
		$model->update(I('post.username'),I('post.password'),I('post.role_id'),$admin_id);
		$this->success('ok');
	}
}