<?php 
namespace Home\Model;
use Think\Model;
/**
* 商品模型
*/
class UserModel extends Model
{
	// 定义字段信息
	protected $fields=array('id','username','password','tel','email','status','active_code','openid');
	// qq登录
	public function qqLogin($user,$openid)
	{
		// 1、根据openid查询用户是否存在
		$userinfo = $this->where("openid='%s'",$openid)->find();
		// 2、存在 直接登录 
		// 3、不存在 写入数据库在登录
		if(!$userinfo){
			$userinfo=array(
				'username'=>'qquser_'.$user['nickname'].'_'.rand(100,999),
				'password'=>md5('123456'),
				'status'=>1,
				'openid'=>$openid
			);
			$userinfo['id']=$this->add($userinfo);
		}
		// 针对用户ID进行加密
		$userinfo['id'] = think_encrypt($userinfo['id']);
		cookie('userinfo',$userinfo);
		// 转移购物车中的数据
		D('Cart')->cookie2db();
	}

	// 实现注册功能
	public function regist()
	{
		$data = $this->create();
		// 检查用户名是否重复
		if($this->where("username='%s'",$data['username'])->find()){
			$this->error='用户名重复';
			return false;
		}
		// 校验手机号是否重复
		if($this->where("tel=%s",$data['tel'])->find()){
			$this->error='手机号重复';
			return false;
		}
		// 从session中将数据获取出来
		$sessionData = session('code');
		// 校验验证码是否有效
		if($sessionData['limit']+$sessionData['time']<time()){
			// 验证码以及过期
			$this->error='验证码以及过期';
			return false;
		}
		if(I('post.code')!=$sessionData['code']){
			// 验证码错误
			$this->error='验证码错误';
			return false;
		}
		$data['password']=md5($data['password']);
		// 将手机号注册的状态设置为已激活
		$data['status']=1;
		return $this->add($data);
	}

	// 实现登录
	public function login($username,$password)
	{
		$map=array(
			'username'=>$username,
			'password'=>md5($password)
		);
		$userinfo = $this->where($map)->find();
		if(!$userinfo){
			$this->error='用户名或者密码错误';
			return false;
		}
		if($userinfo['status']==0){
			$this->error='没有激活不能登录';
			return false;
		}
		// 保存用户状态
		unset($userinfo['password']);
		// 针对用户ID进行加密
		$userinfo['id'] = think_encrypt($userinfo['id']);
		cookie('userinfo',$userinfo);
		// 转移购物车中的数据
		D('Cart')->cookie2db();
		return true;
	}
	// 实现邮件注册
	public function registByEmail()
	{
		$data = $this->create();
		// 检查用户名是否重复
		if($this->where("username='%s'",$data['username'])->find()){
			$this->error='用户名重复';
			return false;
		}
		// 校验邮箱是否重复
		if($this->where("email='%s'",$data['email'])->find()){
			$this->error='邮箱重复';
			return false;
		}
		// 处理密码
		$data['password']=md5($data['password']);
		// 生成唯一的激活码
		$data['active_code']=uniqid();
		$this->add($data);
		return $data;
	}
}
?>
