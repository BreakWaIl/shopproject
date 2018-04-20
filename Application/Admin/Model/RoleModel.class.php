<?php 
namespace Admin\Model;
use Think\Model;

/**
* 角色模型
*/
class RoleModel extends Model
{
	protected $fields=array('id','role_name');

	protected $_validate=array(
		array('role_name','','角色名称不能重复',1,'unique',1)
	);

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

	public function dels($role_id)
	{
		return $this->where('id='.$role_id)->delete();
	}
}