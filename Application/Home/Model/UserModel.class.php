<?php 
namespace Home\Model;
use Think\Model;

/**
* 会员模型
*/
class UserModel extends Model 
{
	
	
	//会员自动验证
	protected $_validate = array(
		array('username', 'require', '会员名称不能为空'),
	);

	/****************前置钩子完成密码加密操作*****************/	
	public function _before_insert(&$data, $options)
	{
		// 1. 密码加密，双重md5  
		// 2. 生成token 
		// 3. 生成注册时间
		$userEmail = $data['email']; // 用户游戏啊
		// 检测用户的邮箱是否唯一
		$result = $this->field('email,username')->where("email = '$userEmail'")->find();

		if(in_array($username, $result) || in_array($userEmail, $result)){
			$this->error = '用户名或者邮箱重复';
			return false;
		}
		$salt = C("MD5_KEY");
		$data['password'] = md5( md5($data['password']) . $salt );

		$data['token'] = md5( uniqid() ); // 随机生成一个字符串

		$data['register_time'] = time(); // 生成一个注册时间
	}


	/****************后置钩子里面实现邮件验证激活发送*****************/
	public function _after_insert($data, $options)
	{

		// 1. 加密userid
		// 2. 获取token
		// 3. 获取email
		// 4. 拼接激活链接
		// 5. 发送邮件
		$userId = base64_encode( $data['id'] ); // 会员id

		$userToken = $data['token']; // 验证token

		$userEmail = $data['email']; // 用户邮箱

		// 激活链接
		$url = "<a href='http://local.14.com/index.php/Home/User/check/id/{$userId}/token/{$userToken}'>请点击激活</a>";
		$content = "尊敬的用户,{$url}";
		$result = sendMail($userEmail, '邮箱激活', $content);
		
		if($result['sign'] == 0 ){
			$this->error = $result['msg'];
			return false;
		}else{
			return true;
		}
	}

	/**
	 * 更新前置钩子，实现找回密码加盐操作
	 */
	public function _before_update(&$data, $options)
	{	
		
			// $salt = C("MD5_KEY");
			// $data['password'] 在做用户状态激活的时候，这个信息没有
			// md5( null . $salt )
			// 相当于做了 is_active = 1 password = md5(null. $salt)
			// $data['password'] = md5( md5($data['password']) . $salt );
	}
}
 ?>