<?php
namespace Home\Controller;
// 订单的控制器
class OrderController extends CommonController {

	// 结算页的显示
	public function check()
	{
		$bak = 'http://shop.com/User/login.html?bak='.urlencode('http://shop.com/index.php?m=Home&c='.CONTROLLER_NAME.'&a='.ACTION_NAME);
		// 检查是否登录
		$this->checkLogin($bak);
		// 获取购物车中数据
    	$data = D('Cart')->getList();
    	$this->assign('data',$data);
    	// dump($data);
    	// 调用模型方法计算总金额
    	$total = D('Cart')->getTotal($data);
    	$this->assign('total',$total);
    	$this->display();
	}
	// 实现下单
	public function order()
	{
		// 检查是否登录
		$this->checkLogin();
		$model = D('Order');
		$res = $model->order();
		if($res === false){
			$this->error($model->getError);
		}
		// 开始使用支付宝支付
		require_once '../alipay/config.php';
		require_once '../alipay/pagepay/service/AlipayTradeService.php';
		require_once '../alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';

	    //商户订单号，商户网站订单系统中唯一订单号，必填
	    $out_trade_no = trim($res['id']);
	    //订单名称，必填
	    $subject = 'test';
	    //付款金额，必填
	    $total_amount = $res['total_price'];
	    //商品描述，可空
	    $body = 'test-dec';
		//构造参数
		$payRequestBuilder = new \AlipayTradePagePayContentBuilder();
		$payRequestBuilder->setBody($body);
		$payRequestBuilder->setSubject($subject);
		$payRequestBuilder->setTotalAmount($total_amount);
		$payRequestBuilder->setOutTradeNo($out_trade_no);

		$aop = new \AlipayTradeService($config);
		// 调用方法实现支付
		$response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);

		//输出表单
		var_dump($response);

	}

	// 支付宝同步回调接口
	public function returnUrl()
	{
		require_once("../alipay/config.php");
		require_once '../alipay/pagepay/service/AlipayTradeService.php';
		$arr=$_GET;
		$alipaySevice = new \AlipayTradeService($config); 
		// 签名校验 必须要进行
		$result = $alipaySevice->check($arr);
		if(!$result) {
			//验证失败
		    echo "fail";exit();
		}

		// 用户付款成功 处理自己的业务逻辑
		//商户订单号
		$out_trade_no = htmlspecialchars($_GET['out_trade_no']);
		// 修改用户的支付状态
		D('Order')->where('id=%d',$out_trade_no)->setField('status',1);
		// 如果还有其他的逻辑继续执行
		echo "ok";
	}

	// 支付宝异步接口地址
	public function notifyUrl()
	{
		require_once '../alipay/config.php';
		require_once '../alipay/pagepay/service/AlipayTradeService.php';
		$arr=$_POST;
		$alipaySevice = new \AlipayTradeService($config); 
		$alipaySevice->writeLog(var_export($_POST,true));
		$result = $alipaySevice->check($arr);	
		if(!$result) {
			echo "fail";exit;
		}
		//商户订单号
		$out_trade_no = $_POST['out_trade_no'];
		$order = D('Order')->find($out_trade_no);
		if($order['status']==0){
			D('Order')->where('id=%d',$out_trade_no)->setField('status',1);	
		}
		echo "success";	//请不要修改或删除
	}
}