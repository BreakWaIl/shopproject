<?php
namespace Admin\Controller;

class AttributeController extends CommonController {

	// 属性的入库
	public function add()
	{
		if(IS_GET){
			// 获取到已有的类型信息
			$type = D('Type')->select();
			$this->assign('type',$type);
			$this->display();
		}else{
			$model = D('Attribute');
			$data = $model->create();
			if(!$data){
				$this->error($model->getError());
			}
			$model->add($data);
			$this->success('ok');
		}
	}

	public function index()
	{
		$model = D('Attribute');
		$data = $model->listData();
		$this->assign('data',$data);
		$this->display();
	}


	public function dels()
	{

		$attr_id = I('request.attr_id',0,'intval');

		$model = D('Attribute');

		$res = $model->dels($attr_id);

		if($res == false){
			
			$error= $model->getError();

			$this->error($error);	
		}

		$this->success('ok');

	}

	public function edit()
	{
		$model = D('Attribute');
		if(IS_GET){			
			$attr_id = I('get.attr_id',0,'intval');
			$info = $model->where('id='.$attr_id)->find();
			$this->assign('info',$info);
			$type = D('Type')->select();
			$this->assign('type',$type);
			$this->display();
		}else{
			$attr_id = I('post.attr_id',0,'intval');
			$data = $model->create();
			if(!$data){
				$this->error($model->getError());
			}
			$res = $model->where(array('id'=>$attr_id))->save($data);
			if($res === false){
				$this->error($model->getError());
			}
			$this->success('ok');
		}
	}
}