<?php 
namespace Admin\Controller;

/**
* 登录控制器
*/
class LoginController extends CommonController
{
	public $is_check_login =false;//属性标识符，通过此值检查是否需要登录

	// 登录方法
	public function login()
	{
		if(IS_GET){
			$this->display();
			exit();
		}
		$adminModel = D('Admin');
		// 创建数据 针对用户表单的提交接受数据
		$data = $adminModel->create();
		if(!$data){
			$this->error($adminModel->getError());
		}
		// 调用模型下的login方法实现登录
		$res = $adminModel->login($data['username'],$data['password'],I('post.code'));
		if(!$res){
			$this->error($adminModel->getError());
		}
		$this->success('登录成功',U('Index/index'));
	}

	// 生成验证码
	public function verify()
	{
		$config = array('length'=>3);
		$obj = new \Think\Verify($config);
		$obj->entry();
	}

	// 实现退出功能
	public function logout()
	{
		// 清除cookie中保存的信息
		cookie('_user',null);
		$this->success('退出成功',U('Login/login'));
	}

	// 生成测试密码
	public function test()
	{
		// 如果有部分测试相关的代码 建议加上密钥
		if(I('get.key')=='abc'){
			echo md6('admin');
		}
	}
}


?>