<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="Generator" content="YONGDA v1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="Keywords" content="YONGDA商城" />
        <meta name="Description" content="YONGDA商城" />
        
        <title>YONGDA商城 - Powered by YongDa</title>
        
        <link href="/Public/Home/css/style.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="/Public/Home/css/magiczoomplus.css" />
        
         <style type="text/css">
            table {border:1px solid #dddddd; border-collapse: collapse; width:99%; margin:auto;}
            td {border:1px solid #dddddd;}
            #consignee_addr {width:450px;}
        </style>
        
    </head>
    <body class="index_body">
        <div class="block clearfix" style="position: relative; height: 98px;">
            <a href="#" name="top"><img class="logo" src="/Public/Home/images/logo.gif"></a>

            <div id="topNav" class="clearfix">
                <div style="float: left;"> 
                    <font id="ECS_MEMBERZONE">
                        <div id="append_parent"></div>
                        欢迎光临本店&nbsp;
                        <?php if(!empty($_SESSION['user_id'])) echo $_SESSION['user_name'] . "&nbsp; &nbsp;<a href='".U('User/logout')."'>退出</a>" ; else echo "<a href='".U('User/login')."'>登录</a>&nbsp;&nbsp;<a href='".U('User/register')."'>注册</a>";?>
                    </font>
                </div>
                <div style="float: right;">
                    <a href="<?php echo U('Cart/cartList');?>">查看购物车</a>
                    |
                    <a href="/index.php">选购中心</a>
                    |
                    <a href="#">标签云</a>
                    |
                    <a href="#">报价单</a>
                </div>
            </div>
            <div id="mainNav" class="clearfix">
                <a href="/index.php" class="cur">首页<span></span></a>
               <?php foreach ($navData as $k => $v): ?>
                    <a href="<?php echo U('Index/category', array('cate_id'=>$v['id']), false);?>"><?php echo $v['cate_name'];?><span></span></a>
               <?php endforeach ?>

                
            </div>
        </div>

        <div class="header_bg">
            <div style="float: left; font-size: 14px; color:white; padding-left: 15px;">
            </div>  

            <form id="searchForm" method="get" action="#">
                <input name="keywords" id="keyword" type="text" />
                <input name="imageField" value=" " class="go" style="cursor: pointer; background: url('./images/sousuo.gif') no-repeat scroll 0% 0% transparent; width: 39px; height: 20px; border: medium none; float: left; margin-right: 15px; vertical-align: middle;" type="submit" />

            </form>
        </div>
        <div class="blank5"></div>
        <div class="block box">
            <div class="blank"></div>
            <div id="ur_here">
                当前位置: <a href="/index.php ">首页</a> <code>&gt;</code> 
                <?php foreach ($breadNav as $k => $v): ?>
                             <a href="<?php echo U('category', array('id'=>$v['id']), false);?>"><?php echo $v['cate_name'];?></a> <code>&gt;</code>
                 <?php endforeach ?>
                <?php echo $goodsInfo['goods_name'];?>
            </div>
        </div>
        <div class="blank"></div>

        <div class="block clearfix">
            <div class="AreaL">
                <h3><span>商品分类</span></h3> 
                <div id="category_tree" class="box_1">

                     <?php foreach ($cateData as $k => $v): ?>
                        <?php if($v['pid'] == 0) {?>
                        <dl>
                            <dt><a href="<?php echo U('category', array('cate_id'=>$v['id']), false);?>" target="_blank"><?php echo $v['cate_name']?></a></dt>
                            <dd>
                                <?php foreach ($cateData as $k1 => $v1): ?>
                                        <?php if($v1['pid'] == $v['id']) {?>
                                    <a href="<?php echo U('category', array('cate_id'=>$v['id']), false);?>"  target="_blank"><?php echo $v1['cate_name'];?></a>
                                    <?php };?>
                                <?php endforeach ?>
                            </dd>
                        </dl>
                        <?php }?>
                    <?php endforeach ?>

                </div>
                <div class="blank"></div>
                <div class="blank5"></div>
            </div>

            <div class="AreaR">
                <div id="goodsInfo" class="clearfix">
                    <div class="imgInfo">
                        <a  href="<?php echo $viewPath. $goodsInfo['goods_img'];?>"  class="MagicZoomPlus" title="<?php echo $goodsInfo['goods_name'];?>">
                            <img id="sim806035" src="<?php echo $viewPath. $goodsInfo['goods_thumb'];?>" alt="<?php echo $goodsInfo['goods_name'];?>" height="310px" width="310px;" />
                        </a>
                        <!-- 上下图 -->
                        <div class="blank5"></div>
                        <div style="text-align: center; position: relative; width: 100%;">
                            <a href="javascript:;">
                                <img style="position: absolute; left: 0pt;" alt="prev" src="/Public/Home/images/up.gif" /></a>
                            <a href="javascript:;">
                                <img alt="zoom" src="/Public/Home/images/zoom.gif" />
                            </a>
                            <a href="javascript:;">
                                <img style="position: absolute; right: 0pt;" alt="next" src="/Public/Home/images/down.gif" /></a>
                        </div>
                        <div class="blank5"></div>

                        <div class="picture" id="imglist">
                            <a   href="<?php echo $viewPath. $goodsInfo['goods_img'];?>" id="zoom1"  class="MagicZoomPlus">
                                <img src="<?php echo $viewPath . $goodsInfo['goods_thumb']?>" alt="<?php echo $goodsInfo['goods_name'];?>" class="onbg" /></a>
                        </div>
                    </div>

                    <div class="textInfo">
                        <form action="<?php echo U('Cart/addToCart')?>" method="POST" name="" id="cart_form">
                            <div class="clearfix" style="font-size: 14px; font-weight: bold; padding-bottom: 8px;">
                                <input type="hidden" name="goods_id" value="<?php echo $goodsInfo['id'];?>" id="">
                                <?php echo $goodsInfo['goods_name'];?>    
                            </div>
                            <ul>

                                <li class="clearfix">
                                    <dd>
                                        <strong>商品库存：</strong>
                                        <?php echo $goodsInfo['goods_number'];?>台             
                                    </dd>
                                </li>  
                                <li class="clearfix">
                                    <dd>
                                        <strong>商品点击数：</strong>24</dd>
                                </li>
                                <li class="clearfix">
                                    <dd>
                                        <strong>市场价格：</strong><font class="market">￥<?php echo $goodsInfo['goods_price'] * 1.4; ?>元</font><br />

                                        <strong>本店售价：</strong><font class="shop" id="ECS_SHOPPRICE">￥<?php echo $goodsInfo['goods_price'];?>元</font><br />
                                    </dd>
                                </li>
                                <li class="clearfix">
                                    <dd>
                                        <strong>用户评价：</strong>
                                        <img src="/Public/Home/images/stars5.gif" alt="comment rank 5">
                                    </dd>
                                </li>
                                <li class="clearfix">
                                    <dd>
                                        <strong>购买数量：</strong>
                                        <input name="goods_number" id="number" value="1" size="4"   style="border: 1px solid rgb(204, 204, 204);" type="text" />
                                    </dd>
                                </li>
                                <li class="clearfix">
                                    <dd>
                                        <strong>购买此商品可使用：</strong><font class="f4">2200 积分</font>
                                    </dd>
                                </li>
                                
                                <?php foreach ($radioData as $k => $v): ?>
                                    <li class="padd loop">
                                        <strong><?php echo $v[0]['attr_name'];?>:</strong>
                                        <?php foreach ($v as $k1 => $v1): ?>
                                        <label for="spec_value_227">
                                            <input name="goods_attr_<?php echo $k;?>" value="<?php echo $v1['id'];?>" id="spec_value_227" checked="checked" onclick="changePrice()" type="radio" />
                                            <?php echo $v1['goods_attr_value'];?>[ ￥0.00元] </label>
                                          <?php endforeach ?>
                                    </li>
                              <?php endforeach ?>

                                <li class="padd">
                                    <a href="javascript:;"><img src="/Public/Home/images/goumai2.gif" id="submits"></a>
                                    <a href="#"><img src="/Public/Home/images/shoucang2.gif"></a>
                                    <a href="#"><img src="/Public/Home/images/tuijian.gif"></a>
                                </li>
                            </ul>
                        </form>


                    </div>
                </div>
                <div class="blank"></div>
                <div class="box">
                    <div class="box_1">
                        <h3 style="padding: 0pt 5px;">
                            <div id="com_b" class="history clearfix">
                                <h2 style="cursor: pointer;"  id="goods_descp">商品描述：</h2>
                                <h2 style="cursor: pointer;"  id="goods_attribute">商品属性</h2>
                            </div>
                        </h3>
                        <div id="com_v"  class="boxCenterList RelaArticle">
                          <?php echo $goodsInfo['goods_descp'];?>
                        </div>
                        <div  id="com_h"  style="display:none" class="boxCenterList RelaArticle" >
                            hi! 商品属性
                        </div>
                    </div>
                </div>
                <div class="blank"></div>
                <div class="blank5"></div>
                <div class="box"></div>
                <div class="blank5"></div>
                <div id="ECS_BOUGHT"><div class="box">
                    </div>
                    <!-- 多说评论框 start -->
                    <div class="ds-thread" data-thread-key="<?php echo $goodsInfo['id']?>" data-title="<?php echo $goodsInfo['goods_name']?>" data-url="/index.php"></div>
                <!-- 多说评论框 end -->
                <!-- 多说公共JS代码 start (一个网页只需插入一次) -->
                <script type="text/javascript">
                var duoshuoQuery = {short_name:"php1414"};
                    (function() {
                        var ds = document.createElement('script');
                        ds.type = 'text/javascript';ds.async = true;
                        ds.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') + '//static.duoshuo.com/embed.js';
                        ds.charset = 'UTF-8';
                        (document.getElementsByTagName('head')[0] 
                         || document.getElementsByTagName('body')[0]).appendChild(ds);
                    })();
                    </script>
                <!-- 多说公共JS代码 end -->
                    <div class="blank5"></div>

                   </div>
            </div>

        </div>
        <div class="blank"></div>
        <div class="block">
            <div class="blank"></div>
        </div>
        <div class="block">
            <div class="box">
                <div class="helpTitBg" style="clear: both;">
                    <dl>
                        <dt><a href="#" title="新手上路 ">新手上路 </a></dt>
                        <dd><a href="#" title="售后流程">售后流程</a></dd>
                        <dd><a href="#" title="购物流程">购物流程</a></dd>
                        <dd><a href="#" title="订购方式">订购方式</a></dd>
                    </dl>
                    <dl>
                        <dt><a href="#" title="手机常识 ">手机常识 </a></dt>
                        <dd><a href="#" title="如何分辨原装电池">如何分辨原装电池</a></dd>
                        <dd><a href="#" title="如何分辨水货手机 ">如何分辨水货手机</a></dd>
                        <dd><a href="#" title="如何享受全国联保">如何享受全国联保</a></dd>
                    </dl>
                    <dl>
                        <dt><a href="#" title="配送与支付 ">配送与支付 </a></dt>
                        <dd><a href="#" title="货到付款区域">货到付款区域</a></dd>
                        <dd><a href="#" title="配送支付智能查询 ">配送支付智能查询</a></dd>
                        <dd><a href="#" title="支付方式说明">支付方式说明</a></dd>
                    </dl>
                    <dl>
                        <dt><a href="#" title="会员中心">会员中心</a></dt>
                        <dd><a href="#" title="资金管理">资金管理</a></dd>
                        <dd><a href="#" title="我的收藏">我的收藏</a></dd>
                        <dd><a href="#" title="我的订单">我的订单</a></dd>
                    </dl>
                    <dl>
                        <dt><a href="#" title="服务保证 ">服务保证 </a></dt>
                        <dd><a href="#" title="退换货原则">退换货原则</a></dd>
                        <dd><a href="#" title="售后服务保证 ">售后服务保证</a></dd>
                        <dd><a href="#" title="产品质量保证 ">产品质量保证</a></dd>
                    </dl>
                    <dl>
                        <dt><a href="#" title="联系我们 ">联系我们 </a></dt>
                        <dd><a href="#" title="网站故障报告">网站故障报告</a></dd>
                        <dd><a href="#" title="选机咨询 ">选机咨询</a></dd>
                        <dd><a href="#" title="投诉与建议 ">投诉与建议</a></dd>
                    </dl>
                </div>
            </div>


        </div>
        <div class="blank"></div>
        <div id="bottomNav" class="box block">
            <div class="box_1">
               
            </div>
        </div>
        <div class="blank"></div>
        <div id="bottomNav" class="box block">
            <div class="bNavList clearfix">
                <a href="#">免责条款</a>
                |
                <a href="#">隐私保护</a>
                |
                <a href="#">咨询热点</a>
                |
                <a href="#">联系我们</a>
                |
                <a href="#">公司简介</a>
                |
                <a href="#">批发方案</a>
                |
                <a href="#">配送方式</a>

            </div>
        </div>

        <div id="footer">
            <div class="text">
                © 2005-2012 YONGDA 版权所有，并保留所有权利。<br />
            </div>
        </div>
        <div class="MagicThumb-container" style="position: absolute; display: none; visibility: hidden;">
            <div style="font-size: 0px; height: 0px; outline: medium none; border: medium none; line-height: 0px; width: 200px; padding-left: 1px; padding-right: 1px;">
            </div>
            <div style="display: inline; overflow: hidden; visibility: visible; color:red; font-size: 12px; font-weight: bold; font-family: Tahoma; position: absolute; width: 90%; text-align: right; right: 15px; top: 242px; z-index: 10;">
            </div>
            <div class="MagicThumb-controlbar" style="position: absolute; top: -9999px; visibility: hidden; z-index: 11;">
                <a style="float: left; position: relative;" rel="close" href="#" title="Close">
                    <span style="left: -36px; cursor: pointer;"></span>
                </a>
            </div>
        </div>
        <img class="MagicThumb-image" style="position: absolute; top: -9999px; display: none;" src="/Public/Home/images/9_P_1241511871575.jpg" />
        <img src="/Public/Home/images/controlbar.htm" style="position: absolute; top: -999px;" />
        <img style="position: absolute; left: -10000px; top: -10000px;" src="/Public/Home/images/9_P_1241511871575.jpg" />
        <img style="position: absolute; left: -10000px; top: -10000px;" src="/Public/Home/images/9_P_1241511871575.jpg" />
    </body>
    <script type="text/javascript" src="/Public/Js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/Public/Home/js/magiczoomplus.js"></script>
    <script type="text/javascript">

            /**
             * 选项卡
             * 
             * 属性和详细介绍
             * 
             */
            $("#goods_descp").click(function(event) {
                $("#com_v").show();
                $("#com_h").hide();
            });

             $("#goods_attribute").click(function(event) {
                $("#com_v").hide();
                $("#com_h").show();
            });

             $("#submits").click(function(event) {
                 $("#cart_form").submit();
             });
            
    </script>
</html>