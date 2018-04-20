<?php 
namespace Home\Model;
use Think\Model;
/**
* 商品模型
*/
class GoodsModel extends Model
{
	// 定义字段信息
	protected $fields=array(
		'id','goods_name','goods_sn','market_price','shop_price','goods_number','type_id','cate_id','goods_img','goods_thumb','goods_body','addtime','is_hot','is_new','is_rec','is_del'
	);

	// 获取推荐的商品
	public function getRecGoods($field)
	{
		$map = array($field=>1);
		return $this->where($map)->limit(5)->order('id desc')->select();
	}
}