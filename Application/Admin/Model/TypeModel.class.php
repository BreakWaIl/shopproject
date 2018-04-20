<?php 
namespace Admin\Model;
use Think\Model;

/**
* 类型模型
*/
class TypeModel extends Model
{
	protected $field=array('id','type_name');
	protected $_validate=array(
		array('type_name','checkName','名称错误',1,'callback')
	);
	public function checkName($type_name)
	{
		if(!$type_name){
			return false;
		}
		if($this->where(array('type_name'=>$type_name))->find()){
			return false;
		}
		return true;
	}

	public function listData()
	{
		$p = I('get.p');

		$pagesize = 10;

		$count = $this->count();

		$page = new \Think\Page($count,$pagesize);

		$pageStr = $page->show();

		$list = $this->page($p,$pagesize)->select();

		return array('pageStr'=>$pageStr,'list'=>$list);
	}

	public function dels($type_id)
	{
		// 根据type_id的不同组装条件
		if(is_array($type_id)){
			$map=array('id'=>array('in',$type_id));
		}else{
			$map=array('id'=>$type_id);
		}
		
		return $this->where($map)->delete();
	}
}