<?php 
	// 身份认证标识可以在接口列表中查询到
	$key = 'e3673a585812e1a081a3a4a5a7066fe7';
	$com = 'yd';//快递公司的代号 可以通过接口获取
	// 运单号
	$no = '3872050027371';
	$url = 'http://v.juhe.cn/exp/index?key='.$key.'&com='.$com.'&no='.$no;
	// 发送请求
	$res = file_get_contents($url);
	var_dump($res);
?>