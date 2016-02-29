<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link href="/Public/Admin/css/lightbox.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src=" "></script>
	<title>商品展示页面</title>
	<style>
		a.num{
			border: 1px solid red;
			margin: 3px;
			padding: 3px;
		}
		span.current{
			background: red;
			padding: 4px;
		}
	</style>
</head>
<body>
	<h1>商品详细展示页面</h1>
	<h2><a href="<?php echo U('Goods/lst');?>">返回商品列表</a></h2>
	<hr />
		<div>
			<?php echo "<input type='hidden' value='" . $info['id'] . "' name='id'>"; ?>
			商品名称：<?php echo $info['goods_name']."<br/>"; ?>
			商品数量：<?php echo $info['goods_number']."<br/>";?>
			商品图片：<?php  if(!empty($info['goods_thumb'])) echo ' <br /> <a href="http://local.14.com'. $viewPath . $info['goods_img'] .'" data-lightbox="my_lightbox"><img src="http://local.14.com/'.$viewPath.$info['goods_thumb'].'" alt="" /></a> <br />'; else echo "暂无图片<br/>";?>
			商品价格：<?php echo $info['goods_price']."<br/>";?>
			【商品描述】：<?php echo $info['goods_descp']."<br/>";?>
		</div>
</body>
<script type="text/javascript" src="/Public/Admin/js/lightbox-plus-jquery.js"></script>
<script type="text/javascript">

	// 表单搜索默认提交行为
	$("form[name=search]").click(function(event) {
		
		var _target = $(event.target);// 当前被点击的对象

		var _type = _target.attr('type'); // 当前被点击对象的类型 （text radio）

		if(_type == 'text'){
			var _This = this;
			// 如果是文本框失去焦点则触发提交
			_target.blur(function(event) {

				$(_This).submit();

			});

		}else if(_type == 'radio'){
			// 如果是单选框则触发提交
			$(this).submit();
		}
	});
</script>	
</html>