<?php 
function sendMail($to, $from, $content){
		/*
		 * sina 邮箱测试：smtp.sina.com
		 * username: gogery@sina.com
		 * password: php1234
		*/

		/*
		 * sohu 邮箱测试：smtp.sohu.com
		 * username: gogery@sohu.com
		 * password: php1234
		*/

		header("Content-type:text/html;charset=utf-8");
		//引入邮件类
		require './PHPMailer/class.phpmailer.php';
		
		$mail = new PHPMailer();

		/*服务器相关信息*/
		$mail->IsSMTP();    //设置使用SMTP服务器发送
		$mail->SMTPAuth   = true;     //开启SMTP认证
		$mail->Host       = 'smtp.sina.com';    //设置 SMTP 服务器,自己注册邮箱服务器地址
		// $mail->Host       = 'smtp.sohu.com';    //设置 SMTP 服务器,自己注册邮箱服务器地址
		$mail->Username   = 'gogery';  	//发信人的邮箱用户名 是不包含@
		$mail->Password   = 'php1234';  //发信人的邮箱密码

		/*内容信息*/
		$mail->IsHTML(true); 	//指定邮件内容格式为：html
		$mail->CharSet    ="UTF-8";	//编码
		$mail->From       = 'gogery@sina.com';	 //发件人完整的邮箱名称包含@
		// $mail->From       = 'gogery@sohu.com';	 //发件人完整的邮箱名称包含@
		$mail->FromName   = $from;	//发信人署名
		$mail->Subject    = $from;  	 //信的标题
		$mail->MsgHTML( $content );  	//发信主体内容 发送具体邮件

		//发送邮件
		$mail->AddAddress( $to );  //收件人地址
				
		//使用send函数进行发送
		if( $mail->Send() ) {

		  	return true;

		} else {
		    	//如果发送失败，则返回错误提示	
		    	
		    	// echo $mail->ErrorInfo;
		    	return false;
		}

	}


 ?>