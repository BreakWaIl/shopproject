<?php
namespace Admin\Controller;

class RoleController extends CommonController {
	public $_model;

	public function model()
	{
		if(!$this->_model){
			$this->_model=D('Role');
		}
		return $this->_model;
	}

	public function add()
	{
		if(IS_GET){
			$this->display();
		}else{
			$data = $this->model()->create();
			if(!$data){
				$this->error($this->model()->getError());
			}
			$this->model()->add($data);
			$this->success('ok');
		}
	}

	public function index()
	{
		$data = $this->model()->listData();
		$this->assign('data',$data);
		$this->display();
	}

	public function dels()
	{
		$role_id = I('get.role_id',0,'intval');
		if($role_id<=1){
			// 限制超级管理不容许删除
			$this->error('参数错误');
		}
		$this->model()->dels($role_id);
		$this->success('ok');
	}

	public function edit()
	{
		if(IS_GET){
			$role_id = I('get.role_id',0,'intval');
			$info = $this->model()->where('id='.$role_id)->find();
			$this->assign('info',$info);		
			$this->display();
		}else{
			$data = $this->model()->create();
			if(!$data){
				$this->error($this->model()->getError());
			}
			$role_id = I('post.role_id',0,'intval');
		
			$this->model()->where('id='.$role_id)->save($data);

			$this->success('ok');
		}
	}

	// 分配权限
	public function disfetch()
	{
		if(IS_GET){
			// 获取当前角色拥有的权限信息
			$role_id = I('get.role_id',0,'intval');
			$hasRules = D('RoleRule')->getRules($role_id);
			// 将已经拥有的权限id保存到数组中
			foreach ($hasRules as $key => $value) {
				$hasRulesIds[]=$value['rule_id'];
			}
			$hasRulesIds = implode(',', $hasRulesIds);
			$this->assign('hasRules',$hasRulesIds);

			// 获取所有的权限信息
			$rules = D('Rule')->getTree();
			$this->assign('rules',$rules);

			$this->display();exit();
		}
		// 实现权限提交入库
		// 接受提交的角色ID以及权限id信息
		$role_id = I('post.role_id',0,'intval');
		$rule = I('post.rule');
		D('RoleRule')->disfetch($role_id,$rule);
		$this->success('ok');
	}

	public function test()
	{
		$rules = D('Rule')->select();
		foreach ($rules as $key => $value) {
			$list[]=$value['module_name'].$value['controller_name'].$value['action_name'];
		}
		dump($list);
	}

}
?>