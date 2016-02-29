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
        <div class="block block1">  
            <div class="block box">
                <div class="blank"></div>
                <div id="ur_here">
                    当前位置: <a href="/" target="_blank">首页</a> <code>&gt;</code> 用户注册 
                </div>
            </div>
            <div class="blank"></div>

            <!--放入view具体内容-->
            <div class="block box">
                <div class="usBox">
                    <div class="usBox_2 clearfix">
                        <div class=""><img src="/Public/Home/images/user_tit3.gif" alt="" /></div>

                            <form id="yw0" name="myform" action="/index.php/Home/User/register.html" method="POST">              

                                    <table cellpadding="5" cellspacing="3" style="text-align:left; width:100%; border:0;">
                                            <tbody>
                                                <!-- 用户名 -->
                                                <tr>
                                                    <td style="width:13%; text-align: right;"><label for="username" class="required">用户名 <span class="required">*</span></label>
                                                    </td>
                                                    <td style="width:87%;">
                                                        <input class="inputBg" size="25" name="username" id="username" type="text" value=""  placeholder=' username'/>                  
                                                    </td>
                                                </tr>
                                                 <!-- 邮箱 -->
                                                <tr>
                                                    <td align="right"><label for="email">邮箱</label><span class="required"> *</span></label></td>
                                                    <td>
                                                        <input class="inputBg" size="25" name="email" id="email" type="text" value="" placeholder=' gogery@163.com' />    
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td align="right"><label for="email">手机号</label><span class="required"> *</span></label></td>
                                                    <td>
                                                        <input class="inputBg" size="25" name="iphone" id="iphone" type="text" value=""  />
                                                        <input type="button" value=" 点击获取免费验证码" id="getCode">
                                                    </td>
                                                </tr>

                                                 <tr>
                                                    <td align="right"><label for="email">手机验证码</label><span class="required"> *</span></label></td>
                                                    <td>
                                                        <input class="inputBg" size="25" name="code" id="code" type="text" value=""  />
                                                        
                                                    </td>
                                                </tr>



                                                <!-- 密码 -->
                                                <tr>
                                                    <td align="right">
                                                        <label for="psd" class="required">密码 <span class="required">*</span></label>
                                                    </td>
                                                    <td>
                                                        <input class="inputBg" size="25" name="password" id="psd" type="password" value="" />         
                                                    </td>
                                                </tr>
                                                <!-- 密码确认 -->
                                                <tr>
                                                    <td align="right"><label for="psd2">密码确认</label> <span class="required">*</span></td>
                                                    <td>
                                                        <input class="inputBg" size="25" name="psd2" id="psd2" type="password" />
                                                    </td>

                                                </tr>
                                                <!-- 提交 -->
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td align="left">
                                                            <!-- <input  value="" class="us_Submit_reg" type="submit" /> -->
                                                            <img src="/Public/Home/images/bnt_ur_reg.gif" alt="提交" id="sub" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">&nbsp;</td>
                                                </tr>
                                            </tbody>
                                    </table>
                            </form>

                        </div>
                </div>
            </div>
            <!--放入view具体内容-->

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
                <a href="#">Powered&nbsp;by&nbsp;<strong><span style="color: rgb(51, 102, 255);">YongDa</span></strong></a>
                |
                <a href="#">批发方案</a>
                |
                <a href="#">配送方式</a>

            </div>

        </div>

        <div id="footer">
            <div class="text">
                © 2005-2015 YONGDA 版权所有，并保留所有权利。<br />
            </div>
        </div>
        <script type="text/javascript" src="/Public/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="/Public/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="/Public/js/validate_zh_cn.js"></script>
    </body>
    <script type="text/javascript">

        //  表单验证
        $("form[name=myform]").validate({
        rules:{
            username:{
                required:true,
                rangelength:[3,10],
            },
            email:{
                required:true,
                email:true,
            },
            password:{
                required:true,
                rangelength:[5,10]
            },
            psd2:{
                required:true,
                rangelength:[5,10],
                equalTo:"#psd"
            }
            
        }
    });
    //  提交表单
    $("#sub").click(function(event) {
        $("form[name=myform]").submit();
    });

    $("#getCode").click(function(event) {
        var _iphone = $("#iphone").val();
        $.ajax({
            
                url: '/message/SendTemplateSMS.php',
                type: 'GET',
                dataType: 'json',
                data: {'iphone':_iphone},
                error:function(data){
                    console.log(data);
                },
                success:function(json){
        
                   if(json['sign'] == 1){
                        alert('发送验证成功，请注意查收');
                        return false;
                   }else{
                        alert(json['msg']);
                        return false;
                   }
        
                }
            });
    });
</script>
</html>