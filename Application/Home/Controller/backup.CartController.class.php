<?php 
namespace Home\Controller;
use Think\Controller;
/**
 * 购物车控制器
 */
class CartController extends Controller {
	/**
	 * 购买的数据加入购物车
	 */
	public function addToCart(){
    		// 获取购物车需要保存的数据
    		// 1. goods_id 2. goods_number 3. goods_attr_id
    		$goodsId = I('post.goods_id');
    		$goodsNumber = I('post.goods_number');
    		// 获取属性ID字符串
    		$goodsAttrId = '';
    		unset($_POST['goods_id']);
    		unset($_POST['goods_number']);
    		$goodsAttrId = implode(',',$_POST);
		// 将购买商品加入购物车    		
    		$cartModel = D("Cart");
    		$cartModel->addToCart($goodsId, $goodsNumber, $goodsAttrId);

    		$this->success('处理成功！', U('cartList'));
		
    	}

            /**
             * 显示购物车列表
             */
    	public function cartList()
    	{	
    	   
                $categoryModel = D("Admin/Category");
                $navData = $categoryModel->getNav();

                $this->assign('navData', $navData);

                // 获取购物车列表数据
                $cartModel = D("Cart");
                $cartData = $cartModel->getCartList(); 

                $this->assign('cartData', $cartData);

                $viewPath = C("VIEW_ROOT_PATH");
                $this->assign('viewPath', $viewPath);
                $this->display();
    	}


        /****************ajax更新购物车*****************/
        public function ajaxCart()
        {
            // 获取商品ID和属性ID
            $goodsId = I('get.goods_id');
            $goodsAttrId = I('get.goods_attr_id');
            $memberId = $_SESSION['user_id'];

            if($memberId){
                $cartModel = D("Cart");
                $result = $cartModel->where("goods_id = $goodsId and goods_attr_id = '$goodsAttrId' and user_id = $memberId")->setInc("goods_number", 1);
                if($result !== false){
                    echo "ok";
                }else{
                    echo "no";
                }
            }else{
                $cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
                 $_key = $goodsId .'-'.$goodsAttrId;

                 if($cart[$_key]){
                     $cart[$_key] += 1;
                 }else{
                     $cart[$_key] = 1;
                 }

                 // 清空cookie数据
                $time = time()+86400*30;
                setcookie('cart', serialize($cart), $time, '/');

                echo "ok";

            }

        }

        /**
         * 点击结算中心后跳转的地址
         */
        public function order1()
        {
             $memberId = $_SESSION['user_id'];
             if(!$memberId){
                $_SESSION['returnUrl'] = U('Cart/order1');
                $this->success('处理成功！', U('User/login'));
                exit();
             }
            // 实例化商品模型的时候，一定要加上后台的分组信息
            // 获取导航栏信息，默认pid 为 0 
            $categoryModel = D("Admin/Category");
            $navData = $categoryModel->getNav();
            $this->assign('navData', $navData);
            // 获取购物车列表数据
            $cartModel = D("Cart");
            $cartData = $cartModel->getCartList(); 
            $this->assign('cartData', $cartData);

            $viewPath = C("VIEW_ROOT_PATH");
            $this->assign('viewPath', $viewPath);

             $this->display();
        }

        public function order2()
        {   
            // 收集用户信息
            $userName = I('post.username');
            $email = I('post.email');
            $address = I('post.address');
            $zcode = I('post.zcode');
            $iphone = I('post.iphone'); 

            if(!$userName || !$email || !$address || !$zcode || !$iphone){
                $this->error('信息不完整');
            }

            // 检测购物车
            $cartModel = D("Cart");
            $cartData = $cartModel->getCartList();
            if(count($cartData) <= 0 ){
                $this->error('购物车数据为空，处理失败！');
            }

            // 库存检测
            $goodsModel = D("Admin/Goods");
            $tp = 0;
            foreach ($cartData as $k => $v) {
                    $kc = $goodsModel->field('goods_number')->find($v['goods_id']);
                    if($v['goods_number'] > $kc['goods_number']){
                        $this->error('库存不够，处理失败！');
                    }
                    $tp += $v['info']['goods_price'] * $v['goods_number'];
            }

            // 生成订单
            $orderModel = D("Order");

            $data = array(
                'username' => $userName,
            );

            $id = $orderModel->add($data);
            // 订单商品表
            $orderGoodsModel = D("OrderGoods");
            if($id){
                foreach ($cartData as $k => $v) {
                        $data = array(
                            'order_id' => $id,
                            'time'=>time(),
                            'price'=>$v['info']['goods_price'],
                        );
                    $orderGoodsModel->add($data);
                    // 库存减少
                    $goodsModel->where(array('id'=>$v['goods_id']))->setDec('goods_number', $v['goods_number']);
                    
                }

                // 清空购物车
                

                //跳转支付页面
                $data = array('id'=>$id);
                $this->success('处理成功！', U('Cart/order2test', $data, false));                
               
            }

        }
        public function order2test()
        {
            include './alipay/alipayapi.php';
            $this->assign('html_text', $html_text);
            $this->display();
        }
}

 ?>