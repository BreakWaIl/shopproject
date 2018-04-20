<?php 
namespace Admin\Model;
use Think\Model;
/**
* 商品模型
*/
class GoodsModel extends Model
{
	// 定义字段信息
	protected $fields=array(
		'id','goods_name','goods_sn','market_price','shop_price','goods_number','type_id','cate_id','goods_img','goods_thumb','goods_body','addtime','is_hot','is_new','is_rec','is_del'
	);

	// 验证规则
	protected $_validate=array(
		array('goods_name','require','商品名称需要填写'),
		array('market_price','currency','市场售价价格不合适'),
		array('shop_price','currency','本店售价价格不合适'),
		array('goods_number','checkNumber','商品数量不合适',1,'callback'),
	);

	// 前置钩子函数，实现数据入库之前的处理
	public function _before_insert(&$data,$options)
	{
		// 添加的时间
		$data['addtime']=time();
		// 处理货号，用户提交检查重复问题。没有提交则生成唯一的货号
		if(!$data['goods_sn']){
			// 没有提交货号
			$data['goods_sn']='JX'.uniqid();
		}else{
			// 用户有提交货号 检查重复问题  使用货号进行查找 找到说明重复
			$res = $this->where(array('goods_sn'=>$data['goods_sn']))->find();
			if($res){
				$this->error='货号重复';
				return false;
			}
		}
		// 验证价格问题
		if($data['market_price']<=$data['shop_price']){
			$this->error='价格不合理';
			return false;
		}
	}

	// 添加后置钩子函数
	public function _after_insert($data,$options)
	{	
		$goods_id = $data['id'];
		// 接受到的属性信息 是二维数组  第一个维度下标为属性的ID 第二个维度下的值就是属性值
		$attr = I('post.attr');
		foreach ($attr as $key => $value) {
			foreach ($value as $v) {
				$attrList[]=array(
					'goods_id'=>$goods_id,
					'attr_id'=>$key,
					'attr_value'=>$v
				);
			}
		}
		M('GoodsAttr')->addAll($attrList);
		// 实现相册功能
		// 释放商品缩略图文件上传的信息
		unset($_FILES['file']);
		$upload = new \Think\Upload();
		$info = $upload->upload();
		foreach ($info as $key => $value) {
			// 组装上传到服务器上的地址
			$goods_img = 'Uploads/'.$value['savepath'].$value['savename'];
			$img = new \Think\Image();
			$img->open($goods_img);
			$goods_thumb = 'Uploads/'.$value['savepath'].'thumb_'.$value['savename'];
			$img->thumb(100,100)->save($goods_thumb);
			$goods_img_list[]=array(
				'goods_id'=>$goods_id,
				'goods_img'=>$goods_img,
				'goods_thumb'=>$goods_thumb
			);
		}
		// 实现相册入库
		if($goods_img_list){
			M('GoodsImg')->addAll($goods_img_list);
		}
	}

	// 检查商品数量
	public function checkNumber($goods_number)
	{
		if($goods_number<=0){
			return false;
		}
		return true;
	}

	// 获取商品的列表数据
	public function listData($tree=array(),$is_del=1)
	{

		$where = 'is_del= '.$is_del;
		// 拼接where条件
		$cate_id = I('get.cat_id');
		if($cate_id){
			// 提交了分类
			$where .= ' AND cate_id='.$cate_id;
		}
		$intro_type = I('get.intro_type');//推荐状态
		if($intro_type){
			// 选择中推荐状态
			if($intro_type == 'is_hot' || $intro_type == 'is_new' || $intro_type == 'is_rec'){
				$where .= ' AND '.$intro_type.'=1';
			}			
		}
		$keyword = I('get.keyword');
		if($keyword){
			$where .= " AND goods_name like '%$keyword%'";
		}

		// exit;
		// 1、获取当前页码
		$p = intval(I('get.p'));
		// 2、定义每页显示数量
		$pagesize = 20;

		// 3、计算总数
		$count = $this->where($where)->count();
		// 4、计算分页导航信息
		$page = new \Think\Page($count,$pagesize);
		$pageStr = $page->show();
		// 5、根据页码获取数据
		// a)、获取所有的分类信息
		if(!$tree){
			$tree= D('Category')->getTree();
		}	
		
		// b)、获取商品信息
		$list = $this->page($p,$pagesize)->where($where)->select();
		// c)循环替换内容
		foreach ($list as $key => $value) {
			// $value['cate_id']代表循环的当前商品对应的分类id标识
			// $category[$value['cate_id']]['cname'] 获取到某个分类对于的名称
			// $list[$key]['cate_id']直接将原始值进行修改
			$list[$key]['cate_id']=$tree[$value['cate_id']]['cname'];
		}

		// 将分页跟数据全部返回
		return array('list'=>$list,'pageStr'=>$pageStr);
	}
	// 商品的彻底删除
	public function remove()
	{
		$goods_id = I('get.goods_id',0,'intval');
		$goodsInfo= $this->where('id='.$goods_id)->find();
		if(!$goodsInfo){
			$this->error='参数错误';
			return false;
		}
		$this->startTrans();
		$res = $this->where('id='.$goods_id)->delete();
		if($res === false){
			$this->rollback();
		}
		$this->commit();
		// 删除图片
		@unlink($goodsInfo['goods_img']);
		@unlink($goodsInfo['goods_thumb']);
		return true;
	}

	// 修改商品
	public function update()
	{

		$goods_id = I('post.goods_id',0,'intval');
		// 创建数据
		$data = $this->create();
		// 处理价格
		if($data['market_price']<=$data['shop_price']){
			$this->error='价格不合理';
			return false;
		}
		// 处理货号
		if(!$data['goods_sn']){
			$data['goods_sn']='JX'.uniqid();
		}else{
			// 检查重复问题 需要排除自己本身
			$info = $this->where("goods_sn='%s' AND id != %d",$data['goods_sn'],$goods_id)->find();
			if($info){
				$this->error='货号重复';
				return false;
			}
		}

		// 删除已有的属性信息
		$attrModel = M('GoodsAttr');
		$attrModel->where('goods_id='.$goods_id)->delete();
		// 写入最新的属性信息
		$attr = I('post.attr');
		foreach ($attr as $key => $value) {
			foreach ($value as $v) {
				$attrList[]=array(
					'goods_id'=>$goods_id,
					'attr_id'=>$key,
					'attr_value'=>$v
				);
			}
		}
		M('GoodsAttr')->addAll($attrList);
		// 实现相册追加图片
		unset($_FILES['file']);
		$upload = new \Think\Upload();
		$info = $upload->upload();
		foreach ($info as $key => $value) {
			// 组装上传到服务器上的地址
			$goods_img = 'Uploads/'.$value['savepath'].$value['savename'];
			$img = new \Think\Image();
			$img->open($goods_img);
			$goods_thumb = 'Uploads/'.$value['savepath'].'thumb_'.$value['savename'];
			$img->thumb(100,100)->save($goods_thumb);
			$goods_img_list[]=array(
				'goods_id'=>$goods_id,
				'goods_img'=>$goods_img,
				'goods_thumb'=>$goods_thumb
			);
		}
		// 实现相册入库
		if($goods_img_list){
			M('GoodsImg')->addAll($goods_img_list);
		}
		return $this->where('id='.$goods_id)->save($data);
	}
}

?>