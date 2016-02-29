<?php 
namespace Admin\Controller;
use Think\Controller;
/**
 * 商品栏目控制器
 * 栏目用于管理商品
 */
class CategoryController extends Controller {

	// 商品栏目添加
	public function add(){

		//2. 处理表单
		if(IS_POST){
		
			// 3. 创建商品模型
			$cateModel=D('Category');
		
			//4. 接受表单中的数据到栏目模型中使用自动验证
			if($cateModel->create(I('post.'), 1)){
				//5. 插入数据库
				if($cateModel->add()){
					// 6. 提示成功信息，并跳转到lst页面
					$this->success('添加栏目成功！',U('lst'));
					// 7. 终止程序，不然3秒后才跳转，会执行下面代码
					exit();
				}else{
					$this->error('添加栏目失败！');
				}
			}
		
			//8. 失败获取失败信息
			$error = $cateModel->getError();
			//9. 提示错误信息
			$this->error($error,'',1);
		
		}

		//1. 显示表单
		$cateModel = D("Category");
		$cateData = $cateModel->getTree();

		$this->assign('cateData', $cateData);
		$this->display();
		
		
	  }

	  /**
	   * 商品栏目展示
	   */
	  public function lst()
	  {
	  	$cateModel = D('Category');
	  	$cateData = $cateModel->getTree();

	  	$this->assign('cateData', $cateData);
	  	$this->display();
	  }

}

 ?>