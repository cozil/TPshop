<?php 
namespace Home\Model;
use Think\Model;

/**
* 购物车模型
*/
class CartModel extends Model 
{
	
	//添加商品信息到购物车
	public function addToCart($goodsId, $goodsNumber, $goodsAttrId){
		
		// 分情况对待 1. 用户登录  2. 用户未登录
		$memberId = $_SESSION['user_id'];
		// 用户登录
		if($memberId){
			// 查具体某一件商品 然后修改商品的数量 
			// 如果是通过会员ID获取 那这个会员购买的所有商品全部取出来，
			// 那是给哪一件商品增加数量？
			// 
			// 在拼接goodsAttrId的时候需要使用 单引号 包裹起来
			$has = $this->field('id')->where("goods_id = $goodsId and goods_attr_id = '$goodsAttrId' and user_id= $memberId")->find();
		

			// 当前购买的商品已经购买过, 需要将当前这件商品的数量直接
			// 增加即可
			if($has){
				// $this->where("id = $has['id']")->setInc('goods_number', $goodsNumber);
				$data['id'] = $has['id']; // array('主键ID' => $id) setDec也是如此
				$this->where($data)->setInc('goods_number', $goodsNumber);
			}else{
			// 用户没有购买过商品，直接将对应的商品信息插入到购物车表
				$data = array(
					'goods_id' => $goodsId,
					'goods_attr_id'=>$goodsAttrId,
					'goods_number'=>$goodsNumber,
					'user_id'=>$memberId
				);
				$this->add($data);
			}


		}else{
		// 用户未登录 以前有可能也购买过 那就要去判断以前是否购买过这件商品
		// 如果购买过 就直接将这个商品的数量增加到当前购买的这个数量
		// 如果没有购买过，那就直接添加一个数据到cookie里面
		// 
		// 处理完成之后，需要将cookie的数据序列化（字符串）之后，保存到cookie里面
	
			$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
			// 拼接一个数组的下标 $goods_id - $goodsAttrId
			$key = $goodsId .'-'. $goodsAttrId;
			// 代表存在 直接增加
			if(isset($cart[$key])){
				$cart[$key] += $goodsNumber;
			}else{
				// 不存在  创建
				$cart[$key] = $goodsNumber;
			}
			$time = time()+86400*30; // cookie保存时间
			setcookie('cart', serialize($cart), $time, '/');
			
		}
	}

	/**
	 * 获取购物车数据
	 * @return [array] 购物车详细信息
	 */
	public function getCartList(){
		// 用户登录
		if($memberId = $_SESSION['user_id']){
			$cart = $this->where("user_id = $memberId")->select(); // 二维数组
		
		}else{
		// 用户未登录
			$_cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array(); // 一维数组
			// 一位数组的下标是 goods_id-goods_attr_id => goods_number
			foreach ($_cart as $k => $v) {
				
				// 一位数组的下标处理  $_key[0] == goods_id $_key[1] ==goos_attr_id
				$_key = explode('-', $k);

				$cart[] = array(
					'goods_id' => $_key[0],
					'goods_attr_id' => $_key[1],
					'goods_number' => $v,
				);
			}

		}

		// 需要获取商品其他信息 名称 图片 属性
		$goodsModel = D("Admin/Goods");
		foreach ($cart as $k => $v) {
			
			$info = $goodsModel->field('goods_name,img_thumb,market_price,shop_price')->where("id = {$v['goods_id']}")->find();
			$cart[$k]['info'] = $info;

			if($v['goods_attr_id']){

				$_data = $this->convertGoodsAttrIdToString($v['goods_attr_id']);
				$cart[$k]['ga'] = $_data;

			}else{	
				// goods Attribute
				$cart[$k]['ga'] = '';
			}
			
		}

		return $cart;

	}

	/**
	 * 根据商品的属性ID，转换成商品属性字符串
	 * @param  string $goodsAttrId 商品属性ID
	 * @return string              商品属性html字符串
	 */
	public function convertGoodsAttrIdToString($goodsAttrId){
		// 实例化商品属性model
		$goodAttrMoel = D('Admin/GoodsAttr');
		$tablePrefix = C("DB_PREFIX");

		$data = $goodAttrMoel->field(" group_concat(  concat( b.attr_name, ':',a.attr_value) separator '<br/>' ) as gastring")->join("a left join " . $tablePrefix . "attribute b on a.goods_attr_id = b.id")->where("a.id in ($goodsAttrId)")->find();

		return $data['gastring'];

	}	

	// 将cookie数据移入到MySQL
	public function moveDataToDb()
	{
		if($memberId = $_SESSION['user_id']){
			$cartModel = D("Cart");

			$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
			foreach ($cart as $k => $v) {
				$_key = explode('-', $k);
				$where = " goods_id = $_key[0] and goods_attr_id = $_key[1] and user_id = $memberId";
				$has = $cartModel->where($where)->find();
				if($has){
					$cartModel->where($where)->setInc('goods_number', $v);
				}else{
					$data = array(
						'goods_id' =>$_key[0],
						'goods_attr_id'=>$_key[1],
						'goods_number'=>$v,
						'user_id'=>$memberId,
					);
					$cartModel->add($data);
				}
				
			}

			setcookie('cart', '', time()-1, '/');
		}
	}

	/**
	 * 将cookie的购物车信息保存到MySQL的cart表里
	 */
	public function moveCookieToDb()
	{	
		$memberId = $_SESSION['user_id'];
		if(!$memberId){
			$this->error('必须登录');
		}
		// 去查看cookie里面是否存在数据
		$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();

		// 存在则需要遍历数据将数据保存到MySQL的cart表中
		foreach ($cart as $k => $v) {
			
			$_key = explode('-', $k); // cookie里面存储的数据结构 goods_id-goods_attr_id 分割处理 goods_id  goods_attr_id单独获取

			// 做逻辑判断 cart里面存在数据  有可能原先有登陆的情况下购买了商品
			// 
			$where = "goods_id = $_key[0] and goods_attr_id = '$_key[1]' and user_id= $memberId";

			$has = $this->where($where)->find();
			// 原先登录的情况下购买过商品 那直接增加该商品的数量
			if($has){
				$this->where($where)->setInc('goods_number', $v);
			}else{
			// 原先登录的情况下也没有购买过商品信息，那就直接创建购买记录到cart表中
				$data = array(

					'goods_id' =>$_key[0],
					'goods_attr_id' =>$_key[1],
					'goods_number' =>$v,
					'user_id' =>$memberId,
				);
				$this->add($data);
			}

		}
		// 当数据全部保存到cart表中后，需要情况cookie里面的数据
		setcookie('cart' ,'', time()-1, '/');

	}

	// 清空购物车信息
	public function clearCart()
	{
		$memberId = $_SESSION['user_id'];
		if($memberId){
			$this->where("user_id = ".$memberId)->delete();
		}else{
			setcookie('cart', '', time()-1, '/');
		}
	}
	

}

 ?>