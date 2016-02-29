<?php 
	
/**
 * PHP防止XXS攻击过滤函数
 * @param  string $val 需要过滤的数据
 * @return string      过滤后的数据
 */
function removeXSS($val){

	static $obj = null;

	if($obj === null){

		require './HTMLPurifier/HTMLPurifier.includes.php';

		$obj = new HTMLPurifier();
	}

	return $obj->purify($val);
}





/**
 * 单张图片上传函数，支持多规格缩略图生成	
 * @param  [type] $imageName [表单中图片元素名字]
 * @param  [type] $saveDir   [上传图片保存子目录,默认为控制器名称]
 * @param  array  $thumb     [是否生成缩略图,arg1,arg2图片尺寸，arg3是否等比例] 
 * @return [type]            [上传后信息]
 */
function uploadOneImage($imageName, $saveDir, $thumb = array()){

	// 图片上传根路径
	$rootPath = C('ROOT_PATH');

	// 上传图片尺寸限制
	$allowMaxSize = C('UPLOAD_MAX_FILESIZE');// 配置文件大小
	$iniMaxSize = ini_get('upload_max_filesize');// php.ini限制单文件大小
	$maxFileSize = (int)min($allowMaxSize, $iniMaxSize);// 允许上传的图片大小
	

	$exts = C('ALLOW_UPLOAD_EXTS');// 允许上传图片的后缀

	// 实例化上传类
	$upload = new \Think\Upload(
		array(
		'maxSize' =>$maxFileSize * 1024 * 1024,
		'exts' => $exts,
		'rootPath' => $rootPath,
		'savePath'=>$saveDir . '/',
		)

	);

	// 上传信息
	$info = $upload->upload(array("$imageName"=>$_FILES["$imageName"]));

	if($info){

		// 上传成功后图片原始路径
		$images[0] = $info[$imageName]['savepath'] . $info[$imageName]['savename'];

		// 传递第三个参数，则生成缩略图
		if($thumb){

			$image = new \Think\Image();

			foreach ($thumb as $k => $v) {
				
				$image->open($rootPath . $images[0]);
				$thumbName = $info[$imageName]['savepath'] . 'thumb_' . $k . '_' . $info[$imageName]['savename']; 
				$image->thumb($v[0], $v[1], $v[2])->save($rootPath . $thumbName);
				$images[] = $thumbName;
			}
		} 

		return array(
			'ok' =>1,
			'images' =>$images,
			);  
	}
	
	$error = $upload->getError();
	return array(
		'ok' =>0,
		'error' =>$error,
	);
}

/**
 * 显示商品图片
 * @param  string $imageName 商品名称
 * @param  number $width     商品显示宽度
 * @param  number $height    商品显示高度
 * @return img标签            商品显示img标签
 */
function showImage($url, $imageName, $width='150px', $height='150px'){

	static $prev = null;

	if($prev === null){
		$prev = C('VIEW_ROOT_PATH');
	}

	if($width){
		$width = " width='$width' ";
	}

	if($height){
		$height = " height='$height' "; 
	}
	echo "<br/> <img $width $height src='http://local.14.com/$prev$imageName'><br/>";
	
}


/**
 * 删除图片前置钩子
 * @param  array $imageName 图片地址数组
 * @return           无
 */
function deleteImage($imageName){

	static $prev = null;
	if($prev === null){
		$prev = C('ROOT_PATH');
	}

	foreach ($imageName as $key => $value) {
			@unlink($prev. $value);
	}
}

/**
 * [递归函数]
 * @param  array  $data      需要递归的数据
 * @param  integer $parent_id 父类ID
 * @param  integer $level     递归层数
 * @return [type]             [description]
 */
function digui($data, $parent_id = 0, $level=0)
	{
		// 1. 定义一个static变量
		static $list = array();
		// 2.遍历所有数据
		foreach ($data as $k => $v) {
			// 3.判断
			if($v['parent_id'] == $parent_id){

				$v['level'] = $level;
				$list[] = $v;

				digui($data, $v['id'], $level+1);
			}

		}
		return $list;
	}

	/**
	 * 邮件发送函数
	 * @param  string $to      邮件接收者
	 * @param  string $from    邮件发送者
	 * @param  string $content 邮件内容
	 * @return 无         
	 */
	function sendMail($to, $from, $content){
		/*
		 * 这个不稳定，有的时候也不可以用
		 *  sina 邮箱测试：smtp.sina.com
		 * username: gogery@sina.com
		 * password: php1234
		*/

		/*
		 * 稳定可以使用，建议使用这个作为测试学习
		 *  sohu 邮箱测试：smtp.sohu.com
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
		// $mail->Host       = 'smtp.sina.com';    //设置 SMTP 服务器,自己注册邮箱服务器地址
		// 需要注意的是，并不是所有的邮件服务器商都提供免费的SMTP服务
		$mail->Host       = 'smtp.sohu.com';    //设置 SMTP 服务器,自己注册邮箱服务器地址

		//需要自己手工的配置
		$mail->Username   = 'gogery';  	//发信人的邮箱用户名
		$mail->Password   = 'php1234';  //发信人的邮箱密码

		/*内容信息*/
		$mail->IsHTML(true); 	//指定邮件内容格式为：html
		$mail->CharSet    ="UTF-8";	//编码
		// $mail->From       = 'gogery@sina.com';	 //发件人完整的邮箱名称

		// 可以自己手工的配置
		$mail->From       = 'gogery@sohu.com';	 //【注意】发件人完整的邮箱名称
		$mail->FromName   = $from;	 //发信人署名
		$mail->Subject    = "PHP邮件测试";  	 //信的标题
		$mail->MsgHTML( $content );  	//发信主体内容
		// 可以在发送邮件的时候发送附件信息
		// $mail->AddAttachment("./img/3.jpg"); //附件
		// $mail->AddAttachment("./1.txt"); //附件

		//发送邮件 添加收件人地址
		$mail->AddAddress( $to );  //收件人地址
				
		//使用send函数进行发送
		if( $mail->Send() ) {

			//邮件成功
		  	return array(
		  		'sign'=>1,
		  		'msg'=>'success',
		  	);

		} else {

		    	//如果发送失败，则返回错误提示	
		    	return array(
		    		'sign'=>0,
		    		'msg'=>$mail->ErrorInfo,
		    	);
		}

	}
/**
 * 获取当前页面完整URL地址
 */
 function get_url() {
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
    return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
 }


/**
 * 邮箱验证
 * @param  string  $user_email 邮箱
 * @return boolean             验证结果
 */
 function is_email($user_email)
{
    $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
    if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false)
    {
        if (preg_match($chars, $user_email))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}


 ?>