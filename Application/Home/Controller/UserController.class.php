<?php 
namespace Home\Controller;
use Think\Controller;

/**
 * 会员控制器 注意命名空间为 Home
 * MyController 继承系统控制器
 */
class UserController extends Controller {

	/**
	 * 会员注册
	 */
	public function register(){

    		//2. 处理表单
    		if(IS_POST){
    			// 3. 创建会员模型
    			$model=D('User');
                                    $code = I('post.code');
                                    $cookieCode = $_COOKIE['code'];

                                    if($code !== $cookieCode){
                                    header('Content-Type:text/html;charset=utf-8');
                                        exit('验证码不合法');
                                    }

    			//4. 接受表单中的数据到模型中验证
    			if($model->create()){
    				//5. 插入数据库
    				if($model->add()){
    					// 6. 提示成功信息，并跳转到首页
    					$this->success('注册成功！，请激活后登录',U('Index/index'));
    					// 7. 终止程序，不然3秒后才跳转，会执行下面代码
    					exit();
    				}else{  
                                                            // 获取MySQL数据库错误或者钩子函数错误
    					$erro = mysql_error() . $model->getError();
    					$this->error('注册失败！！'. $erro);
    				}
    			}
    			//8. 获取自动验证提示信息
    			$error = $model->getError();
    			//9. 提示自动验证提示信息
    			$this->error($error,'',1);
    		
    		}

                    // 获取导航栏信息，默认pid 为 0 
                    $categoryModel = D("Admin/Category");
                    $navData = $categoryModel->getNav();

                    $this->assign('navData', $navData);

    	       //1. 显示表单
    	       $this->display();
    		
		
    	}

        /**
         * 用户登录
         */

        public function login()
        {

            if(IS_POST){
                $username = I('post.username');

                // 对用户提交的密码加盐
                $password = I('post.password');
                $salt = C("MD5_KEY");// 读取后台的salt
                $password = md5( md5($password) . $salt );

                $userModel = D("User");

                // 用户是字符串 需要单引号
                // 单引号是 MySQL需要的
                $userInfo = $userModel->where("username = '$username'")->find();

                if($userInfo['password'] !== $password || $userInfo['is_active'] == 0){
                    $this->error('登录失败，请输正确信息或者激活后登录！');
                }

                $_SESSION['user_id'] = $userInfo['id'];
                $_SESSION['user_name'] = $userInfo['username'];

                  // 登录成功之后，将cookie数据保存至数据库
                $cartModel = D("Cart");
                $cartModel->moveCookieToDb();


                $returnUrl = $_SESSION['returnUrl'];
                if($returnUrl){
                    $_SESSION['returnUrl'] = null;
                    $this->success('处理成功！', $returnUrl);
                    exit();
                }else{
                     $this->success('登录成功！', U('Index/index'));
                     exit();
                }

            }

            // 获取导航栏信息，默认pid 为 0 
            $categoryModel = D("Admin/Category");
            $navData = $categoryModel->getNav();

            $this->assign('navData', $navData);
            $this->display();
        }


        /**
         * 用户退出
         */
        
        public function logout()
        {   
            //1. 方案一
            $_SESSION['user_id'] = null;
            $_SESSION['user_name'] = null;
            // 
            // 2. 方案二
            // session_destroy();

            $this->success('退出成功！', U('Index/index'));
        }
        /**
         * 用户激活
         *  当用户点击邮箱里面的激活链接的时候的操作
         */
        public function check()
        {
            $userId = base64_decode( I('get.id') );
            $userToken = I('get.token');

            $userModel = D("User");
            // 通过传递id获取用户信息
            $userInfo = $userModel->find($userId);

            if($userInfo['token'] !== $userToken){
                    exit('非法篡改信息');
            }

            if($userInfo['is_active'] == 1){
                $this->error('用户已经激活过，激活失败！');
            }else{
                // 执行用户激活状态的更改
                // 底层也会调用save方法
                // 那么在真正的更新之前，也会执行前置钩子函数
                // 那么密码会更改
                $result = $userModel->where("id = $userId")->setField('is_active', 1);
                if($result !== false){
                    $this->success('激活成功！', U('Index/index'));
                    exit();
                }else{
                    $this->error('系统繁忙！');
                }
                
            }


        }

    /**
     * 忘记密码，找回密码功能
     */
      public function findPasswod()
      {

            if(IS_POST){
                $data = I('post.data');// 获取用户名或者邮箱
                 $userModel = D("User");

                 // 用户输入的是邮箱 
                if( is_email($data) ){
                    // 用户确实存在
                     $userInfo = $userModel->where("email = '$data'")->find();
                     if($userInfo){
                            $content = "<a href='http://local.14.com/index.php/Home/User/changePassword/id/{$userInfo['id']}/token/{$userInfo['token']}'>点击找回密码</a>";
                            $result =  sendMail($data, '密码找回', $content);
                            if($result['sign'] == 1){
                                $this->success('邮件发送成功，请登录邮箱后找回密码！', U('Index/index'));
                                exit();
                            }else{
                                $this->error($result['msg']);
                            }
                     }else{
                        $this->error('输入信息有误！');
                     }

                }else{
                      $userInfo = $userModel->where("username = '$data'")->find();


                      if($userInfo['username']){
                            $content = "<a href='http://local.14.com/index.php/Home/User/changePassword/id/{$userInfo['id']}/token/{$userInfo['token']}'>点击找回密码</a>";
                            $result =  sendMail($userInfo['email'], '密码找回', $content);
                            if($result['sign'] == 1){
                                $this->success('邮件发送成功，请登录邮箱后找回密码！', U('Index/index'));
                                exit();
                            }else{
                                $this->error($result['msg']);
                            }
                     }else{
                        $this->error('输入信息有误！');
                     }

                }
            }
            $this->display();
      }

       /**
        * 邮箱修改密码
        */
      public function changePassword()
      {
        
        $userModel = D("User");

        if(IS_POST){

            // 接受表单
            if($userModel->create()){
                // 密码双重加密
                // ORM 可以为对象的属性赋值
                // 相当于给表中的字段赋值
                $salt = C("MD5_KEY");
                $userModel->password = md5( md5( I('post.password') ) . $salt );
                // save返回的是受影响的行
                // 使得用户重新更改密码的操作
                // 执行这个真正的操作之前，会执行更新的前置钩子
                // $salt = C("MD5_KEY");
                 // $data['password'] = md5( md5($data['password']) . $salt );
                if($userModel->save() !== false){
                   // 数据合法校验
                    $userId = I('post.id');
                    $token = I('post.token');
                    $userInfo = $userModel->find($userId);
                    if($userInfo['token'] !== $token){
                        exit('非法修改数据');
                    } 
                    $this->success('修改密码成功！', U('User/login'));
                    exit();
                }else{
                    $error = $userModel->getError();
                    $this->error('修改失败！');
                }
            }else{
                $error = $userModel->getError();
                $this->error('处理失败！'.$error);
            }

        }
          
          // 显示修改密码表单
          $userId = I('get.id');
          // 合法化验证
          $token = I('get.token');
          $userInfo = $userModel->find($userId);
          if($userInfo['token'] !== $token){
                exit('非法修改数据');
          }
          // 赋值需要修改的表单信息
          $this->assign('userInfo', $userInfo);
          $this->display();
      }
}

?>