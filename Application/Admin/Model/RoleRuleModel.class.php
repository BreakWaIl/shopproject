<?php 
namespace Admin\Model;
use Think\Model;

/**
* 角色权限模型
*/
class RoleRuleModel extends Model
{
	// 实现分配权限
	public function disfetch($role_id,$rule)
	{
		// 将数据转换为写入数据库的格式
		foreach ($rule as $key => $value) {
			$list[]=array(
				'role_id'=>$role_id,
				'rule_id'=>$value
			);
		}
		// 先将数据进行删除
		$this->where('role_id=%d',$role_id)->delete();
		// 在写入最新的权限
		$this->addAll($list);
	}

	// 根据角色获取权限的id
	public function getRules($role_id)
	{
		return $this->where('role_id=%d',$role_id)->select();	
	}
}