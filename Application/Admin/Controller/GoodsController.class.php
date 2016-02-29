<?php 
namespace Admin\Controller;
use Think\Controller;
/**
* 商品控制器
*/
class GoodsController extends Controller
{
	
	/**
	 * 商品添加
	 */
	public function add()
	{

		//2. 处理表单
		if(IS_POST){
			// 3. 创建商品模型
			$goodsModel=D('Goods');
		
			//4. 使用商品模型的自动验证来过滤表单数据
			if($goodsModel->create(I('post.'), 1)){
				//5. 插入数据库
				if($goodsModel->add()){
					// 6. 提示成功信息，并跳转到lst页面
					$this->success('处理成功！',U('lst'));
					// 7. 终止程序，不然3秒后才跳转，会执行下面代码
					exit();
				}else{
					// 如果此处出错，使用 mysql_error() 调试
					$this->error('处理失败！');
				}
			}
		
			//8. 从商品模型获取验证错误信息
			$error = $goodsModel->getError();
			//9. 提示错误信息
			$this->error($error,'',1);
		
		}
		// 2. 获取栏目
		$cateModel = D('Category');
		$cateData = $cateModel->getTree();
		$this->assign('cateData', $cateData);
		
		//3. 获取商品类型
		$typeModel = D('Type');

		$typeData = $typeModel->select();
		$this->assign('typeData', $typeData);
		
		// 载入模板		
		$this->display();
	}

	/**
	 * 商品列表展示
	 */
	public function lst()
	{	
		// 代码重用
		$data = $this->search();

		$this->assign('goodsData', $data['goods']);
		$this->assign('page', $data['page']);

		// 2. 获取栏目
		$cateModel = D('Category');
		$cateData = $cateModel->getTree();
		$this->assign('cateData', $cateData);

		// 载入模板
		$this->display(); 
	}

	// 查找 分页 排序
	public function search()
	{	

		$where = " 1 AND is_delete = 0 ";

		$sp = I('get.sp'); // 商品起始价
		$ep = I('get.ep'); // 商品结束价

		$gn = I('get.gn'); // 商品名称

		$ob = I('get.ob'); // 获取排序字段 id shop_price
		$ow = I('get.ow'); // 获取排序方式 desc asc

		if($sp){
			$where .= " AND shop_price >= $sp";
		}

		if($ep){
			$where .= " AND shop_price <= $ep";
		}
		// 商品名称模糊查询
		if($gn){
			$where .= " AND goods_name like '%$gn%' ";
		}

		//默认排序规则
		$ordeby = 'id';
		$orderway = 'desc';

		if($ob){
			$ordeby= $ob;//  默认是按照 id
		}

		if($ow){
			$orderway = $ow; // 默认是按照 asc
		}

		// 分页
		$goodsModel = D('Goods'); // 实例化模型
		$count   = $goodsModel->where($where)->order("$ordeby $orderway ")->count();// 获取满足条件总记录数
		// 实例化分页类 传入总记录数和每页显示的记录数 5 条
		$Page       = new \Think\Page($count, 5);
		
		// 动态根据前台页面的结构定义各自的类名
		$Page->setConfig('prev', '上一页'); // 分页上一页修改
		$Page->setConfig('next', '下一页'); // 分页下一页修改

		$show       = $Page->show();// 生成分页数据字符串
		// 进行分页数据查询
		$list = $goodsModel->where($where)->order("$ordeby $orderway ")->limit($Page->firstRow.','.$Page->listRows)->select();

		// 分页数据和分页字符串
		return  array(
			'goods' => $list,
			'page' =>$show,
		);
	}

	/**
	 * 商品详情编辑页面
	 */
	public function edt()
	{	
		$goodsModel = D('Goods');
		// 商品详情编辑
		if(IS_POST){
			
			if($goodsModel->create(I('post.', 2))){
				if($goodsModel->save() !== false){
					$this->success('修改成功！', U('lst/', array('p'=>I('get.p'), false)));
					exit();
				}
			}
			$this->error($model->getError());

		}
		// 1. 获取商品具体信息
		$goodsId = I('get.id');

		$info = $goodsModel->find($goodsId);
		$this->assign('info', $info);

		// 2. 获取栏目
		$cateModel = D('Category');
		$cateData = $cateModel->getTree();
		$this->assign('cateData', $cateData);

		//3. 获取商品类型
		$typeModel = D('Type');
		$typeData = $typeModel->select();
		$this->assign('typeData', $typeData);
		
		$this->display();
	}

	/**
	 * 记录伪删除
	 */
	public function del()
	{
		$goodsId = I('get.id'); // 获取传递ID
		$goodsModel = D('Goods');// 实例化模型

		// 伪删除
		$data['is_delete'] = 1;
		if($goodsModel->where("id = $goodsId")->save($data) !== false){
			$this->success('处理成功！', U('lst?p'.I('get.p')));
			exit();
		}
		$this->error($model->getError());
	}

	/**
	 * 商品详情
	 */
	public function det()
	{
		$goodsId = I('get.id'); // 获取传递ID
		$goodsModel = D('Goods');

		$info = $goodsModel->find($goodsId);
		$this->assign('info', $info);

		// 图片显示根路径
		$viewPath = C("VIEW_ROOT_PATH");
		$this->assign('viewPath', $viewPath);

		$this->display();
	}
	
	/**
	 * 商品回收站
	 */
	public function recovery()
	{	
		$goodsModel = D('Goods');

		if(IS_GET && (I('get.id')>0)){
			$goodsId = I('get.id');
			

			$data['is_delete'] = 0;
			if($goodsModel->where("id = $goodsId")->save($data) !== false){
				$this->success('还原成功！', U('lst'));
				exit();
			}
			$this->error($goodsModel->getError());
		}

		//伪删除的商品
		$info = $goodsModel->where('is_delete =1')->select();
		$this->assign('info', $info);
		// 载入模板
		$this->display();
	}
	
	/**
	 * 物理行删除
	 */
	public function rdel()
	{
		$goodsId = I('get.id'); // 获取传递ID

		$goodsModel = D('Goods');// 实例化模型

		if($goodsModel->delete($goodsId) !== false){
			$this->success('物理行删除成功！', U('lst'));
			exit();
		}
		$this->error($goodsModel->getError());
	}
	
	/**
	 * 前台类型获取属性信息
	 * @return json 对应type_id下的属性信息
	 */
	public function showattr()
	{	

		$typeId = I('get.type_id'); // 获取类型ID
		$goodsId = I('get.goods_id'); // 获取商品ID

		$attrModel = D('Attribute');//根据类型ID获取对应的属性信息
		$attrInfo = $attrModel->where("type_id = $typeId")->select();

		// 获取商品对应的属性值
		$goodAttr = D('GoodsAttr');
		$goodsInfo = $goodAttr->field("a.*,b.attr_name")->join(" a left join it_attribute b on a.goods_attr_id = b.id")->where("goods_id = $goodsId")->select();
		foreach ($attrInfo as $key => $value) {
			foreach ($goodsInfo as $k => $v) {
				// 获取商品的属性值
				// 商品属性表的属性id和属性表的id关联
				if($value['id'] == $v['goods_attr_id']){

					$attrInfo[$key]['val'] = $v['goods_attr_value'];
				}
			}
		}
		echo json_encode($attrInfo); // 编码输出
		exit();

	}
}

?>