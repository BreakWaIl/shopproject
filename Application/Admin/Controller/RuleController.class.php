<?php
namespace Admin\Controller;

class RuleController extends CommonController {
    // 分类的添加
    public function add()
    {
    	// 实例化模型对象
    	$model = D('Rule');
    	if(IS_GET){
    		// 通过模型对象调用自定义方法获取已经格式化的分类信息
    		$data = $model ->getTree();
    		$this->assign('data',$data);
    		$this->display();
    		exit(); 
    	} 
    	$data = $model->create();
    	// 数据入库
    	$insert_id = $model ->add($data);
    	if(!$insert_id){
    		$this->error('error');
    	}
        // 更新缓存中的分类数据
        S('rules',null);
    	$this->success('ok');  
    }

    //分类的列表显示
    public function index()
    {
        $model = D('Rule');
        // 获取格式化之后的分类信息
        $data = $model->getTree();
        $this->assign('data',$data);
        $this->display();
    } 

    // 分类的删除
    public function dels()
    {
        // 接受传递的参数
        $rule_id = I('get.rule_id',0,'intval');
        if($rule_id<=0){
            $this->error('参数错误');
        }
        $model = D('Rule');
        $res = $model ->dels($rule_id);
        if($res === false){
            $this->error('删除失败');
        }
        // 更新缓存中的分类数据
        S('rules',null);
        $this->success('删除成功');        
    }

    public function edit()
    {
        $model = D('Rule');
        if(IS_GET){
            $rule_id = intval(I('get.rule_id',0));
            // 获取原始的权限信息
            $info = $model->where('id='.$rule_id)->find();
            $this->assign('info',$info);
            // 获取已有的权限信息 方便修改时可以选择上级分类
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
        S('rules',null);
        $this->success('success');
        
    }
}