<?php 
namespace Admin\Controller;
use Think\Controller;

/**
* 商品属性控制器
*/
class AttributeController extends Controller
{	
	/**
	 * 属性添加
	 */
	public function add()
	{	
		//2. 处理表单
		if(IS_POST){
		
			// 3. 创建商品模型
			$attributeModel = D('Attribute');
		
			//4. 接受表单中的数据到模型中验证
		
			if($attributeModel->create(I('post.'), 1)){
				//5. 插入数据库
				if($attributeModel->add()){
					// 6. 提示成功信息，并跳转到lst页面
					$this->success('属性添加成功！',U('lst', array('id'=>I('post.type_id')) , false));
		
					// 7. 终止程序，不然3秒后才跳转，会执行下面代码
					exit();
				}else{
					$this->error('处理失败！');
				}
			}
		
			//8. 失败获取失败信息
		
			$error = $attributeModel->getError();
		
			//9. 提示错误信息
			$this->error($error,'',1);
		
		}
		
		// 实例化 商品类型模型，获取类型数据
		$typeModel = D('Type');

		$typeData = $typeModel->select();
		$this->assign('typeData', $typeData);

		// 商品类型直接添加属性
		$typeId = I('get.id');
		if(!empty($typeId)){
			$this->assign('typeId', $typeId);
		}
		$this->display();
		

	}

	/**
	 * 商品类型对应的属性展示
	 */
	public function lst()
	{

		$typeId = I('get.id');// 商品类型ID

		$attributeModel = D('Attribute');

		$attributeData = $attributeModel->order('id asc')->where("type_id = $typeId")->select();

		$this->assign('attributeData', $attributeData);
		$this->display();
		
	}
	
}

 ?>