<?php 
namespace Home\Model;
use Think\Model;

/**
* 用户模型
*/
class UserModel extends Model 
{
	
	
	//商品自动验证
	protected $_validate = array(
		array('user_name', 'require', '用户名称不能为空'),
	);

	public function _before_insert(&$data, $options)
	{
		$SALT = C('MD5_KEY');
		$data['psd'] = md5( md5($data['psd']) . $SALT);
		$data['token'] = uniqid(); // 发送邮件的时候传递的参数
		$data['register_time'] = time();
	}

	/**
	 * 后置钩子，在用户成功注册之后，发送邮件操作
	 * @param  [array] $data    [用户表单里面填写的信息]
	 * @param  [array] $options [操作表的一些条件]
	 * @return [type]          [description]
	 */
	public function _after_insert($data, $options)
	{
		$userId = $data['id'];

		$email = $data['email'];
		$token = $data['token'];

		$url = "<a href='http://local.13.com/index.php/Home/User/check/id/{$userId}/token/{$token}'>点击激活</a>";
		$content = "请前往{$url}";
		// false 代表失败 true代表成功
		return  sendMail($email, '邮箱验证' , $content);
	}
	

}

 ?>