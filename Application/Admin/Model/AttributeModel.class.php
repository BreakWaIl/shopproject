<?php 
namespace Admin\Model;
use Think\Model;

/**
* 属性模型
*/
class AttributeModel extends Model
{
	protected $fields=array('id','attr_name','type_id','attr_type','attr_input_type','attr_values');
	// 自动验证
	protected $_validate=array(
		array('attr_name','require','名称必须填写'),
		array('attr_type','1,2','属性类型不对',1,'in'),
	);


	public function listData()
	{
		$p = I('get.p');

		$pagesize = 10;

		$count = $this->count();

		$page = new \Think\Page($count,$pagesize);

		$pageStr = $page->show();

		$list = $this->alias('a')->join('left join shop_type b on a.type_id=b.id')->field('a.*,b.type_name')->page($p,$pagesize)->select();

		return array('pageStr'=>$pageStr,'list'=>$list);
	}

	public function dels($attr_id)
	{
		return $this->where('id='.$attr_id)->delete();
	}
}