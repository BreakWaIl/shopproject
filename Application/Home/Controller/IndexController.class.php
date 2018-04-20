<?php
namespace Home\Controller;

class IndexController extends CommonController {
    public function index()
    {   
    	$goodsModel = D('Goods');
    	// 获取新品
    	$this->news = $goodsModel->getRecGoods('is_new');
    	// 获取推荐
    	$this->recs = $goodsModel->getRecGoods('is_rec');
    	// 获取热卖
    	$this->hots = $goodsModel->getRecGoods('is_hot');
    	// 赋予一个标识表示出是否是首页
    	$this->assign('is_show',1);
    	$this->display();    
    }

    public function testU()
    {
    	echo U('index','id=2');
    }
}