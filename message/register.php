<?php 	
	header('Content-Type:text/html;charset=utf-8');
	// 获取表单信息
	$user_name = $_POST['user_name'];
	$psd = $_POST['psd'];
	$psd = md5($psd);
	$iphone = $_POST['iphone'];
	$email = $_POST['email'];
	$code = $_POST['code'];

	$cookieCode = $_COOKIE['code'];
	if($code != $cookieCode){
		exit('非法传参');
	}
	mysql_connect('localhost','root','admin88');
	mysql_query("set names utf8");

	mysql_select_db('test');

	$sql = "insert into register values(null, '$user_name', '$psd', 0, '$email','$iphone')";

	$result = mysql_query($sql);

	if($result){
		echo "会员注册成功";
	}else{
		echo "会员注册失败";
	}


 ?>