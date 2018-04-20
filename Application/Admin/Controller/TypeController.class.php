<?php
namespace Admin\Controller;

class TypeController extends CommonController {
	// 实现添加与修改一步操作
	// 添加数据  get请求显示模板 post提交入库添加
	// 修改数据  get请求显示模板及原始数据  post提交入库修改
	public function add()
	{
		self::edit();
	}
	public function edit()
	{
		$model = D('Type');
		if(IS_GET){
			//添加显示模板或者修改时的显示模板及原始数据
			//type_id有值说明是修改 没有值说明是添加
			$type_id = I('get.type_id',0,'intval');
			if($type_id>=0){
				// 修改类型时
				$info = $model->where('id='.$type_id)->find();
				$this->assign('info',$info);
			}
			$this->display('edit');
		}else{
			$data = $model->create();
			if(!$data){
				$this->error($model->getError());
			}
			$type_id = I('post.type_id',0,'intval');
			if($type_id){
				// 修改
				$model->where('id='.$type_id)->save($data);
			}else{
				// 添加
				$model->add($data);
			}
			$this->success('ok');
		}
	}
	// 类型的列表
	public function index()
	{
		$model = D('Type');
		$data = $model->listData();
		$this->assign('data',$data);
		$this->display();
	}

	// 实现删除 单个删除为get方式 跟批量删除 为ajax方式
	public function dels()
	{
		// 单个删除时type_id为整形数字 批量删除为数组格式
		$type_id = I('request.type_id');

		$model = D('Type');

		$res = $model->dels($type_id);

		if($res == false){
			$error= $model->getError();
			if(IS_GET){
				// 单个删除
				$this->error($error);
			}else{
				// 批量删除
				$this->ajaxReturn(array('status'=>0,'msg'=>$error));
			}			
		}
		if(IS_GET){
			$this->success('ok');
		}
		$this->ajaxReturn(array('status'=>1,'msg'=>'OK'));
	}
}