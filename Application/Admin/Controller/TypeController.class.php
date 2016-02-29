<?php 
namespace Admin\Controller;
use Think\Controller;

/**
* 商品类型控制器
*/
class TypeController extends Controller
{
	// 商品类型添加
	public function add()
	{
		//2. 处理表单
		if(IS_POST){
		
			// 3. 创建商品类型模型
			$typeModel=D('Type');
		
			//4. 接受表单中的数据到模型中验证
		
			if($typeModel->create(I('post.'), 1)){
				//5. 插入数据库
				if($typeModel->add()){
					// 6. 提示成功信息，并跳转到lst页面
					$this->success('处理成功！',U('lst'));
					// 7. 终止程序，不然3秒后才跳转，会执行下面代码
					exit();
				}else{
					$this->error('处理失败！');
				}
			}
		
			//8. 失败获取失败信息
		
			$error = $typeModel->getError();
		
			//9. 提示错误信息
			$this->error($error,'',1);
		
		}
		
		//1. 显示表单
		$this->display();
		
	}

	public function lst()
	{
		
		// 1. 实例化 Type模型
		$typeModel = D('Type');

		$typeData = $typeModel->select();

		$this->assign('typeData', $typeData);

		$this->display();
	}

}

 ?>