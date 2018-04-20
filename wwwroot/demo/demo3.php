<?php 
	// 1、打开会话
	$ch = curl_init();
	// 2、设置参数
	// 设置请求的url地址
	$url = 'http://shop.com/demo/demo2.php';
	curl_setopt($ch,CURLOPT_URL,$url);
	//设置结果不直接输出
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
	// 请求方式为post请求
	curl_setopt($ch,CURLOPT_POST,TRUE);
	// 设置请求参数
	$data = array('name'=>'leo','age'=>18);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
	// 3、执行请求
	$res = curl_exec($ch);
	// 4、关闭会话
	curl_close($ch);
	var_dump($res);
?>