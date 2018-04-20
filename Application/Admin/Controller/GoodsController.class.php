<?php
namespace Admin\Controller;

class GoodsController extends CommonController {

	// 实现根据type_id获取对应的属性信息
	public function showAttr()
	{
		$type_id = I('request.type_id',0,'intval');
		// 获取到类型下的属性信息
		$list = D('Attribute')->where('type_id='.$type_id)->select();
		if(!$list){
			echo '没有数据';exit;
		}
		foreach ($list as $key => $value) {
			if($value['attr_input_type']==2){
				// 列表选择 有默认值 将默认值转换为数组格式
				$list[$key]['attr_values']= explode(',', $value['attr_values']);
			}
		}
		$this->assign('list',$list);
		$this->display();
	}

	// 实现商品的添加功能
	public function add()
	{
		if(IS_GET){
			// 获取已有的类型信息
			$type = D('Type')->select();
			$this->assign('type',$type);
			// 获取已有的分类信息供用户进行选择
			$tree = D('Category')->getTree();
			$this->assign('tree',$tree);
			$this->display();
			exit;
		}

		$model = D('Goods');
		$data = $model->create();
		if(!$data){
			$this->error($model->getError());
		}
		//数据的写入
		$goods_id = $model->add($data);
		if($goods_id === false){
			$this->error($model->getError());
		}
		$this->success('添加成功');
	}

	// 商品图片异步上传
	public function upload()
	{
		if($_FILES['file'] && $_FILES['file']['size']!=0){
			// 实现文件上传
			// 可以控制只能上传图片，控制后缀
			$upload = new \Think\Upload();
			// uploadOne只上传一个文件 需要指定参数 参数$_FILES[名称]  单文件上传返回一维数组
			$info = $upload->uploadOne($_FILES['file']);
			if(!$info){
				// 文件上传失败
				$this->ajaxReturn(array('status'=>0,'msg'=>$upload->getError()));
			}
			// 组装文件上传的地址
			$goods_img='Uploads/'.$info['savepath'].$info['savename'];
			// 生成缩略图
			// 实例化图片处理的对象
			$obj = new \Think\Image();
			// 打开图片
			$obj->open($goods_img);
			// 组装保存地址
			$goods_thumb='Uploads/'.$info['savepath'].'thumb_'.$info['savename'];
			$obj->thumb(200,200)->save($goods_thumb);
			$this->ajaxReturn(array('status'=>1,'goods_img'=>$goods_img,'goods_thumb'=>$goods_thumb));
		}
	}

	// 显示商品列表
	public function index()
	{
		$model=D('Goods');
		// 获取所有的分类信息
		$tree = D('Category')->getTree();
		$this->assign('tree',$tree);
		// 使用模型对象调用模型类下的自定义方法获取数据
		$data = $model->listData($tree);
		$this->assign('data',$data);
		$this->display();
	}

	// 实现列表状态的点击切换
	public function setStatus()
	{
		$goods_id = I('request.goods_id',0,'intval');
		$field = I('request.field','');

		// 检查容许修改的字段信息
		$allow = array('is_del','is_hot','is_rec','is_new');
		if(!in_array($field,$allow)){
			$this->ajaxReturn(array('status'=>0,'msg'=>'fail'));
		}
		//获取商品表中的数据
		$model=D('Goods');
		$goodsInfo = $model->where(array('id'=>$goods_id))->find();
		if(!$goodsInfo){
			$this->ajaxReturn(array('status'=>0,'msg'=>'fail'));
		}
		// 计算需要修改的值
		if($goodsInfo[$field]){
			$status=0;
		}else{
			$status =1;
		}
		// setField修改单个字段的内容 $field为要修改的字段名称 $status为要修改的值
		$model->where(array('id'=>$goods_id))->setField($field,$status);
		$this->ajaxReturn(array('status'=>1,'ztm'=>$status));
	}

	// 商品删除或者还原商品
	public function dels()
	{
		$goods_id = I('get.goods_id',0,'intval');
		$is_del = I('get.is_del',0,'intval');

		$model = D('Goods');
		$model->where('id='.$goods_id)->setField('is_del',$is_del);
		$this->success('ok');
	}


	// 商品回收站列表显示
	public function recycle()
	{
		$model=D('Goods');
		// 获取所有的分类信息
		$tree = D('Category')->getTree();
		$this->assign('tree',$tree);
		// 使用模型对象调用模型类下的自定义方法获取数据
		$data = $model->listData($tree,0);
		$this->assign('data',$data);
		$this->display();	
	}

	// 彻底删除
	public function remove()
	{
		$model = D('Goods');
		$res = $model->remove();
		if($res===false){
			$this->error($model->getError());
		}
		$this->success('ok');
	}

	// 商品编辑功能
	public function edit()
	{
		$model = D('Goods');
		if(IS_GET){
			// 获取分类数据
			$tree = D('Category')->getTree();
			$this->assign('tree',$tree);
			// 获取商品的原始信息
			$goods_id = I('get.goods_id',0,'intval');
			$goodsInfo = $model->where('id='.$goods_id)->find();
			// 将商品详情进行转换
			$goodsInfo['goods_body']=htmlspecialchars_decode($goodsInfo['goods_body']);
			$this->assign('info',$goodsInfo);
			// 获取当前商品已经拥有的属性信息
			$attr = M('GoodsAttr')->alias('a')->field('a.*,b.attr_name,b.attr_input_type,b.attr_type,b.attr_values')->join('left join shop_attribute b on a.attr_id=b.id')->where('a.goods_id='.$goods_id)->select();
			foreach ($attr as $key => $value) {
				if($value['attr_input_type']==2){
					$value['attr_values']=explode(',',$value['attr_values']);
				}
				// 转换成为使用属性id作为下标的数组 组合到一起之后就可以知道属性成套关系
				$attr_list[$value['attr_id']][]=$value;
			}

			$this->assign('attr',$attr_list);
			// 获取已有的类型信息
			$type = D('Type')->select();
			$this->assign('type',$type);
			// 获取商品相册信息
			$goods_img_list = M('GoodsImg')->where('goods_id='.$goods_id)->select();
			dump($goods_img_list);
			$this->assign('goods_img_list',$goods_img_list);
			$this->display();
		}else{
			// 入库修改
			$res = $model ->update();
			if($res === false){
				$this->error($model->getError());
			}
			$this->success('ok');
		}
	}
	// 删除相册图片
	public function delImg()
	{
		$goods_img_id = I('request.goods_img_id');
		M('GoodsImg')->delete($goods_img_id);
		$this->ajaxReturn(array('status'=>'1'));
	}

	// 测试文件上传
	public function test()
	{
		if(IS_GET){
			$this->display();
			exit;
		}
		// 实例化文件上传的类
		$config=array(
			'exts'=>array('jpg'),//设置容许上传的文件后缀
		);
		$upload = new \Think\Upload($config);
		// 使用对象调用上传方法
		$info = $upload->upload();
		dump($info);
		if(!$info){
			header('content-type:text/html;charset=utf8');
			echo $upload->getError();exit;
		}
		echo 'ok';
	}
}