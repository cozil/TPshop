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
                当前位置: <a href="#">首页</a> <code>&gt;</code> 购物流程 
            </div>
        </div>
        <div class="blank"></div>

        <div class="blank"></div>
        <div class="block">
            <div class="flowBox">
                <h6><span>商品列表</span></h6>
                <form id="formCart">
                    <table cellpadding="5" cellspacing="1">
                        <tbody><tr>
                                <th>商品名称</th>
                                <th>属性</th>
                                <th>市场价</th>
                                <th>本店价</th>
                                <th>购买数量</th>
                                <th>小计</th>
                                <th>操作</th>
                            </tr>


                            <?php $tp = 0; foreach ($cartData as $k => $v): ?>
                            <tr gid="<?php echo $v['goods_id'];?>" gaid = "<?php echo $v['goods_attr_id'];?>">
                                <td align="center">
                                    <a href="<?php echo U('Index/detail', array('id'=>$v['goods_id']), false);?>" target="_blank"><img style="width: 80px; height: 80px;" src="<?php echo $viewPath . $v['info']['goods_thumb'];?>" title="P806" /></a><br />
                                    <a href="<?php echo U('Index/detail', array('id'=>$v['goods_id']), false);?>" target="_blank" class="f6"><?php echo $v['info']['goods_name']?></a>
                                </td>
                                <td><?php echo $v['ga'];?> <br />
                                </td>
                                <td align="right">￥<?php echo $v['info']['goods_price'] * 1.4;?>元</td>
                                <td align="right">￥<span> <?php echo $v['info']['goods_price'];?></span>元</td>
                                <td align="right">
                                    <img src="/Public/Home/images/jian.gif" alt="" />
                                    <input name="goods_number" id="goods_number_43" value="<?php echo $v['goods_number'];?>" size="4" class="inputBg" style="text-align: center;"  type="text" />
                                    <img src="/Public/Home/images/jia.gif" class="jia" alt="" />
                                </td>
                                <td align="right">￥ <span><?php echo $v['info']['goods_price'] * $v['goods_number']; $tp += $v['info']['goods_price'] * $v['goods_number']; ?></span> 元</td>
                                <td align="center">
                                    <a href="#" class="f6">删除</a>
                                </td>
                            </tr>
                            <?php endforeach ?>



                        </tbody></table>
                    <table cellpadding="5" cellspacing="1">
                        <tbody><tr>
                                <td>
                                    购物金额小计 ￥<?php echo $tp;?>元，比市场价 ￥<?php echo $tp*1.4;?>元 节省了 ￥<?php echo $tp*0.4;?>元               </td>
                                <td align="right">
                                    <input value="清空购物车" class="bnt_blue_1"  type="button" />
                                    <input name="submit" class="bnt_blue_1" value="更新购物车" type="submit" />
                                </td>
                            </tr>
                        </tbody></table>
                    <input name="step" value="update_cart" type="hidden" />
                </form>
                <table cellpadding="5" cellspacing="0" width="99%">
                    <tbody><tr>
                            <td><a href="/index.php"><img src="/Public/Home/images/continue.gif" alt="continue" /></a></td>
                            <td align="right"><a href="<?php echo U('Cart/order1');?>"><img src="/Public/Home/images/checkout.gif" alt="checkout" /></a></td>
                        </tr>
                    </tbody></table>
            </div>
            <div class="blank"></div>
            <div class="blank5"></div>
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
            <div class="box_1"></div>
        </div>

        <div class="blank"></div>


        <div id="bottomNav" class="box block">
            <div class="bNavList clearfix">
                <a href="#">免责条款</a>
                |
                <a href="#">隐私保护</a>
                |
                <a href="#">Powered&nbsp;by&nbsp;<strong><span style="color: rgb(51, 102, 255);">YongDa</span></strong></a>
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
    </body>
    <script type="text/javascript" src="/Public/Js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript">
    $(".jia").click(function(event) {
        // 1. 获取商品价格 2. 获取商品数量
        var _goods_price = parseFloat( $(this).parent().prev().find('span').eq(0).text() );
        var _goods_number = parseFloat( $(this).prev().val() );

        if(_goods_number>=20){
            alert('购买已经达到上限');
            return false;
        }
        var _goods_total_price = parseFloat(  $(this).parent().next().find('span').eq(0).text() );
        
         // 获取商品ID和商品属性ID
         var _goods_id = $(this).parent().parent().attr('gid');
         var _goods_attr_id = $(this).parent().parent().attr('gaid');

         var _This = this; // 当前点击的jia img这DOM元素
         $.ajax({
                 url: '/index.php/Home/Cart/ajaxCart',
                 type: 'GET',
                 dataType: 'text',
                 data:{'goods_id': _goods_id, 'goods_attr_id': _goods_attr_id},
                 error:function(data){
                     console.log(data);
                 },
                 success:function(json){
         
                    if(json == 'ok'){
                          $(_This).prev().val(_goods_number+1);
                          $(_This).parent().next().find('span').eq(0).text(_goods_total_price+_goods_price);
                    }

                 }
             });
    });

    </script>
</html>