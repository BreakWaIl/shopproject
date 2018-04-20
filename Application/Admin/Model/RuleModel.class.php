<?php 
namespace  Admin\Model;
use Think\Model;
/**
* 权限模型
*/
class RuleModel extends Model
{
	// 获取已经格式化的分类信息
	public function getTree($id=0)
	{
		// 大于0表示要获取子权限
		if($id>0){
			// 1、查询数据获取所有的数据
			$data = $this->select();
			// 2、需要将数据格式化  通过调用自定义的公共函数实现
			$list = get_cate_tree($data,$id);
			return $list;
		}else{
			$rules = S('rules');
			if(!$rules){
				// 1、查询数据获取所有的数据
				$data = $this->select();
				// 2、需要将数据格式化  通过调用自定义的公共函数实现
				$list = get_cate_tree($data,$id);
				// 循环将数据转换为id作为下标
				foreach ($list as $key => $value) {
					$rules[$value['id']]=$value;
				}
				S('rules',$rules);
			}
			return $rules;
		}		
	}
	// 实现分类的删除功能
	public function dels($rule_id)
	{
		// 判断当前是否有子分类
        // 任何一个分类的parent_id值等于当前要删除的分类的id标识 表示有子分类
        $hasSon = $this->where('parent_id=%d',$rule_id)->find();
        if($hasSon){
            $this->error='有子权限不能删除';
            return false;
        }
        return $this->where(array('id'=>$rule_id))->delete();
	}

	public function update($data)
	{
		// 接受要修改的权限id
		$rule_id = I('post.rule_id',0,'intval');
		// 判断 当前修改的分类。对于上级该分类的设置不能为当前已有的子分类
        // 当前提交的parent_id值不能与当前分类下的子分类的任何一个id相等
        // 获取当前分类下的子分类
        $tree = $this->getTree($rule_id);
        // 排除自己作为自己的父权限
        $tree[]=array('id'=>$rule_id);

        foreach ($tree as $key => $value) {
        	if($data['parent_id']==$value['id']){
        		$this->error='明明是爹非要做儿子';
        		return false;
        	}
        }
        return $this->where('id='.$rule_id)->save($data);
	}

}

?>