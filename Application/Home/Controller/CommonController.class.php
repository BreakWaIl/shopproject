<?php 
namespace Home\Controller;
use Think\Controller;

/**
* 公共控制器
*/
class CommonController extends Controller
{
	
	public function __construct()
	{
		parent::__construct();
		// 获取分类信息
		// 直接实例化后台中的模型对象
    	$tree = D('Admin/Category')->getTree();
    	$this->assign('tree',$tree);
    	$this->user  = cookie('userinfo');
	}

	// 校验用户是否登录
	public function checkLogin($bak='')
	{
		if(!$bak){
			$bak= U('User/login');
		}
		$user = cookie('userinfo'); 
		if(!$user){
			// echo $bak;exit();
			$this->error('没有登录',$bak);
		}
		// 说明用户已经登录 对用户的ID进行解密
		$user_id = think_decrypt($user['id']);
		if(!$user_id){
			$this->error('没有登录',$bak);
		}
	}
}

?>