<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link href=" " rel="stylesheet" type="text/css" />
	<script type="text/javascript" src=" "></script>
	<title>找回密码</title>
</head>
<body>
	<h1>找回密码</h1>
	<hr />
	<form action="/index.php/Home/User/findPasswod.html" method="POST" enctype="multipart/form-data"  id="find_form">
		<p>请输入在注册会员时候的用户名或者密码，然后点击找回密码按钮</p>
		<p>
			用户或邮箱：<input type="text" name="data" value="" />
		</p>
		
		<p>
			<input type="submit" value="找回密码" />
		</p>
	</form>
</body>
<script type="text/javascript" src="/Public/Js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/Public/Js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/Js/validate_zh_cn.js"></script>
<script type="text/javascript">
	$("#find_form").validate({
		rules:{
			data:{
				required:true,
			}
		}
	})
</script>
</html>