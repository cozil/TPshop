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
            // 图片显示根路径
            $viewPath = C("VIEW_ROOT_PATH");
            $this->assign('viewPath', $viewPath);

             $this->display();
        }


        /**
         * 订单生成
         */
        public function order2()
        {
            
            $memberId = $_SESSION['user_id'];
            if(!$memberId){
                $this->error('必须登录！');
            } 


            // 1. 接受收货人信息
            $username = I('post.username');
            $email = I('post.email');
            $iphone = I('post.iphone');
            $zcode = I('post.zcode');
            $address = I('post.address');

            if(!$username || !$email || !$iphone || !$zcode || !$address){
                $this->error('收货人信息不完整，处理失败！');
            }

            // 2. 判断购物车数据是否存在
            $cartModel = D("Cart");
            $cartData = $cartModel->getCartList();

            if(count($cartData) <= 0){
                $this->error('购物车为空，处理失败！');
            }

            // 3. 检测一下库存，并计算一下这次购买所产生的金额
            $goodsModel = D("Admin/Goods");
            $tp = 0;
            foreach ($cartData as $k => $v) {
                
                $kc = $goodsModel->field("goods_number")->where("id = {$v['goods_id']}")->find();

                if($v['goods_number'] > $kc['goods_number']){
                    $this->error('库存不足，处理失败！');
                }

                $tp += $v['info']['goods_price'] * $v['goods_number'];

            }

            // 4. 生成订单
            $data = array(
                'user_id' => $memberId,
                'username' => $username,
                'email' => $email,
                'iphone' => $iphone,
                'zcode' => $zcode,
                'address' => $address,
                'tp' => $tp,
            );

            $orderModel = D("Order");
            $id = $orderModel->add($data);
            // 订单生成成功
            if($id){
                // 1. 生成订单商品表
                $orderGoodsModel = D("OrderGoods");

                foreach ($cartData as $k => $v) {
                    
                    $data = array(
                        'order_id' =>$id,
                        'goods_id' => $v['goods_id'],
                        'goods_attr_id' => $v['goods_attr_id'],
                        'goods_number' => $v['goods_number'],
                        'goods_price' => $v['info']['goods_price'],
                    );

                    $orderGoodsModel->add($data);

                    // 需要减少库存
                    // setDec 这个链式操作的where条件必须是主键
                    $goodsModel->where(array('id' => $v['goods_id']))->setDec("goods_number", $v['goods_number']);
                }

                // 清空购物车
                $cartModel->clearData();
                
                // 跳转到支付页面
                $data = array(
                    'order_id' =>$id,
                    'username' => $username,
                    'zcode' => $zcode,
                    'iphone' => $iphone,
                    'address' => $address,
                );
                $this->success('订单处理成功！', U('Cart/order3', $data, false));

            }

        }
        
        public function order3()
        {
            // 引入阿里接口文件
            include './alipays/alipayapi.php';
            $this->assign('html_text', $html_text);
            $this->display();

        }
}

 ?>