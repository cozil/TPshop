<?php 
namespace Home\Model;
use Think\Model;

/**
* 购物车模型
*/
class CartModel extends Model 
{
	/**
	 * 将数据加入购物车
	 * @param inter $goodsId     商品ID
	 * @param inter $goodsNumber 商品购买数量
	 * @param string $goodsAttrId 商品属性ID字符串
	 */
	public function addToCart($goodsId, $goodsNumber, $goodsAttrId = '')
	{
		$memberId = $_SESSION['user_id'];
		// 登录 保存数据库
		if($memberId){
			// 1. 判断此前是否已经加入过该商品到购物车
			// 如果以前加入购物车过，直接增加数量
			// 如果以前没有加入购物车过，直接创建新记录
			if($goodsAttrId != ''){
				$has = $this->field('id')->where("goods_id = $goodsId and goods_attr_id = '$goodsAttrId' and user_id = $memberId")->find();	
			}else{
				$has = $this->field('id')->where("goods_id = $goodsId and user_id = $memberId")->find();	
			}
			// 判断商品信息是否以前有购买
			if($has){
				$this->where(array('id'=>$has['id']))->setInc('goods_number', $goodsNumber);
			}else{
				// 此前未购买
				$data = array(
					'goods_id' => $goodsId,
					'goods_attr_id' => $goodsAttrId,
					'goods_number' => $goodsNumber,
					'user_id' => $memberId,
				);
				$this->add($data);
			}
		}else{

			// 2. 未登录 cookie保存
			$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();

			// 拼接下标 商品ID-商品属性ID
			$_key = $goodsId.'-'.$goodsAttrId;
			// 此前已经加入购物车
			if($cart[$_key]){
				$cart[$_key] += $goodsNumber;
			}else{
				// 此前未加入购物车
				$cart[$_key] = $goodsNumber;
			}
			$time = time()+86400*30; // 保存一个月
			// 第四个参数代表整个网站cookie全部生效
			setcookie('cart', serialize($cart), $time, '/');

		}
	}


	/****************获取购物车数据*****************/
	public function getCartList()
	{
		// 1. cookie 2. MySQL
		$memberId = $_SESSION['user_id'];

		$cartData = array();
		if($memberId){
			// 取出一个二维数据，每一维是一个商品记录	
			$cartData = $this->where("user_id = $memberId")->select();
		}else{	
			// 取出是一个一维数组，每一个单元是一条记录
			$_cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
			// 将数据处理成二维数组
			$cartData = array();
			foreach ($_cart as $k => $v) {
				// 对下标处理 $goodsId-$goodsAttrId
				$_key = explode('-', $k);
				$cartData[] = array(
					'goods_id' => $_key[0],
					'goods_attr_id' => $_key[1],
					'goods_number' => $v,
				);
			}
		}
		$goodsModel = D("Admin/Goods");
		// 获取商品其他信息
		foreach ($cartData as $k => $v) {
			$goodsId = $v['goods_id'];
			$data = $goodsModel->field('goods_name,goods_thumb,goods_price')->where("id = {$v['goods_id']}")->find();	
			$cartData[$k]['info']=$data;

			if($v['goods_attr_id'] != ''){
				$cartData[$k]['ga'] = $this->convertGoodsAttrIdToString($v['goods_attr_id']);
			}else{
				// goods attribute
				$cartData[$k]['ga'] = '';
			}
		}

		return $cartData;
	}
	
	


	/**
	 * 更加商品的属性ID组成一个商品属性的字符串使用'<br/>'作为分割符
	 * @param  string $goodsAttrId 商品属性ID字符串
	 * @return string              使用"<br/>"分割的属性字符串
	 */
	private function convertGoodsAttrIdToString($goodsAttrId)
	{	
		// 如果调用这个方法的时候，已经默认是由goodsid信息的
		$goodsAttrModel = D("Admin/GoodsAttr");

		$goodsAttrData = $goodsAttrModel->alias("a")->field(' group_concat(  concat(  b.attr_name,":",a.goods_attr_value)  SEPARATOR "<br>") ga'  )->join(" left join it_attribute b on a.goods_attr_id = b.id")->where("a.id in ($goodsAttrId)")->find();

		return $goodsAttrData['ga'];

	}
	
	/**
	 * 在登录成功之后，将cookie的数据保存到数据库
	 */
	public function moveCookieToDb()
	{
		$memberId = $_SESSION['user_id'];
		if($memberId){
			$cookieCartData = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();

			foreach ($cookieCartData as $k => $v) {
				
				$_key = explode('-', $k);

				$has = $this->where("goods_id = {$_key[0]} and goods_attr_id = '{$_key[1]}' and user_id = {$memberId}")->find();
				// 原先cart表里有数据
				if($has){
					$this->where("goods_id = {$_key[0]} and goods_attr_id = '{$_key[1]}' and user_id = {$memberId}")->setInc('goods_number', $v);
				}else{
					$data = array(
						'goods_id'=> $_key[0],
						'goods_attr_id'=> $_key[1],
						'goods_number' => $v,
						'user_id' => $memberId,
					);
					$this->add($data);	
				}

				
			}
			// 成功保存至数据库之后清空cookie
			setcookie('cart', '', time()-1, '/');
		}
	}


	/****************清空购物车*****************/	

	public function clearData()
	{
		$memberId = $_SESSION['user_id'];
		// 登录情况下清空购物车
		if($memberId){
			
			$this->where("user_id = $memberId")->delete();
		}else{
			// 未登录情况下
			setcookie('cart', '', time() - 1, '/');
		}
	}

}

 ?>