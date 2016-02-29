<?php 
namespace Home\Controller;
use Think\Controller;

/**
 * 用户控制器
 */
class UserController extends Controller {
	public function register(){
		//2. 处理表单
		if(IS_POST){
			// 3. 创建商品模型
			$model=D('User');
			//4. 接受表单中的数据到模型中验证
			$cookieCode = $_COOKIE['code'];
			$code = $_POST['code'];
			if($code != $cookieCode){
				header('Content-Type:text/html;charset=utf-8');
				exit('非法验证码');
			}
			if($model->create(I('post.'), 1)){
				//5. 插入数据库
				if($model->add()){
					// 6. 提示成功信息，并跳转到lst页面
					$this->success('处理成功！',U('lst'));
					// 7. 终止程序，不然3秒后才跳转，会执行下面代码
					exit();
				}else{
					$this->error('处理失败！');
				}
			}
			//8. 失败获取失败信息 模型不报错的情况下，那就是MySQL执行出了错，
			//可以使用mysql_error() 方法获取对应错误信息
			// var_dump( mysql_error() );
			$error = $model->getError();
			//9. 提示错误信息
			$this->error($error,'',1);
		}
		//1. 显示表单
		$this->display();
    	}
            // 用户登录
    	public function login()
    	{
    		if(IS_POST){
    			$userName = I('post.user_name');
    			$psd = I('post.psd');

    			$token = C("MD5_KEY");
    			$tokenPsd = md5(md5($psd) . $token);

    			$userModel = D("User");
    			// 密码检测
    			$userInfo = $userModel->where("user_name = '$userName'")->find();
    			if($userInfo['psd'] !== $tokenPsd){
    				$this->error('登录失败');
    			}

    			//用户激活检测
    			if($userInfo['is_active'] == 0){
    				$this->error('请激活之后在登录');
    			}


    			$_SESSION['user_id'] = $userInfo['id'];
    			$_SESSION['user_name'] = $userInfo['user_name'];

                                    // 先将cookie的商品数据保存到cart表里面，然后清空cookie里面的数据
                                    $cartModel = D("Cart");
                                    $cartModel->moveCookieToDb();

                                    $returnUrl = $_SESSION['returnUrl'];

                                    if($returnUrl){
                                        $_SESSION['returnUrl'] = null; // 注意在登录成功之后，跳到回跳地址之前需要先情况session里面的数据
                                        $this->success('处理成功！',$returnUrl);
                                        exit();
                                    }else{
                                        $this->success('登录成功！', U('Index/index'));
                                        exit();
                                    }
    		}
    		$this->display();
    	}

    	public function logout()
    	{
    		$_SESSION['user_id'] = null;
    		$_SESSION['user_name'] = null;
    		$this->success('退出成功', U('Index/index'));
    	}
    	/**
    	 * 功能：激活用户状态
    	 */
    	public function check()
    	{	
    		// 获取用户ID
    		$userId = I("get.id");
    		// 获取用户激活token
    		$token = I('get.token');

    		$userModel = D('User');
    		$userInfo = $userModel->find($userId);

    		if($userInfo['token'] !== $token){
    			exit('非法修改');
    		}else{
    			// 设置用户状态为激活
    			$result = $userModel->where("id = $userId")->setField('is_active', 1);
    			if($result){
    				$this->success('激活成功', U('Index/index'));
    			}else{
    				$this->error('激活失败');
    			}
    		}
    	}

}


 ?>