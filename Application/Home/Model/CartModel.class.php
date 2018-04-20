<?php 
namespace Home\Model;
use Think\Model;

/**
* 
*/
class CartModel extends Model
{
	public $user_id;//保存用户的id值
	protected $fields=array('id','user_id','goods_id','goods_attr_id','goods_count');
	public function __construct()
	{
		parent::__construct();
		// 获取用户是否登录  登录之后需要将用户ID保存到user_id属性中
		// 1、获取用户信息
		$user = cookie('userinfo'); 
		if($user){
			// 说明用户已经登录 对用户的ID进行解密
			$this->user_id = think_decrypt($user['id']);
		}
	}

	// 实现商品添加到购物车中
	public function addCart($goods_id,$goods_count,$goods_attr_id)
	{
		// 1、判断是否登录
		// 2、如果已经登录
		//  先判断购物车中是否存在对应的商品信息如果存在增加数量如果不存在直接写入
		// 3、如果没有登录
		// 先判断cookie中是否存在对应的商品信息如果存在增加数量如果不存在直接写入cookie
		if($this->user_id){
			// 用户已经登录
			// 组装条件到数据库中查询商品信息是否存在
			$map = array(
				'goods_id'=>$goods_id,
				'goods_attr_id'=>$goods_attr_id,
				'user_id'=>$this->user_id
			);
			$res = $this->where($map)->find();
			if($res){
				// 已经存在增加数量
				// setInc将查询到的数据对应的值进行增加 第一个参数为字段名称 第二个参数不填表示加1 填写增加固定的值
				$this->where($map)->setInc('goods_count',$goods_count);
			}else{
				// 写入数据
				$map['goods_count']=$goods_count;
				$this->add($map);
			}
		}else{
			// 用户没有登录
			// 获取cookie中已有的数据信息
			$cart = cookie('cart');
			// 判断添加的商品是否存
			// 拼接key的名称
			$key = $goods_id.'-'.$goods_attr_id;
			if(array_key_exists($key, $cart)){
				// 判断成立说明商品数据在cookie中已经存在
				$cart[$key]+=$goods_count;
			}else{
				// 说明商品数据在cookie中不存在
				$cart[$key]=$goods_count;
			}
			// 再次写入cookie
			cookie('cart',$cart);
		}
	}

	// 获取购物车中数据
	public function getList()
	{
		// 1、获取购物车中数据
		if($this->user_id){
			// 已经登录
			$data = $this->where('user_id=%d',$this->user_id)->select();
		}else{
			// 没有登录
			$cart = cookie('cart');
			// 对数据进行格式化 确保获取购物车中数据对于登录与未登录数据格式保持一致
			foreach ($cart as $key => $value) {
				// 使用-特殊符号对key进行分割
				$tmp = explode('-',$key);
				$data[]=array(
					'goods_id'=>$tmp[0],
					'goods_attr_id'=>$tmp[1],
					'goods_count'=>$value
				);
			}
		}
		foreach ($data as $key => $value) {
			// 2、获取商品信息
			// 获取商品信息 保持到$data 变量中
			$data[$key]['info']=D('Goods')->where('id=%d',$value['goods_id'])->find();
			// 3、获取属性信息包括属性名称跟属性值
			$data[$key]['attr']=D('Goods')->query("SELECT a.attr_value,b.attr_name FROM shop_goods_attr a LEFT JOIN shop_attribute b ON a.attr_id=b.id WHERE a.id in ({$value['goods_attr_id']})");
		}
		return $data;		
	}

	// 获取总金额
	public function getTotal($data)
	{
		// $count 为总商品件数 $sum为总金额
		$count = $sum = 0;
		foreach ($data as $key => $value) {
			// 总数量
			$count += $value['goods_count'];
			// 总金额
			$sum += $value['goods_count']*$value['info']['shop_price']; 
		}
		return array('count'=>$count,'sum'=>$sum);
	}

	// 实现删除购物车中某一个商品
	public function delCart($goods_id,$goods_attr_id)
	{
		if($this->user_id){
			$map=array(
				'user_id'=>$this->user_id,
				'goods_id'=>$goods_id,
				'goods_attr_id'=>$goods_attr_id,
			);
			$this->where($map)->delete();
		}else{
			$cart = cookie('cart');
			$key = $goods_id.'-'.$goods_attr_id;
			// 删除$key指定的元素 删除元素等价于删除购物车中商品数据
			unset($cart[$key]);
			cookie('cart',$cart);
		}
	}

	// 清空购物车
	public function clearCart()
	{
		if($this->user_id){
			$this->where('user_id=%d',$this->user_id)->delete();
		}else{
			cookie('cart',null);
		}
	}
	// 实现将cookie购物车数据转移到数据库下
	public function cookie2db()
	{
		// 此操作必须要求用户已经登录
		if(!$this->user_id){
			return false;
		}
		// 1、cookie中数据获取回来
		$cart = cookie('cart');
		// 2、加入到数据库中
		foreach ($cart as $key => $value) {
			$tmp = explode('-',$key);
			$map = array(
				'goods_id'=>$tmp[0],
				'goods_attr_id'=>$tmp[1],
				'user_id'=>$this->user_id,
			);
			if($this->where($map)->find()){
				// 数据库中存在商品跟属性值ID组合相同的信息
				// 需要增加数量
				$this->where($map)->setInc('goods_count',$value);
			}else{
				$map['goods_count']=$value;
				$this->add($map);
			}
		}
		// 清空cookie中记录的购物车
		cookie('cart',null);
	}
}
?>