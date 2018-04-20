<?php 
	// 发送get请求
	// $res = file_get_contents("http://www.baidu.com");
	// echo $res;
	// 使用curl发送get请求
	// 1、打开会话
	$ch = curl_init();
	// 2、设置参数  请求地址、请求参数、请求头、请求方式
	// curl_setopt函数三个参数  第一个为打开的资源 第二个参数 为设置的参数名称(请求url地址或者其他)属于固定值，第三个参数为
	$url = 'http://www.baidu.com';
	curl_setopt($ch, CURLOPT_URL,$url);
	// 设置接口为不直接输出
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	// 3、执行请求
	$res = curl_exec($ch);
	// 4、关闭会话
	curl_close($ch);
	var_dump($res);
?>