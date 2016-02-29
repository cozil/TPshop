<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link href=" " rel="stylesheet" type="text/css" />
	<script type="text/javascript" src=" "></script>
	<title>validate.js自定义验证规则</title>
</head>
<body>
	<h1>validate.js自定义验证规则</h1>
	<hr />
	<form action="/index.php/Home/Index/test" id="validate_form" method="POST" enctype="multipart/form-data">
			
		<p>
			手机：<input type="text" name="phone"  value=""  id=""/>
		</p>
		<p>
			名称：<input type="text" name="user_name" value=""  id=""/>
		</p>
		
		<p>
			<input type="submit" value="提交" />
		</p>
	</form>
	
</body>
<script type="text/javascript" src="/Public/Js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/Public/Js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/Js/validate_zh_cn.js"></script>
<script type="text/javascript">

$.validator.addMethod("isphone", function(value, element) {
    // this.arguments 类数组 因为它没有length属性
   //匹配13，14，15，18开头的手机号码   || 注意：如果正则这样写 /0?1[3|4|5|8][0-9]\d{8}/; 这个手机号码也可以匹配出来 aa18620628729sdaf
    var reg = /^0?1[3|4|5|8][0-9]\d{8}$/;

    return (reg.test(value));
}, "请正确填写手机号码");
	$("#validate_form").validate({
		rules:{
			user_name:'required',
			phone:{
				isphone:true,
			}
		}
	})
</script>
</html>