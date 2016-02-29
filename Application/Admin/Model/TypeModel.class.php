<?php 
namespace Admin\Model;
use Think\Model;

/**
* 商品类型模型
*/
class TypeModel extends Model 
{
	
	//商品自动验证
	protected $_validate = array(
		array('type_name', 'require', '商品名称不能为空'),
	);
	

}

 ?>