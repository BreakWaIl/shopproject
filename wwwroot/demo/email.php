<?php
	require './PHPMailer/class.phpmailer.php';
	$mail             = new PHPMailer();
	/*服务器相关信息*/
	$mail->IsSMTP();  //启用smtp方式发送邮件                      
	$mail->SMTPAuth   = true; //控制是否需要进行校验               
	$mail->Host       = 'smtp.163.com'; //smtp服务地址  	   
	$mail->Username   = 'phpresources'; //邮箱的用户名 		
	$mail->Password   = 'qazwsxedc123'; //邮箱第三方授权密码
	/*内容信息*/
	$mail->IsHTML(true);
	$mail->CharSet    ="UTF-8";			
	$mail->From       = 'phpresources@163.com';	 //发件箱		
	$mail->FromName   ="地球管理员";	  //发件箱的昵称
	$mail->Subject    = '邮件发送使用phpmailer'; //发件箱的主题
	$mail->MsgHTML('<strong>邮件发送使用phpmailer</strong><br/>邮件发送使用phpmailer');
	$mail->AddAddress('phper_leo@163.com');//收件人地址  
	$mail->AddAttachment("1.jpg"); //添加附件
	$res = $mail->Send();
	var_dump($res);
?>