<?php 
namespace Home\Model;
use Think\Model;

/**
* 订单模型
*/
class OrderModel extends Model
{
	// 实现下单
	public function order()
	{
		// 1、获取购买的商品信息
		$cartModel = D('Cart');
		$goodsList = $cartModel->getList();
		// 2、检查库存 备注 目前还存在bug 需要配合货品一起检查库存
		foreach ($goodsList as $key => $value) {
			if($value['info']['goods_number']<$value['goods_count']){
				$this->error='库存不够';
				return false;
			}
		}
		//计算总价格
		$total = $cartModel->getTotal($goodsList);
		// 3、写入订单总表
		$data = $this->create();
		// 获取用户的ID
		$user = cookie('userinfo');
		$user_id = think_decrypt($user['id']);
		$data['user_id']=$user_id;
		$data['total_price']=$total['sum'];
		$data['addtime']=time();
		$order_id = $this->add($data);
		// 4、写入订单详情表
		foreach ($goodsList as $key => $value) {
			$orderDetail[]=array(
				'order_id'=>$order_id,
				'goods_id'=>$value['goods_id'],
				'goods_attr_id'=>$value['goods_attr_id'],
				'goods_count'=>$value['goods_count']
			);
		}
		D('OrderDetail')->addAll($orderDetail);
		// 5、清空购物车
		// $cartModel ->clearCart();
		// 6、减少库存
		foreach ($goodsList as $key => $value) {
			D('Goods')->where("id=%d",$value['goods_id'])->setDec('goods_number',$value['goods_count']);
		}
		$data['id']=$order_id;
		return $data;
	}
}