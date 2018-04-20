<?php 
namespace Admin\Model;
use Think\Model;

/**
* 后台管理员模型
*/
class AdminModel extends Model
{
	// 指定主键字段 如果主键字段为id可以省略
	protected $pk = 'id';
	// 自定义数据表的字段
	protected $fields=array('id','username','password','salt');
	// 定义字段映射
	protected $_map=array(
		'name'=>'username',
		'pwd'=>'password'
	);
	// 定义自动验证规则
	protected $_validate=array(
		array('username','require','用户名不能为空!'),
		array('password','checkLength','密码长度不合适!',1,'callback'),
	);
	// 检查密码的长度
	public function checkLength($str){
		if(strlen($str)<4){
			return false;
		}
		return true;
	}
	// 实现登录操作
	public function login($username,$password,$code)
	{
		// 1、检查验证码
		$obj = new \Think\Verify();	
		if(!$obj->check($code)){
			// 验证码不对
			$this->error = '验证码不匹配';//设置错误信息
			return false;
		}
		// 2、查询用户
		$userInfo = $this->where("username='%s'",$username)->find();
		if(!$userInfo){
			$this->error = '用户名错误';//设置错误信息
			return false;
		}
		// 3、比对密码
		if($userInfo['password'] != md6($password,$userInfo['salt'])){
			$this->error = '密码错误';//设置错误信息
			return false;
		}
		// 4、保存登录状态  考虑保存登录状态
		$isremember = I('post.remember');//接受是否需要保存登录状态
		$expire = 0;//默认不需要保存登录状态
		if($isremember){
			// 需要保存
			$expire = 3600*24*3;
		}
		// 可以考虑使用cookie加密 更加安全
		unset($userInfo['password']);
		unset($userInfo['salt']);
		cookie('_user',$userInfo,$expire);
		return true;
	}

	// 实现用户添加
	public function insert($username,$password,$role_id)
	{
		//1、检查用户是否重复 
		if($this->where(array('username'=>$username))->find()){
			$this->error='用户名重复';
			return false;
		}
		//2、生成盐并且处理密码
		$salt = rand(100000,999999);
		$password = md6($password,$salt);
		//3、数据入库写入用户信息
		$data = array(
			'username'=>$username,
			'password'=>$password,
			'salt'=>$salt
		);
		$this->startTrans();
		$admin_id = $this->add($data);
		if(!$admin_id){
			$this->error='写入失败';
			$this->rollback();
			return false;
		}
		//4、写入用户角色中间中对应关系
		$insert_id = D('AdminRole')->add(array('admin_id'=>$admin_id,'role_id'=>$role_id));
		if(!$insert_id){
			$this->error='写入中间表失败';
			$this->rollback();
			return false;
		}
		$this->commit();
		return true;
	}

	public function listData()
	{
		$p = I('get.p');

		$pagesize = 10;

		$count = $this->count();

		$page = new \Think\Page($count,$pagesize);

		$pageStr = $page->show();

		$list = $this->alias('a')->field('a.*,c.role_name')->join('LEFT JOIN shop_admin_role b on a.id=b.admin_id')->join('LEFT JOIN shop_role c on b.role_id=c.id')->page($p,$pagesize)->select();

		return array('pageStr'=>$pageStr,'list'=>$list);
	}

	public function dels($admin_id)
	{
		// 删除用户表中数据
		$this->where('id='.$admin_id)->delete();
		// 删除用户角色中间表数据
		D('AdminRole')->where('admin_id='.$admin_id)->delete();
		return true;
	}

	// 获取用户对应的信息 包括用户信息以及角色ID及名称
	public function getUserRole($admin_id)
	{
		return $this->alias('a')->field('a.*,b.role_id,c.role_name')->join('LEFT JOIN shop_admin_role b on a.id=b.admin_id')->join('LEFT JOIN shop_role c on b.role_id=c.id')->where('a.id=%d',$admin_id)->find();
	}
	// 编辑用户信息
	public function update($username,$password,$role_id,$admin_id)
	{
		// 用户名是否重复
		$userInfo=$this->getUserRole($admin_id);
		//组装条件 username = '$username' and id != $admin_id
		$map=array(
			'username'=>$username,
			'id'=>array('neq',$admin_id)
		);
		if($this->where($map)->find()){
			$this->error='用户重复';
			return false;
		}
		$data=array(
			'username'=>$username,	
		);
		// 密码是否修改
		if($password){
			$data['password'] = md6($password,$userInfo['salt']);
		}
		// 信息修改入库
		$this->where('id=%d',$admin_id)->save($data);
		// 角色信息是否修改
		if($role_id != $userInfo['role_id']){
			// 说明用户所属的角色有变化
			D('AdminRole')->where('admin_id=%d',$admin_id)->setField('role_id',$role_id);
		}
	}

}
?>