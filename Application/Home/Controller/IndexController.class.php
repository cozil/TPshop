<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 首页控制器
 */
class IndexController extends Controller {
    
    /**
     * 前台首页显示
     */
    public function index(){

    	// 实例化商品模型的时候，一定要加上后台的分组信息
    	// 获取导航栏信息，默认pid 为 0 
    	$categoryModel = D("Admin/Category");
    	$navData = $categoryModel->getNav();

    	$this->assign('navData', $navData);


    	/****************获取左侧分类信息*****************/
    	$cateData = $categoryModel->getTree();
    	$this->assign('cateData', $cateData);

          /****************获取热卖、精品、新品*****************/
    	// 热卖
    	$goodsModel = D("Admin/Goods");
    	$hotData = $goodsModel->getGoodsData('is_hot', 3);
    	$this->assign('hotData', $hotData);

    	// 精品
    	$bestData = $goodsModel->getGoodsData('is_best', 3);
    	$this->assign('bestData', $bestData);

    	// 新品
    	$newData = $goodsModel->getGoodsData('is_new', 3);
    	$this->assign('newData', $newData);

    	/****************获取图片显示根路径*****************/
    	$viewPath = C('VIEW_ROOT_PATH');
    	$this->assign('viewPath', $viewPath);

           // 载入模板 
    	$this->display();
    }


        /**
        * 获取商品详细信息
        */
        public function detail()
        {	
                // 获取商品id
                $goodsId = I('get.id');

                // 实例化商品模型，获取详情
                $goodsModel = D('Admin/Goods');
                $goodsInfo = $goodsModel->find($goodsId);

                // 获取导航栏信息，默认pid 为 0 
                $categoryModel = D("Admin/Category");
                $navData = $categoryModel->getNav();

                $this->assign('navData', $navData);

                /****************面包屑导航*****************/
                $breadNav = $categoryModel->getFamilys($goodsInfo['cate_id']);
                // 将面包屑导航 反向输出
                $breadNav =  array_reverse($breadNav);
                $this->assign('breadNav', $breadNav);


                /****************获取左侧分类信息*****************/
                $cateData = $categoryModel->getTree();
                $this->assign('cateData', $cateData);

                /****************获取商品的属性*****************/
                // 商品属性表里面
                $goodsAttrModel = D("GoodsAttr");
                $goodsAttrData = $goodsAttrModel->alias('a')->field('b.attr_name,b.attr_type,a.*')->join(" left join it_attribute b on a.goods_attr_id = b.id")->where("a.goods_id = $goodsId")->select();
                // 注意如果使用TP提供的链式操作无法得到正确的结果
                // 可以使用 $goodsAttrModel->getLastSql() 获取最后执行的一条sql语句来调试
                // 注意：在使用TP框架的时候 不建议自己写sql语句来执行
                // $sql = "update it_goods set goods_number = 3";
                // $goodsAttrModel->query($sql)
                // $goodsAttrModel->execute($sql)
                // 建议使用TP提供的链式操作
                // 因为自己拼接的sql语句，容易造成sql注入，TP的链式操作
                // 在底层会过滤sql注入
                // 用于处理单选属性
                $radioData = array();
                foreach ($goodsAttrData as $k => $v) {
                    if($v['attr_type'] == 1){
                        $radioData[$v['goods_attr_id']][] = $v; // 将同一个单选属性放入到一个数组里面
                    }
                }

                // 赋值单选属性
                $this->assign('radioData', $radioData);

                // 赋值显示根路径
                $viewPath = C("VIEW_ROOT_PATH");
                $this->assign('viewPath', $viewPath);

                $this->assign('goodsInfo', $goodsInfo);
                $this->display();
        }


        /**
         * 商品列表页
         */
        public function category()
        {
            $cateId = I('get.cate_id');

            // 获取当前栏目 及其子栏目的信息
            $categoryModel = D("Admin/Category");

            // 获取导航栏信息，默认pid 为 0 
            $navData = $categoryModel->getNav();

            $this->assign('navData', $navData);

            /****************面包屑导航*****************/
            $breadNav = $categoryModel->getFamilys($cateId);
             // 将面包屑导航 反向输出
            $breadNav =  array_reverse($breadNav);
            $this->assign('breadNav', $breadNav);



            // 通过当前分类id获取其子栏目id
            $ids = $categoryModel->getChild($cateId);
            $ids[] = $cateId; // 将当前栏目ID也放入栏目ID

            // 获取当前栏目及其子栏目的商品信息
            $goodsModel = D("Admin/Goods");
            $idsString = implode(',', $ids); // i string 

            $where = "cate_id in ($idsString) and is_delete = 0 ";
            $count      = $goodsModel->where($where)->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count, 8);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            
            $Page->setConfig('prev','上一页');
            $Page->setConfig('next','下一页');

            $show       = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $goodsData = $goodsModel->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

            $this->assign('page',$show);// 赋值分页输出
            $this->assign('goodsData', $goodsData);

            // 获取列表推荐商品信息
            $recomentGoodsData = $goodsModel->getGoodsData('is_best', 3, $ids);
            // shuffle() 默认是引用传值
            shuffle($recomentGoodsData);
            $this->assign('recomentGoodsData', $recomentGoodsData);


            /****************获取左侧分类信息*****************/
            $cateData = $categoryModel->getTree();
            $this->assign('cateData', $cateData);

            // 读取图片显示根路径
            $viewPath = C('VIEW_ROOT_PATH');
            $this->assign('viewPath', $viewPath);

            // 载入模板
            $this->display();

        }



}