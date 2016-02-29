<?php 
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {

	public function index(){

		// 在前台实例化后台的Model的时候需要加上对应的模块名称 对应的是使用 " / "
		$cateModel = D("Admin/Category"); 

		// 获取导航信息
		$navData = $cateModel->getNav();
    		$this->assign('navData', $navData);

    		// 获取分类信息
    		$catData = $cateModel->getCate();
    		$this->assign('catData', $catData);

    		// 获取精品数据 is_best 3
    		$goodsModel = D("Admin/Goods");
    		$bestData = $goodsModel->getGoods('is_best', 3);
    		$this->assign('bestData', $bestData);

    		// 获取热卖
    		$hotData = $goodsModel->getGoods('is_hot', 3);
    		$this->assign('hotData', $hotData);

    		// 获取新品
    		$newData = $goodsModel->getGoods('is_new', 3);
    		$this->assign('newData', $newData);

                     $viewPath = C("VIEW_ROOT_PATH");
                     $this->assign('viewPath', $viewPath);

    		//载入首页
		$this->display();
    	}

        public function detail()
        {
            $goodsId = I("get.id");
            $goodsModel = D("Admin/Goods");

            $goodsData = $goodsModel->find($goodsId);
            $this->assign('goodsData', $goodsData);
             $viewPath = C("VIEW_ROOT_PATH");
             $this->assign('viewPath', $viewPath);

             //命名规范：
             // 后台命名：
             // 注意在PHP代码里面建议所有的命名方式 方法和函数和变量名使用小驼峰的方式
             // 对应的类名使用大驼峰的方式
             // 对应的常量全部使用大写方式命名
             // 
             // 
             // 前台命名：
             // js  建议大家使用_下划线的方式命名  get_name_by_id(id)
             // 
             // css 建议大家使用- 中划线的方式命名 head-container
             // 
             
             // 商品属性获取
             $goodsAttrModel = D('Admin/GoodsAttr');
             // $goodsAttrModel->方法 前缀
             // $this->tablePrefix
             // 获取表前缀
            $tablePrefix = C('DB_PREFIX');
             $goodsAttrData = $goodsAttrModel->field('a.*, b.attr_name,b.attr_type')->join("a left join ".$tablePrefix."attribute b  on a.goods_attr_id = b.id")->where("a.goods_id = $goodsId")->select();
             // var_dump($goodsAttrModel->getLastSql());die();
             // 将商品信息重组，同一个属性的值放在一个数组里
             $attrData = array();
             foreach ($goodsAttrData as $k => $v) {
                 if($v['attr_type'] == 1){
                    $attrData[$v['goods_attr_id']][] = $v;
                 }
             }
             $this->assign('attrData', $attrData);


             // 面包屑导航
             $categoryModel = D("Admin/Category");
             // 获取当前商品的分类ID，然后在根据分类ID获取对应的父分类信息
             $goodsCateId = $goodsData['cat_id'];
             $cateData = $categoryModel->getFamily($goodsCateId);
             $cateData = array_reverse($cateData); // 将数据的全部信息倒置，主要是为了数据显示方便
             $this->assign('cateData', $cateData);


            // 获取导航信息
            $navData = $categoryModel->getNav();
            $this->assign('navData', $navData);



            $this->display();
        }


        public function category()
        {
            $cateId = I('get.cat_id');

            $cateModel = D("Admin/Category");

            // 获取导航信息
            $navData = $cateModel->getNav();
            $this->assign('navData', $navData);


            // 获取分类信息
            $catData = $cateModel->getCate();
            $this->assign('catData', $catData);
            


            // 获取当前ID的子栏目ID
            $ids = $cateModel->getIds($cateId);
            $ids[] = $cateId; // 将当前栏目也追加到子栏目里

            $ids = implode(',', $ids);
            // 代表获取当前栏目以及当前栏目的子栏目的所有商品信息全部获取
            $goodsModel = D("Admin/Goods");
            $goodsData = $goodsModel->where("cat_id in ($ids)")->limit(3)->order('id desc')->select();
            $this->assign('goodsData', $goodsData);
            //显示图片的路径
            $viewPath = C("VIEW_ROOT_PATH");
            $this->assign('viewPath', $viewPath);

              // 面包屑导航
             // 获取当前商品的分类ID，然后在根据分类ID获取对应的父分类信息
             $cateData = $cateModel->getFamily($cateId);
             $cateData = array_reverse($cateData); // 将数据的全部信息倒置，主要是为了数据显示方便
             $this->assign('cateData', $cateData);


            $this->display();

        }

        public function test()
        {
            $this->display();
        }

        public function addData()
        {
            
            //2. 处理表单
            if(IS_POST){
            
                // 3. 创建商品模型
                $model=D('Data');
            
                //4. 接受表单中的数据到模型中验证
                // var_dump($model->create());die();
                if($model->create(I('post.'), 1)){
                    // var_dump($model->add());die();
                    //5. 插入数据库
                    if($model->add()){
                        var_dump($model->getLastSql());die();
                        // 6. 提示成功信息，并跳转到lst页面
                        $this->success('处理成功！',U('dlst'));
            
                        // 7. 终止程序，不然3秒后才跳转，会执行下面代码
                        exit();
                    }
                }
            
                //8. 失败获取失败信息
            
                $error = $model->getError();
            
                //9. 提示错误信息
                $this->error($error,'',1);
            
            }
            
            //1. 显示表单
            $this->display();
            

        }

        public function dlst()
        {
            
                $dataModel = M("Data");
                $data = $dataModel->select();
                header('Content-Type:text/html;charset=utf-8');
                echo "<pre>";
                var_dump($data);
        }
}

 ?>