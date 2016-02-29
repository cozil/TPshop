<?php 

namespace Home\Controller;
use Think\Controller;

class OrderController extends Controller {

	public function order1(){

		$cartModel = D("Cart");
    		$cartData = $cartModel->getCartList();
    		$this->assign('cartData', $cartData);

    		// 获取导航信息
    		$cateModel = D("Admin/Category"); 
		$navData = $cateModel->getNav();
    		$this->assign('navData', $navData);

    		// 图片显示根路径
    		$viewPath = C("VIEW_ROOT_PATH");
    		$this->assign('viewPath', $viewPath);

    	
		$this->success('处理成功！', U('order2'));
    	}


    	public function order2()
    	{
    		
    		$this->display();
    	}
}
 ?>
