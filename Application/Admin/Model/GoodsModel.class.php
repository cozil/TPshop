<?php 
namespace Admin\Model;
use Think\Model;

/**
* 商品模型
*/
class GoodsModel extends Model
{
	// 允许提交的合法字段
	// protected $insertFields = array('goods_name','goods_number','shop_price','img_ori','img_sam','market_price','is_sale','goods_desc');
	// 允许更新的合法字段
	// protected $updateFields = array('id', 'goods_name','goods_number','shop_price','img_ori','img_sam','market_price','is_sale','is_delete','goods_desc');
	
	// 定义验证规则
	// 第一个参数：表单里面的字段
	// 第二个参数：验证规则 require TP系统自带的验证规则
	// 第三个参数：提示信息
	// 第四个参数：验证条件 0 字段存在就验证 1 必须验证
	// 第五个参数：附加规则  怎么样方式验证 regex 正则  in在某个范围
	// 第六个参数：验证时间 新增数据时候验证 或者插入时候验证
	protected $_validate = array(

		array('goods_name', 'require', '商品名称不能为空'),

		array('goods_number', 'require', '商品数量不能为空'),
		array('goods_number', 'number', '商品数量必须为数字'),

		array('goods_price','require', '商品本店价格不能为空'),
		array('goods_price','currency', '商品本店价必须是数字（例如：12 或 12.12）'),

		array('is_sale',array(0,1), '商品是否上架，只能为0、1', 1, 'in'),
	);



	/****************获取前台 热卖、精品、新品数据*****************/
	/**
	 * 获取前台数据
	 * @param  string  $type   热卖 精品 新品
	 * @param  integer $number 商品数量
	 * @param array  $cateID 栏目ID
	 * @return array          符合条件的二维数组
	 */
	public function getGoodsData($type, $number = 3, $cateId = Null)
	{	

		if($type == 'is_best' || $type == 'is_new' || $type == 'is_hot'){
			$where = " $type = 1 and is_delete = 0";
			// 当是列表页的时候，获取列表页的推荐商品
			if(!empty($catId)){
				$where .= "  and cate_id in ($catId)";
			}
			$data = $this->where($where)->order('id desc')->limit($number)->select();
			return $data;
		}
	}

	// 商品图片前置钩子操作
	protected function _before_insert(&$data, $options){
		// 通用性
		$rootPath = C('ROOT_PATH');// 网站图片上传根路径 configure

		$uploadMaxFileSize = (int)C('UPLOAD_MAX_FILESIZE'); // 字符串 上传类文件大小
		$iniMaxSize = (int)ini_get('upload_max_filesize');// 获取php.ini配置文件单个文件上传大小

		$allowMaxSize = min($uploadMaxFileSize, $iniMaxSize); // 获取允许上传文件大小

		$allowExts = C('UPLOAD_ALLOW_EXTS');

		$config = array(    
			'maxSize'    =>    $allowMaxSize * 1024 * 1024,    // 上传图片的大小B
			'rootPath'   =>     $rootPath,    
			'savePath'   =>    'Goods/',    
			'exts'       =>      $allowExts,    
		);
		$upload = new \Think\Upload($config);// 实例化上传类
		$info = $upload->upload();

		if($info){

			$imgOri = $info['goods_img']['savepath'].$info['goods_img']['savename'];

			$image = new \Think\Image(); //实例化对象  注意：在路径拼接的过程之中一定要注意 /
			$image->open($rootPath. $imgOri);// 按照原图的比例生成一个最大为50*50的缩略图并保存
			$thumbName = $info['goods_img']['savepath'] . 'sam_' . $info['goods_img']['savename']; // 缩略图的路径
			$image->thumb(50, 50, 1)->save($rootPath . $thumbName);


			// 怎么保存到数据库 下标对应的是表中字段
			$data['goods_img'] = $imgOri;
			$data['goods_thumb'] = $thumbName;

			
		}else{

			$this->error = $upload->getError(); // 错误的信息赋值给model的error
		}

		
		// $return = uploadOneImage('img_ori','Goods',array(
		// 	array(50, 50, 1),
		// ));
		
		// if($return['ok'] == 1){
		// 	$data['img_ori'] = $return['images'][0];
		// 	$data['img_sam'] = $return['images'][1];
		// }
	}
	
	/**
	 * 
	 * 商品插入后的后置钩子函数，主要完成商品属性的录入
	 * @param  引用传值 &$data   
	 * @param  where后面的条件 $options 更新 $optionp['where']['id'] 代表要操作的商品ID
	 * @return 无
	 */
	public function _after_insert(&$data, $options)
	{
	
		$goodsId = $data['id'];// 在商品成功添加后

		$attr = I('post.goodsattr'); // 商品属性值 数组的下标代表属性ID

		foreach ($attr as $k => $v) {
			
			// 代表属性是 单选属性 
			if(is_array($v)){

				foreach ($v as $k1 => $v1) {
					
					$arr = array(
						'goods_id' =>$goodsId,
						'goods_attr_id' => $k, // 这就是属性ID里需要注意，是$k 最外层数据的下标
						'goods_attr_value' =>$v1, // 属性值
					);

					$model = D('goodsAttr'); // 插入商品属性表
					$model->add($arr);
				}

			}else{

				// 代表唯一属性
				// 
				$arr = array(
					'goods_id' => $goodsId, // 商品主键ID
					'goods_attr_id' => $k, // 属性ID
					'goods_attr_value' => $v, // 属性值
				);

				$model = D('goodsAttr');
				$model->add($arr);


			}
		}

	}
	// 商品修改后
	public function _after_update(&$data, $options)
	{
	
		$goodsId = $options['where']['id'];// 在商品成功添加后

		$attr = I('post.goodsattr'); // 商品属性值 数组的下标代表属性ID

		foreach ($attr as $k => $v) {
			
			// 代表属性是 单选属性 
			if(is_array($v)){

				foreach ($v as $k1 => $v1) {
					
					$arr = array(
						'goods_attr_value' =>$v1, // 属性值
					);

					$model = D('goodsAttr'); // 更新商品属性表
					$model->where("goods_id = $goodsId AND goods_attr_id = $k")->save($arr);
				}

			}else{

				// 代表唯一属性
				// 
				$arr = array(
					'goods_attr_id' => $k, // 属性ID
					'goods_attr_value' => $v, // 属性值
				);

				$model = D('goodsAttr'); // 更新商品属性表
				$model->where("goods_id = $goodsId AND goods_attr_id = $k")->save($arr);

			}
		}

	}


	// 商品修改操作
	protected function _before_update(&$data, $options){

		if($_FILES['goods_img'] !== Null &&  $_FILES['goods_img']['error'] == 0){

			$return = uploadOneImage('goods_img','Goods', array(
				array(50, 50 , 1),
			));


			if($return['ok'] == 0){
				$this->error = $return['error'];
				return false;
			}else{
				$data['goods_img'] = $return['images'][0];
				$data['goods_thumb'] = $return['images'][1];
			}

			$img = $this->field('goods_img,goods_thumb')->find($options['where']['id']);
			deleteImage($img);
		}
	}
	

	// 商品删除
	public function _before_delete($options)
	{
		$img = $this->field('goods_img,goods_thumb')->find($options['where']['id']);

		deleteImage($img);
	}

}

 ?>