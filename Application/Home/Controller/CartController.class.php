<?php
namespace Home\Controller;

class CartController extends CommonController {
	// 添加商品到购物车
    public function addCart()
    {
    	//1、接受商品ID
    	$goods_id = I('post.goods_id',0,'intval');
    	//2、接受商品数量
    	$goods_count = I('post.goods_count',0,'intval');
    	//3、购买商品的属性值ID组合
    	$goods_attr_id = I('post.attr','');
    	// dump($goods_attr_id);exit;

    	// 将goods_attr_id转换为字符串格式
    	if($goods_attr_id){
    		$goods_attr_id = implode(',',$goods_attr_id);
    	}
    	// 调用模型方法加入到购物车
    	D('Cart')->addCart($goods_id,$goods_count,$goods_attr_id);
    	echo 'ok';
    }
    // 购物车列表显示
    public function index()
    {
    	// 获取购物车中数据
    	$data = D('Cart')->getList();
    	$this->assign('data',$data);
    	// 调用模型方法计算总金额
    	$total = D('Cart')->getTotal($data);
    	$this->assign('total',$total);
    	$this->display();
    }

    // 删除购物车中的删除
    public function delCart()
    {
    	//1、接受商品ID
    	$goods_id = I('get.goods_id',0,'intval');
    	//3、购买商品的属性值ID组合
    	$goods_attr_id = I('get.goods_attr_id','');
    	D('Cart')->delCart($goods_id,$goods_attr_id);
    	$this->success('删除成功',U('index'));
    }
    
    // 清空购物车
    public function clearCart()
    {
    	D('Cart')->clearCart();
    	$this->success('清空成功',U('index'));
    }

    public function test2db()
    {
    	D('Cart')->cookie2db();
    }

    public function testcart()
    {
    	dump(cookie());
    }
}