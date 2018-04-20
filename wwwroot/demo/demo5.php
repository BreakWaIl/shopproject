<?php 
	$username = $_REQUEST['username']?$_REQUEST['username']:'';
	$password = $_REQUEST['password']?$_REQUEST['password']:'';
	if(!$username || !$password){
		echo json_encode(array('status'=>0,'msg'=>'parm error'));
		exit();
	}
	mysql_connect('127.0.0.1','root','123456');
	mysql_select_db('shop');
	mysql_query("set names utf8");
	$password = md5($password);
	$sql ="select * from shop_user where username='$username' and password='$password'";
	$res = mysql_query($sql);
	$row = mysql_fetch_assoc($res);
	if(!$row){
		echo json_encode(array('status'=>0,'msg'=>'parm error'));
		exit();
	}
	echo json_encode(array('status'=>1,'msg'=>'ok','data'=>$row));

?>