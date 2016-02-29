<?php 
namespace Admin\Model;
use Think\Model;

/**
* addition
*/
class AttributeModel extends Model 
{
	
	
	//商品自动验证
	protected $_validate = array(
		array('attr_name', 'require', '属性名称不能为空'),
		array('type_id', 'require', '类型ID不能为空'),
		array('attr_type', array(0, 1), '属性类型只能为0  1', 1 , 'in'),
		array('attr_input_type', array(0, 1), '属性录入方式只能为0  1', 1 , 'in'),
	);

	// 允许表单提交的数据
	protected $insertFields = array('attr_name','type_id','attr_type','attr_input_type','attr_value');
	

	public function _before_insert(&$data, $options)
	{	
		// 插入前将用户输入的可选值列表的逗号转换成英文状态
		$data['attr_value'] = str_replace('，',  ',',  $data['attr_value']);
	}
}

 ?>