<?php 
namespace Home\Controller;
use Think\Controller;
class CartController extends Controller {
	// 添加商品到购物车
	public function addToCart(){
		// 保存购物数据 
		// 1. cookie 2. MySQL里面
		// goodsId-goodsAttrId 
		$goodsId = I('post.goods_id');
		$goodsNumber = I('post.goods_number');

		if(count(I('post.')) > 2){

			unset($_POST['goods_id']);
			unset($_POST['goods_number']);

			$ids = implode(',', $_POST); // 属性ID是一个字符串
		}

		$cartModel = D("Cart");
		// 调用model的addToCart方法将数据保存到购物车
		$cartModel->addToCart($goodsId, $goodsNumber, $ids);

		$this->success('添加购物车成功', U('cartList'));


    	}

    	/**
    	 * 购物车列表页显示
    	 */
    	public function cartList()
    	{
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
    		$this->display();
    	}

            // 结算页面
            public function order1()
            {          
                    $memberId = $_SESSION['user_id'];

                    if(!$memberId){
                            $_SESSION['returnUrl'] = U('Cart/order1');
                            $this->success('请登录', U('User/login'));
                            exit();
                    }

                    // 取出购物车的数据
                    $cartModel = D("Cart");
                    // 获取购物车数据
                    $cartData = $cartModel->getCartList();
                    $this->assign('cartData', $cartData);

                    // 获取导航信息
                    $cateModel = D("Admin/Category"); 
                    $navData = $cateModel->getNav();
                    $this->assign('navData', $navData);

                    // 图片显示根路径
                    $viewPath = C("VIEW_ROOT_PATH");
                    $this->assign('viewPath', $viewPath);

                    $this->display();
            }

            /**
             * 收集用户信息和下订单
             */
            public function order2()
            {
                // 1. 手机用户信息
                $memberId = $_SESSION['user_id'];
                if(!$memberId){
                    $this->error('必须登录');
                }
                $userName = I('post.user_name');
                $userEmail = I('post.user_email');
                $userAddress = I('post.user_address');
                $userIphone = I('post.user_iphone');
                $userShipping = I('post.shipping');
                $userPayment = I('post.payment');

                if( !$userName || !$userEmail || !$userAddress || !$userIphone){
                        $this->error('用户信息不完整');
                }
                // 2. 检测购物车数据是否存在
                $cartModel = D("Cart");

                $goods = $cartModel->getCartList();

                if(count($goods)<= 0 ){
                    $this->error('购物车数据不存在');
                }

                
                // 3. 检测库存
                $goodsModel = D("Admin/Goods");
                $tp = 0; // 购买总价
                foreach ($goods as $k => $v) {

                        $kc = $goodsModel->field('goods_number')->where("id =". $v['goods_id'])->find();
                        if($kc['goods_number'] < $goods['goods_number']){
                            $this->error('库存不够');
                        }

                        $tp += $v['info']['shop_price'] * $v['goods_number'];
                }

                // 4. 生成订单表
                $orderModel = D("Order");
                //生成订单基本表 保存用户信息
                // 对于一次交易 只能生成一次 包含收货人的基本信息和 总价
                $data = array(
                            'user_id' =>$memberId,
                            'addtime'=>time(),
                            'user_name' =>$userName,
                            'user_email' =>$userEmail,
                            'user_address' =>$userAddress,
                            'user_iphone' =>$userIphone,
                            'total_price'=>$tp,
                        );
                // 将本次交易的全部商品信息保存到订单商品表
                $id = $orderModel->add($data);
                $orderGoodsModel = D('OrderGoods');
                // 当订单基本信息插入成功之后，将商品其他信息 插入到商品订单表
                if($id){

                    foreach ($goods as $k => $v) {
                            $data = array(
                                'order_id' =>$id,
                                'goods_id'=>$v['goods_id'],
                                'goods_attr_id' => $v['goods_attr_id'],
                                'goods_price'=>$v['info']['shop_price'],
                                'goods_number'=>$v['goods_number'],
                            );
                            $orderGoodsModel->add($data);

                            $goodsModel->where("id=". $v['goods_id'])->setDec('goods_number', $v['goods_number']);

                    }
                    // 在保存订单商品表之后，然后库存减少之后，然后跳到支付宝支付
                    $this->success('处理成功！', U('order3', array('id'=>$id), false));
                    $cartModel->clearCart();

                }else{
                    $this->error('处理失败！');
                }
                              
            }

            // 支付宝处理
            public function order3()
            {
                require_once './alipay/alipayapi.php';
            }

            /****************前台ajax调用功能*****************/
            /**
             * 前台ajax请求修改商品数量
             * @return [type] [description]
             */
    	public function update()
    	{
    		$goodsId = I('get.gid');
    		$goodsAttrId = I('get.gaid');


    		// 1. 按照购物流程来 肯定有
    		// 2. 不按照购物流程来， 直接点击购物车

    		if($memberId = $_SESSION['user_id']){
    			$cartModel = D("Cart");
    			$has = $cartModel->where("goods_id = $goodsId and goods_attr_id = '$goodsAttrId' and user_id = $memberId")->find();

    			if($has['goods_number'] >= 10 ){
    				echo 1; // 购买数量超过上限
    				exit();
    			}

    			if($has){
    				$cartModel->where("goods_id = $goodsId and goods_attr_id = '$goodsAttrId' and user_id = $memberId")->setInc('goods_number', 1);
    				echo 2; // 增加成功
    			}

    		}else{

    			$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();

    			$key = $goodsId .'-'. $goodsAttrId;


    			$cart[$key] += 1;

    			setcookie('cart', serialize($cart), time()+86400, '/');

    			echo 2; // 增加成功 
    		}

    	}


}

 ?>