<?php
namespace Home\Controller;

class UserController extends CommonController {

    public function callback()
    {
        require_once("./qq/API/qqConnectAPI.php");
        $qc = new \QC();
        // access_token
        $access_token =  $qc->qq_callback();
        // openid
        $openid =  $qc->get_openid();
        // 获取用户的详细信息
        //建议使用此方式获取用户的详细信息
        $qc= new \QC($access_token,$openid);
        $user = $qc->get_user_info();
        // 通过调用用户模型下的方法实现用户的注册
        D('User')->qqLogin($user,$openid);
        echo 'ok';
    }

    public function oauth()
    {
        require_once("./qq/API/qqConnectAPI.php");
        $qc = new \QC();
        $qc->qq_login();
    }


	// 用户注册
    public function regist()
    {   
    	if(IS_GET){
    		$this->display();
    	}else{
    		$model = D('User');
    		// 调用模型方法实现注册
    		$user_id =$model->regist();
    		if($user_id === false){
    			$this->error($model->getError());
    		}
    		$this->success('ok');
    	}
    }

    // 用户登录
    public function login()
    {
    	if(IS_GET){
    		$this->display();
    	}else{
    		$model = D('User');
    		$result = $model->login(I('post.username'),I('post.password'));
    		if($result === false){
    			$this->error($model->getError());
    		}

            $bak= I('request.bak','','htmlspecialchars_decode');
            
            if($bak){
                header('Location:'.$bak);
            }else{
                $this->redirect('Index/index');
            }
    		
    	}
    }

    public function sendSms()
    {
        $tel = I('request.tel');
        if(!$tel || strlen($tel)!=11){
            $this->ajaxReturn(array('status'=>'0','msg'=>'error'));
        }
        $code = rand(1000,9999);//验证码
        $limit = 1800;//有效时间
        // $res = send_sms($tel,array($code,$limit),1);
        $res= true;
        if(!$res){
            $this->ajaxReturn(array('status'=>'0','msg'=>'发送失败'));
        }
        // 需要保存到session中的内容
        $data= array(
            'code'=>$code,
            'limit'=>$limit,
            'time'=>time()//验证码生成时间  是为了跟limit一起配合验证码是否过期
        );
        session('code',$data);
        $this->ajaxReturn(array('status'=>'1','msg'=>'ok'));
    }

    // 邮箱注册
    public function registByEmail()
    {
        if(IS_GET){
            $this->display();
        }else{

            // post表单提交
            $model = D('User');
            $res = $model->registByEmail();
            if($res===false){
                $this->error($model->getError());
            }
            // 处理发送邮件
            $link = 'http://shop.com'.U('active').'?key='.$res['active_code'];
            send_email($res['email'],'注册激活邮件',$link);
            $this->success('注册成功',U('login'));
        }
    }

    // 实现用户的激活
    public function active()
    {
        $key = I('get.key');//获取激活码
        $userinfo = D('User')->where("active_code='%s'",$key)->find();
        if(!$userinfo){
            $this->error('参数错误',U('registByEmail'));
        }
        if($userinfo['status']==1){
            $this->error('已激活',U('login'));
        }
        // 设置用户为已激活
        D('User')->where("active_code='%s'",$key)->setField('status',1);
        $this->success('激活成功',U('login'));
    }

    public function test()
    {
        dump(session());
    }

    public function logout()
    {
        cookie('userinfo',null);
    }
    // 测试加密解密
    public function testjiajie()
    {
    	dump(cookie());
    	header('content-Type:text/html;charset=utf-8');
    	$str = I('get.str');
    	echo '原始字符串'.$str.'<br/>';
    	$str2 = think_encrypt($str);
    	echo '加密字符串'.$str2.'<br/>';
    	$str3 = think_decrypt($str2);
    	echo '解密字符串'.$str3.'<br/>';
    }
    // 测试短信发送
    public function testsms()
    {
        var_dump(send_sms("18520409113",array('123445','30'),1));
    }
    public function testsendemail()
    {
        var_dump(send_email('phper_leo@163.com','中奖100万','访问连接地址领取奖励'));
    }
}
?>