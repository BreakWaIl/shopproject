<?php
namespace Admin\Controller;

class CategoryController extends CommonController {
    // 分类的添加
    public function add()
    {
    	// 实例化模型对象
    	$model = D('Category');
    	if(IS_GET){
    		// 通过模型对象调用自定义方法获取已经格式化的分类信息
    		$data = $model ->getTree();
    		$this->assign('data',$data);
    		$this->display();
    		exit(); 
    	} 
    	
    	// 组装要入库的数据
    	$data = array(
    		'cname'=>I('post.cname',''),
    		'parent_id'=>I('post.parent_id',0,'intval')
    	);
    	// 数据入库
    	$insert_id = $model ->add($data);
    	if(!$insert_id){
    		$this->error('error');
    	}
        // 更新缓存中的分类数据
        S('category',null);
    	$this->success('ok');  
    }

    //分类的列表显示
    public function index()
    {
        $model = D('Category');
        // 获取格式化之后的分类信息
        $data = $model->getTree();
        $this->assign('data',$data);
        $this->display();
    } 

    // 分类的删除
    public function dels()
    {
        // 接受传递的参数
        $id = I('get.id',0,'intval');
        if($id<=0){
            $this->error('参数错误');
        }
        $model = D('Category');
        $res = $model ->dels($id);
        if($res === false){
            $this->error('删除失败');
        }
        // 更新缓存中的分类数据
        S('category',null);
        $this->success('删除成功');        
    }

    public function edit()
    {
        $model = D('Category');
        if(IS_GET){
            $id = intval(I('get.id',0));
            // 获取原始的分类信息
            $info = $model->where('id='.$id)->find();
            $this->assign('info',$info);
            // 获取已有的分类信息 方便修改时可以选择上级分类
            $data = $model ->getTree();
            $this->assign('data',$data);
            $this->display();
            exit();
        }
        // 实现表单的提交修改
        $data = $model->create();
        $res = $model ->update($data);
        if($res === false){
            $this->error($model->getError());
        }
        // 更新缓存中的分类数据
        S('category',null);
        $this->success('success');
        
    }
}