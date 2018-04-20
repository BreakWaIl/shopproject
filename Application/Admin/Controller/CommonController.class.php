<?php 
namespace Admin\Controller;
use Think\Controller;
/**
* 公共控制器
*/
class CommonController extends Controller
{
	// 是否需要进行权限校验 对于超级管理员角色不校验
	public $is_check_rule = true ;
	// 保存用户的信息
	public $user = array();

	public $is_check_login = true;
	public function __construct()
	{
		// 执行父类的构造方法
		parent::__construct();
		// 对登录进行判断
		if($this->is_check_login){
			$user = cookie('_user');
	    	if(!$user){
	    		// 没有登录
	    		$this->error('没有登录！',U('Login/login'));
	    	}
	    	// 调用方法实现权限控制
	    	$this->auth();
		}
	}
	// 具体实现权限控制
	protected function auth()
	{
		// 先将用户信息保存到user属性中
		$user = cookie('_user'); 
		$this->user = S('admin_'.$user['id']);
		if(!$this->user){
			$this->user= $user;
			// 根据用户ID获取角色ID
			$role = D('AdminRole')->where('admin_id=%d',$this->user['id'])->find();
			if(!$role){
				$this->error('没有分配角色不能访问');
			}
			$this->user['role_id']=$role['role_id'];
			if($this->user['role_id']==1){
				// 为预留的超级管理员
				$this->is_check_rule = false;//设置不进行权限校验
				$rule_list = D('Rule')->select();
			}else{
				// 普通其他角色管理员
				// 先获取角色对应已拥有的权限ID
				$rule_ids = D('RoleRule')->getRules($this->user['role_id']);
				// 将获取到的权限id转换为字符串格式并且逗号隔开。因为需要使用mysql中in查询权限信息
				foreach ($rule_ids as $key => $value) {
					$rule_id[]=$value['rule_id'];
				}
				$rule_id = implode(',', $rule_id);
				// 根据权限ID获取详细的权限信息
				$rule_list = D('Rule')->where("id in ($rule_id)")->select();
			}
			// 将权限信息进行格式化
			foreach ($rule_list as $key => $value) {
				// 权限信息转换为一维数组
				$this->user['rule_list'][] =strtolower($value['module_name'].'/'.$value['controller_name'].'/'.$value['action_name']);
				// 格式化导航菜单 将显示的权限信息保存到menus元素中
				if($value['is_show']==1){
					$this->user['menus'][]=$value;
				}	
			}
			// 获取数据需要保存到缓存中
			S('admin_'.$user['id'],$this->user);
		}

		// 解决使用缓存之后超级管理员会进行权限校验的问题
		if($this->user['role_id']==1){
			$this->is_check_rule = false;
		}

		// 根据当前访问的进行判断
		// 获取当前访问的模块、控制器、操作名称
		if($this->is_check_rule){
			// 处理后台首页没有权限的问题
			$this->user['rule_list'][]='admin/index/index';
			$this->user['rule_list'][]='admin/index/top';
			$this->user['rule_list'][]='admin/index/menu';
			$this->user['rule_list'][]='admin/index/main';
			// 针对非超级管理员进行权限校验
			$action =strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
			if(!in_array($action,$this->user['rule_list'])){
				if(IS_AJAX){
					$this->ajaxReturn(array('status'=>0,'msg'=>'没有权限访问'));
				}else{
					$this->error('没有权限访问');
				}
			}
		}

	}
}
?>