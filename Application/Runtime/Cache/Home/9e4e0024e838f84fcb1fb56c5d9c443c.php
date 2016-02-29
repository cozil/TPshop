<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link href=" " rel="stylesheet" type="text/css" />
	<script type="text/javascript" src=" "></script>
	<title>修改密码</title>
</head>
<body>
	<h1>修改密码</h1>
	<hr />
	<form action="/index.php/Home/User/changePassword/id/22/token/96be70c2a9453299a96ce2163784f89b" method="POST" enctype="multipart/form-data">
		<p>
			用户名：<b><?php echo $userInfo['username'];?></b>
		</p>
		<input type="hidden" name="id"  value="<?php echo $userInfo['id']?>" id="" />
		<input type="hidden" name="token" value="<?php echo $userInfo['token'];?>" id="" />
		<p>
			新密码：<input type="text" name="password" value=""  id=""/>
		</p>
		
		<p>
			<input type="submit" value="提交" />
		</p>
	</form>
</body>
</html>