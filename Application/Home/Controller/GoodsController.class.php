<?php
namespace Home\Controller;

class GoodsController extends CommonController {
    public function index()
    {
    	$goods_id = I('get.goods_id',0,'intval');
    	if($goods_id<=0){
    		$this->redirect('Index/index');
    	}
    	
    	$goods_info = http_curl('http://api.com/Goods/getGoodsBase',array('goods_id'=>$goods_id));
    	if(!$goods_info){
    		$this->redirect('Index/index');
    	}
    	// 反转详细信息
    	$goods_info['goods_body'] = htmlspecialchars_decode($goods_info['goods_body']);
    	$this->assign('goods_info',$goods_info);
        
    	// 获取商品的相册
    	$img = M('GoodsImg')->where('goods_id=%d',$goods_id)->select();
    	$this->assign('img',$img);
    	$attr = D('GoodsAttr')->alias('a')->field('a.*,b.attr_name,b.attr_type')->join('LEFT JOIN shop_attribute b ON a.attr_id = b.id')->where('a.goods_id=%d',$goods_id)->select();
    	foreach ($attr as $key => $value) {
    		if($value['attr_type']==1){
    			// 唯一属性
    			$unique[]=$value; 
    		}else{
    			// 表示为单选属性 将单选属性转换为三维数组将相同属性的属性值存储到一个元素下
    			$sigle[$value['attr_id']][]=$value;
    			
    		}
    	}
    	// dump($sigle);
    	$this->assign('sigle',$sigle);
    	$this->assign('unique',$unique);
    	$this->display();    
    }
}