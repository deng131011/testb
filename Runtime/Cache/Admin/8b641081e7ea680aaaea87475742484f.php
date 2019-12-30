<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>欢迎使用博思格后台管理系统</title>
    <link href="/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/base.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/common.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/module.css">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/Admin/css/<?php echo (C("COLOR_STYLE")); ?>.css" media="all">
     <!--[if lt IE 9]>
    <script type="text/javascript" src="/Public/static/jquery-1.10.2.min.js"></script>
    <![endif]--><!--[if gte IE 9]><!-->
    <script type="text/javascript" src="/Public/static/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/jquery.mousewheel.js"></script>
	
	<script src="/Public/static/layer/layer.js"></script>
    <!--<![endif]-->
    
</head>
<body>
    <!-- 头部 -->
    <div class="header">
        <!-- Logo -->
        <span class="logo"></span>
        <!-- /Logo -->

        <!-- 主导航 -->
        <ul class="main-nav">
            <?php if(is_array($__MENU__["main"])): $k = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($k % 2 );++$k;?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>">
                <?php if((getgroupid($mid) == 2) AND ($k == 2)): ?><a href="/Admin/persun/index.html"><?php echo ($menu["title"]); ?></a></li>
	            <?php elseif((getgroupid($mid) == 5) AND ($k == 2)): ?>
	                <a href="/Admin/questions/index.html"><?php echo ($menu["title"]); ?></a></li>
                <?php else: ?>
	                <a href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <!-- /主导航 -->

        <!-- 用户栏 -->
        <div class="user-bar">
            <a href="javascript:;" class="user-entrance"><i class="icon-user"></i></a>
            <ul class="nav-list user-menu hidden">
                <li class="manager">你好，<em title="<?php echo session('user_auth.username');?>"><?php echo session('user_auth.username');?></em></li>
                <li><a href="<?php echo U('User/updatePassword');?>">修改密码</a></li>
                <li><a href="<?php echo U('User/updateNickname');?>">修改昵称</a></li>
                <li><a href="<?php echo U('Public/logout');?>">退出</a></li>
            </ul>
        </div>
    </div>
    <!-- /头部 -->

    <!-- 边栏 -->
    <div class="sidebar">
        <!-- 子导航 -->
        
            <div id="subnav" class="subnav">
                <?php if(!empty($_extra_menu)): ?>
                    <?php echo extra_menu($_extra_menu,$__MENU__); endif; ?>
                <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
                    <?php if(!empty($sub_menu)): if(!empty($key)): ?><h3><i class="icon icon-unfold"></i><?php echo ($key); ?></h3><?php endif; ?>
                        <ul class="side-sub-menu">
                            <?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
                                    <a class="item" href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul><?php endif; ?>
                    <!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        
        <!-- /子导航 -->
    </div>
    <!-- /边栏 -->

    <!-- 内容区 -->
    <div id="main-content">
        <div id="top-alert" class="fixed alert alert-error" style="display: none;">
            <button class="close fixed" style="margin-top: 4px;">&times;</button>
            <div class="alert-content">这是内容</div>
        </div>
        <div id="main" class="main">
            
            <!-- nav -->
            <?php if(!empty($_show_nav)): ?><div class="breadcrumb">
                <span>您的位置:</span>
                <?php $i = '1'; ?>
                <?php if(is_array($_nav)): foreach($_nav as $k=>$v): if($i == count($_nav)): ?><span><?php echo ($v); ?></span>
                    <?php else: ?>
                    <span><a href="<?php echo ($k); ?>"><?php echo ($v); ?></a>&gt;</span><?php endif; ?>
                    <?php $i = $i+1; endforeach; endif; ?>
            </div><?php endif; ?>
            <!-- nav -->
            

            
	<script type="text/javascript" src="/Public/static/uploadify/jquery.uploadify.min.js"></script>
	<script type="text/javascript" src="/Public/static/getcity.js"></script>
	

    <div class="main-title">
        <h2>添加项目</h2>
    </div>
    <ul class="tab-nav nav">


        <li class="current"><a href="javascript:void(0)">基本信息</a></li>
        <!--<li><a href="javascript:void(0)">选择颜色</a></li>-->
        <li><a href="javascript:void(0)">选择产品</a></li>
        <li><a href="javascript:void(0)">项目地址</a></li>
        <li><a href="javascript:void(0)">项目内容</a></li>
        <!--<li><a href="javascript:void(0)">项目规格</a></li>-->

    </ul>
    
    <form action="<?php echo U();?>" method="post" class="form-horizontal">
    <div class="tab-content">
        <div class="form-item">
            <label class="item-label">项目名称<span class="check-tips">（项目名称必须填写）</span></label>
            <div class="controls">
                <input type="text" class="text input-large" name="title" value="<?php echo ($vo["title"]); ?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">项目英文标题<span class="check-tips"></span></label>
            <div class="controls">
                <input type="text" class="text input-large" name="egtitle" value="<?php echo ($vo["egtitle"]); ?>">
            </div>
        </div>
        <!--
		<div class="form-item">
            <label class="item-label">项目分类<span class="check-tips"></span></label>
            <div class="controls">
                <select name="type_id" style="width:200px;">
					<?php if(is_array($brands)): $i = 0; $__LIST__ = $brands;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$brand): $mod = ($i % 2 );++$i;?><option value="<?php echo ($brand["id"]); ?>" <?php echo ($vo['brand']==$brand['id']?'selected':''); ?>><?php echo ($brand["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
            </div>
        </div>
       
        <div class="form-item">
            <label class="item-label"><span style="background:#2d7200; padding:3px 10px; color:#fff; cursor:pointer;">选择颜色</span></label>
            <div class="controls">
                
            </div>
        </div> -->
		
		<div class="form-item">
            <label class="item-label">所属年份<span class="check-tips"></span></label>
            <div class="controls">
                <select name="years" class="my_sel" id="my_sel" style="width:200px;">
					
				</select>
            </div>
        </div>
		
		
		
		<div class="form-item">
            <label class="item-label">所属行业<span class="check-tips"></span></label>
            <div class="controls">
                <select name="hy_id" style="width:200px;">
					<?php if(is_array($hangyeList)): $i = 0; $__LIST__ = $hangyeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$hylist): $mod = ($i % 2 );++$i;?><option value="<?php echo ($hylist["id"]); ?>"><?php echo ($hylist["title"]); ?></option>
                      
                        <?php if(is_array($hylist[erji])): $i = 0; $__LIST__ = $hylist[erji];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$hyt): $mod = ($i % 2 );++$i;?><option value="<?php echo ($hyt["id"]); ?>">|--<?php echo ($hyt["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
				</select>
            </div>
        </div>

        <div class="form-item">
            <label class="item-label">所属产品<span class="check-tips"></span></label>
            <div class="controls">
               <?php if(is_array($cpList)): $i = 0; $__LIST__ = $cpList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cp): $mod = ($i % 2 );++$i;?><label class="checkbox">
                    <input type="checkbox" name="cp_id[]" value="<?php echo ($cp[id]); ?>"  /><?php echo ($cp[title]); ?>
                    </label><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>

        <div class="form-item">
            <label class="item-label">所属规模<span class="check-tips"></span></label>
            <div class="controls">
                <input type="text" name="size" class="text input-small" value="<?php echo ($vo["size"]); ?>">（㎡）
            </div>
        </div>
		
		
        
        <div class="form-item">
            <label class="item-label">
                项目排序<span class="check-tips">（项目排序）</span>
            </label>
            <div class="controls">
                <input type="text" name="sort" class="text input-small" value="<?php echo ((isset($vo["sort"]) && ($vo["sort"] !== ""))?($vo["sort"]):0); ?>">
            </div>
            </div>
			
			
			<div class="form-item">
            <label class="item-label">
                项目状态<span class="check-tips">（项目是否启用）</span>
            </label>
            <div class="controls">
                <label class="inline radio"><input type="radio" name="status" value="1" checked>启用</label>
                <label class="inline radio"><input type="radio" name="status" value="0">禁用</label>
            </div>
            </div>
			
			
			<div class="form-item">
            <div class="controls">
                <label class="item-label">项目缩略图</label>
                <input type="file" id="upload_picture">
                <input type="hidden" name="icon" id="icon" value="<?php echo ((isset($vo['icon']) && ($vo['icon'] !== ""))?($vo['icon']):''); ?>"/>
                <div class="upload-img-box">
                <?php if(!empty($vo['icon'])): ?><div class="upload-pre-item"><img src="<?php echo (get_cover($vo["icon"],'path')); ?>"/></div><?php endif; ?>
                </div>
            </div>
            <script type="text/javascript">
            //上传图片
            /* 初始化上传插件 */
            $("#upload_picture").uploadify({
                "height"          : 30,
                "swf"             : "/Public/static/uploadify/uploadify.swf",
                "fileObjName"     : "download",
                "buttonText"      : "上传图片",
                "uploader"        : "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
                "width"           : 120,
                'removeTimeout'	  : 1,
                'fileTypeExts'	  : '*.jpg; *.png; *.gif;',
                "onUploadSuccess" : uploadPicture,
                'onFallback' : function() {
                    alert('未检测到兼容版本的Flash.');
                }
            });
            function uploadPicture(file, data){
                var data = $.parseJSON(data);
                var src = '';
                if(data.status){
                    $("#icon").val(data.id);
                    src = data.url || '' + data.path;
                    $("#icon").parent().find('.upload-img-box').html(
                        '<div class="upload-pre-item"><img src="' + src + '"/></div>'
                    );
                } else {
                    updateAlert(data.info);
                    setTimeout(function(){
                        $('#top-alert').find('button').click();
                        $(that).removeClass('disabled').prop('disabled',false);
                    },1500);
                }
            }
            </script>
        </div>
		

       
        <!--
        <div class="form-item">
            <label class="item-label">下架时间<span class="check-tips"></span></label>
            <div class="controls">
                <input type="text" class="text input-large" name="shelftime" value="<?php echo (time_format($vo["shelftime"])); ?>">
            </div>
        </div>
		-->
		
       
        
        <div class="form-item" style="margin-top: 20px; overflow:hidden;">
            <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
            <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
        </div>
    </div>
	<!--
	<div class="tab-content" style="display:none;">
	   <style>
	       .colors{border:1px solid gray; padding:2px 10px; border-radius:2px; cursor:pointer; margin-left:0; margin-right:15px; margin-bottom:10px;}
		   .form-horizontal .controls label + label{margin-left:0;}
		   .colors .color_list{display:none;}
		   .color_on{background:#4bbd00; color:#fff;}
	   </style>
    
	
	    <?php if(is_array($ertl)): $i = 0; $__LIST__ = $ertl;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ert): $mod = ($i % 2 );++$i;?><div class="form-item">
            <label class="item-label"><?php echo ($ert[title]); ?></label>
            <div class="controls">
			<?php if(is_array($ert[erji])): $i = 0; $__LIST__ = $ert[erji];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$er): $mod = ($i % 2 );++$i;?><label class="checkbox colors">
                    <input type="checkbox" class="color_list" name="color[]" value="<?php echo ($er[id]); ?>" /><?php echo ($er[title]); ?>
                </label><?php endforeach; endif; else: echo "" ;endif; ?>	
            </div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
    	
        <div class="form-item">
            <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
            <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
        </div>
    
    </div>
     -->
	<!--产品--> 
	 <div class="tab-content" style="display:none;">
	   <style>
	     
		   
	   </style>
    
	
	    <?php if(is_array($ertler)): $i = 0; $__LIST__ = $ertler;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ert): $mod = ($i % 2 );++$i;?><div class="form-item">
            <label class="item-label"><?php echo ($ert[title]); ?></label>
            <div class="controls">
			<?php if(is_array($ert[erji])): $i = 0; $__LIST__ = $ert[erji];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$er): $mod = ($i % 2 );++$i;?><label class="checkbox colors ">
                    <input type="checkbox" class="color_list" name="color[]" value="<?php echo ($er[id]); ?>" /><?php echo ($er[title]); ?>
                </label><?php endforeach; endif; else: echo "" ;endif; ?>	
            </div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
    	
        <div class="form-item">
            <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
            <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
        </div>
    
    </div>
	
	
        <div class="tab-content" style="display:none;">
		<style>
		  #city_id select{width:200px;}
		  #province_id select{width:200px;}
		</style>
		<div class="form-item" style="overflow: hidden;">
            <label class="item-label" style="float: left; width:65px;">地区：<span class="check-tips"></span></label>
            <div class="controls" style="margin-top:-3px;">
			    <div id="province_id" style="width:200px; float:left;">
					<select name="province_id" style="width:200px;">
					    <option value="0">请选择省份</option>
						<?php if(is_array($province)): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$provc): $mod = ($i % 2 );++$i;?><option value="<?php echo ($provc["id"]); ?>"><?php echo ($provc["addr"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</div>
				<div id="city_id" style="width:200px;float:left;">
				
				</div>
				<div id="county_id" style="width:200px; float:left;">
				
				</div>
				
            </div>
        </div>
		
		
		

        
<script src="/Public/Admin/mapapi/js/jquery-1.9.1.min.js"></script>
<script src="/Public/Admin/mapapi/js/jquery-ui-1.10.4.min.js"></script>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<style type="text/css">

#mainer {
    height: 553px;
    width: 912px;
    border-top: 0px;
}

#tooles {
    height: 30px;
    background: #5688CB;
    position: relative;
    z-index: 100;
    color:white;
	width:904px;
}

#bside_left {
    width: 260px;
    height: 510px;
    padding: 10px 0px 10px 10px;
    float: left;
    overflow: auto;
}

#cur_city, #no_value {
    height: 20px;
    position: absolute;
    top: 3px;
    left: 10px;
}

#cur_city .change_city {
    margin-left: 5px;
    cursor: pointer;
}


#level {
    margin-left: 20px;
}


.logo_img {
    width: 172px;
    height: 23px;
}

.search {
    width: 280px;
    height: 53px;
    padding-left: 10px;
    float: left;
    margin-left: 15px;
    margin-right: 30px;
}

.search_t {
    width: 200px;
    height: 18px;

    padding: 3px;
    margin-top: 13px;
    line-height: 20px;
}

.search_c {
    width: 220px;
    height: 40px;
    float: left;
}

.btn1, .btn_active {
    width: 49px;
    height: 24px;
    line-height: 24px;
    font-size: 14px;
    float: left;
    background: url(./img/btn.png);
    margin-top: 14px;
    text-align: center;
    cursor: pointer;
}

.btn_active {
    background: url(./img/btn.png) -49px 0px;
}

.poi {
    height: 41;
    padding-top: 12px;
    float: left;
    position: relative;
}

.poi_note {
    line-height: 26px;
    float: left;
}

#poi_cur {
    width: 300px;
    height: 22px;
    margin-right: 10px;
    margin-top: 3px;
    line-height: 26px;
    text-align: center;
}

#addr_cur {
    width: 440px;
    height: 22px;
    margin-right: 5px;
    margin-top: 3px;
    line-height: 26px;
    text-align: center;
}

.btn_copy {
    width: 49px;
    height: 24px;
    background: url(../img/btn_cpoy.png) 0px 0px;
    float: left;
}

.already {
    width: 52px;
    line-height: 26px;
    padding-left: 5px;
    float: left;
    color: red;
    display: none;
}

.info_list {
    margin-bottom: 5px;
    cleat: both;
    cursor: pointer;
}

#txt_pannel {
    height: 500px;
}

#city {
    width: 356px;
    height: 433px;
    padding: 10px;
    border: 2px solid #D6D6D6;
    position: absolute;
    left: 44px;
    top: 20px;
    z-index: 999;
    background: #fff;
    overflow: auto;
    color: black;
}

#city .city_class {
    font-size: 12px;
    background: #fff;
}

#city .city_container {
    margin-top: 10px;
    margin-bottom: 10px;
    background: #fff;
}

#city .city_container_left {
    width: 48px;
    float: left;
}

#city .city_container_right {
    width: 289px;
    float: left;
}

#city .close {
    width: 20px;
    height: 20px;
    display: inline-block;
    float: right;
    font-size: 20px;
    font-weight: normal;
    cursor: pointer;
}

#city .city_name {
    line-height: 20px;
    margin-left: 5px;
    color: #2F82C4;
    cursor: pointer;
    display: inline-block;
    font-size: 12px;
}

#curCity {
    margin-right: 5px;
}

.hide {
    display: none;
}

#bside_rgiht {
    width: 900px;
    height: 450px;
    float: left;
    font-size: 12px;
}

#container {
    width: 900px;
    height: 450px;
    border: 2px solid gray;
}

#no_value{
    color:red;
    position: relative;
    width:200px;
}
</style>



<div style="height:53px;" class="form-item">
    
    <div class="poi">
        <div class="poi_note">当前坐标：<input type="text" name="coord" value="<?php echo ($vo[coord]); ?>" id="poi_cur" /></div>
        
        <div class="poi_note">当前地址： <input type="text" name="addr_map" value="<?php echo ($vo[addr_map]); ?>" id="addr_cur" /></div>
       
    </div>
	
</div>

<script>
	
	$('input[name=addr_map]').blur(function(){
		
		var address = $(this).val();
		
		if(address != ''){
			
			$.post("/index.php/Admin/Ajax/addr_address",{address:address},function(data){
				
				if(data != ''){
					
					$('input[name=coord]').val(data);
					
				}
				
			});
			
		}
		
	});
	
</script>

<div id="mainer" class="form-item">
    <div id="tooles">
        <div id="cur_city">
            <strong>北京市</strong><span class="change_city">[<span style="text-decoration:underline;">更换城市</span>]<span id="level">当前缩放等级：10</span></span>
                <div id="city" class="hide">
                    <h3 class="city_class">热门城市<span class="close">X</span></h3>
                    <div class="city_container">
                        <span class="city_name">北京</span>
                        <span class="city_name">深圳</span>
                        <span class="city_name">上海</span>
                        <span class="city_name">香港</span>
                        <span class="city_name">澳门</span>
                        <span class="city_name">广州</span>
                        <span class="city_name">天津</span>
                        <span class="city_name">重庆</span>
                        <span class="city_name">杭州</span>
                        <span class="city_name">成都</span>
                        <span class="city_name">武汉</span>
                        <span class="city_name">青岛</span>
                    </div>
                    <h3 class="city_class">全国城市</h3>
                    <div class="city_container">
                        <div class="city_container_left">直辖市</div>
                        <div class="city_container_right">
                            <span class="city_name">北京</span>
                            <span class="city_name">上海</span>
                            <span class="city_name">天津</span>
                            <span class="city_name">重庆</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">内蒙古</span></div>
                        <div class="city_container_right">
                            <span class="city_name">呼和浩特</span>
                            <span class="city_name">包头</span>
                            <span class="city_name">乌海</span>
                            <span class="city_name">赤峰</span>
                            <span class="city_name">通辽</span>
                            <span class="city_name">鄂尔多斯</span>
                            <span class="city_name">呼伦贝尔</span>
                            <span class="city_name">巴彦淖尔</span>
                            <span class="city_name">乌兰察布</span>
                            <span class="city_name">兴安盟</span>
                            <span class="city_name">锡林郭勒盟</span>
                            <span class="city_name">阿拉善盟</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">山西</span></div>
                        <div class="city_container_right">
                            <span class="city_name">太原</span>
                            <span class="city_name">大同</span>
                            <span class="city_name">阳泉</span>
                            <span class="city_name">长治</span>
                            <span class="city_name">晋城</span>
                            <span class="city_name">朔州</span>
                            <span class="city_name">晋中</span>
                            <span class="city_name">运城</span>
                            <span class="city_name">忻州</span>
                            <span class="city_name">临汾</span>
                            <span class="city_name">吕梁</span>

                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">陕西</span></div>
                        <div class="city_container_right">
                            <span class="city_name">西安</span>
                            <span class="city_name">铜川</span>
                            <span class="city_name">宝鸡</span>
                            <span class="city_name">咸阳</span>
                            <span class="city_name">渭南</span>
                            <span class="city_name">延安</span>
                            <span class="city_name">汉中</span>
                            <span class="city_name">榆林</span>
                            <span class="city_name">安康</span>
                            <span class="city_name">商洛</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">河北</span></div>
                        <div class="city_container_right">
                            <span class="city_name">石家庄</span>
                            <span class="city_name">唐山</span>
                            <span class="city_name">秦皇岛</span>
                            <span class="city_name">邯郸</span>
                            <span class="city_name">邢台</span>
                            <span class="city_name">保定</span>
                            <span class="city_name">张家口</span>
                            <span class="city_name">承德</span>
                            <span class="city_name">沧州</span>
                            <span class="city_name">廊坊</span>
                            <span class="city_name">衡水</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">辽宁</span></div>
                        <div class="city_container_right">
                            <span class="city_name">沈阳</span>
                            <span class="city_name">大连</span>
                            <span class="city_name">鞍山</span>
                            <span class="city_name">抚顺</span>
                            <span class="city_name">本溪</span>
                            <span class="city_name">丹东</span>
                            <span class="city_name">锦州</span>
                            <span class="city_name">营口</span>
                            <span class="city_name">阜新</span>
                            <span class="city_name">辽阳</span>
                            <span class="city_name">盘锦</span>
                            <span class="city_name">铁岭</span>
                            <span class="city_name">朝阳</span>
                            <span class="city_name">葫芦岛</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">吉林</span></div>
                        <div class="city_container_right">
                            <span class="city_name">长春</span>
                            <span class="city_name">吉林</span>
                            <span class="city_name">四平</span>
                            <span class="city_name">辽源</span>
                            <span class="city_name">通化</span>
                            <span class="city_name">白山</span>
                            <span class="city_name">松原</span>
                            <span class="city_name">白城</span>
                            <span class="city_name">延边</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">黑龙江</span></div>
                        <div class="city_container_right">
                            <span class="city_name">哈尔滨</span>
                            <span class="city_name">齐齐哈尔</span>
                            <span class="city_name">鸡西</span>
                            <span class="city_name">鹤岗</span>
                            <span class="city_name">双鸭山</span>
                            <span class="city_name">大庆</span>
                            <span class="city_name">伊春</span>
                            <span class="city_name">牡丹江</span>
                            <span class="city_name">佳木斯</span>
                            <span class="city_name">七台河</span>
                            <span class="city_name">黑河</span>
                            <span class="city_name">绥化</span>
                            <span class="city_name">大兴安岭</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">江苏</span></div>
                        <div class="city_container_right">
                            <span class="city_name">南京</span>
                            <span class="city_name">无锡</span>
                            <span class="city_name">徐州</span>
                            <span class="city_name">常州</span>
                            <span class="city_name">苏州</span>
                            <span class="city_name">南通</span>
                            <span class="city_name">连云港</span>
                            <span class="city_name">淮安</span>
                            <span class="city_name">盐城</span>
                            <span class="city_name">扬州</span>
                            <span class="city_name">镇江</span>
                            <span class="city_name">泰州</span>
                            <span class="city_name">宿迁</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">安徽</span></div>
                        <div class="city_container_right">
                            <span class="city_name">合肥</span>
                            <span class="city_name">蚌埠</span>
                            <span class="city_name">芜湖</span>
                            <span class="city_name">淮南</span>
                            <span class="city_name">马鞍山</span>
                            <span class="city_name">淮北</span>
                            <span class="city_name">铜陵</span>
                            <span class="city_name">安庆</span>
                            <span class="city_name">黄山</span>
                            <span class="city_name">阜阳</span>
                            <span class="city_name">宿州</span>
                            <span class="city_name">滁州</span>
                            <span class="city_name">六安</span>
                            <span class="city_name">宣城</span>
                            <span class="city_name">池州</span>
                            <span class="city_name">亳州</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">山东</span></div>
                        <div class="city_container_right">
                            <span class="city_name">济南</span>
                            <span class="city_name">青岛</span>
                            <span class="city_name">淄博</span>
                            <span class="city_name">枣庄</span>
                            <span class="city_name">东营</span>
                            <span class="city_name">潍坊</span>
                            <span class="city_name">烟台</span>
                            <span class="city_name">威海</span>
                            <span class="city_name">济宁</span>
                            <span class="city_name">泰安</span>
                            <span class="city_name">日照</span>
                            <span class="city_name">莱芜</span>
                            <span class="city_name">临沂</span>
                            <span class="city_name">德州</span>
                            <span class="city_name">聊城</span>
                            <span class="city_name">滨州</span>
                            <span class="city_name">菏泽</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">浙江</span></div>
                        <div class="city_container_right">
                            <span class="city_name">杭州</span>
                            <span class="city_name">宁波</span>
                            <span class="city_name">温州</span>
                            <span class="city_name">嘉兴</span>
                            <span class="city_name">绍兴</span>
                            <span class="city_name">金华</span>
                            <span class="city_name">衢州</span>
                            <span class="city_name">舟山</span>
                            <span class="city_name">台州</span>
                            <span class="city_name">丽水</span>
                            <span class="city_name">湖州</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">江西</span></div>
                        <div class="city_container_right">
                            <span class="city_name">南昌</span>
                            <span class="city_name">景德镇</span>
                            <span class="city_name">萍乡</span>
                            <span class="city_name">九江</span>
                            <span class="city_name">新余</span>
                            <span class="city_name">鹰潭</span>
                            <span class="city_name">赣州</span>
                            <span class="city_name">吉安</span>
                            <span class="city_name">宜春</span>
                            <span class="city_name">抚州</span>
                            <span class="city_name">上饶</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">福建</span></div>
                        <div class="city_container_right">
                            <span class="city_name">福州</span>
                            <span class="city_name">厦门</span>
                            <span class="city_name">莆田</span>
                            <span class="city_name">三明</span>
                            <span class="city_name">泉州</span>
                            <span class="city_name">漳州</span>
                            <span class="city_name">南平</span>
                            <span class="city_name">龙岩</span>
                            <span class="city_name">宁德</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">湖南</span></div>
                        <div class="city_container_right">
                            <span class="city_name">长沙</span>
                            <span class="city_name">株洲</span>
                            <span class="city_name">湘潭</span>
                            <span class="city_name">衡阳</span>
                            <span class="city_name">邵阳</span>
                            <span class="city_name">岳阳</span>
                            <span class="city_name">常德</span>
                            <span class="city_name">张家界</span>
                            <span class="city_name">益阳</span>
                            <span class="city_name">郴州</span>
                            <span class="city_name">永州</span>
                            <span class="city_name">怀化</span>
                            <span class="city_name">娄底</span>
                            <span class="city_name">湘西</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">湖北</span></div>
                        <div class="city_container_right">
                            <span class="city_name">武汉</span>
                            <span class="city_name">黄石</span>
                            <span class="city_name">襄樊</span>
                            <span class="city_name">十堰</span>
                            <span class="city_name">宜昌</span>
                            <span class="city_name">荆门</span>
                            <span class="city_name">鄂州</span>
                            <span class="city_name">孝感</span>
                            <span class="city_name">荆州</span>
                            <span class="city_name">黄冈</span>
                            <span class="city_name">咸宁</span>
                            <span class="city_name">随州</span>
                            <span class="city_name">恩施</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">河南</span></div>
                        <div class="city_container_right">
                            <span class="city_name">郑州</span>
                            <span class="city_name">开封</span>
                            <span class="city_name">洛阳</span>
                            <span class="city_name">平顶山</span>
                            <span class="city_name">焦作</span>
                            <span class="city_name">鹤壁</span>
                            <span class="city_name">新乡</span>
                            <span class="city_name">安阳</span>
                            <span class="city_name">濮阳</span>
                            <span class="city_name">许昌</span>
                            <span class="city_name">漯河</span>
                            <span class="city_name">三门峡</span>
                            <span class="city_name">南阳</span>
                            <span class="city_name">商丘</span>
                            <span class="city_name">信阳</span>
                            <span class="city_name">周口</span>
                            <span class="city_name">驻马店</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">海南</span></div>
                        <div class="city_container_right">
                            <span class="city_name">海口</span>
                            <span class="city_name">三亚</span>
                            <span class="city_name">三沙</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">广东</span></div>
                        <div class="city_container_right">
                            <span class="city_name">广州</span>
                            <span class="city_name">深圳</span>
                            <span class="city_name">珠海</span>
                            <span class="city_name">汕头</span>
                            <span class="city_name">韶关</span>
                            <span class="city_name">佛山</span>
                            <span class="city_name">江门</span>
                            <span class="city_name">湛江</span>
                            <span class="city_name">茂名</span>
                            <span class="city_name">东沙群岛</span>
                            <span class="city_name">肇庆</span>
                            <span class="city_name">惠州</span>
                            <span class="city_name">梅州</span>
                            <span class="city_name">汕尾</span>
                            <span class="city_name">河源</span>
                            <span class="city_name">阳江</span>
                            <span class="city_name">清远</span>
                            <span class="city_name">东莞</span>
                            <span class="city_name">中山</span>
                            <span class="city_name">潮州</span>
                            <span class="city_name">揭阳</span>
                            <span class="city_name">云浮</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">广西</span></div>
                        <div class="city_container_right">
                            <span class="city_name">南宁</span>
                            <span class="city_name">柳州</span>
                            <span class="city_name">桂林</span>
                            <span class="city_name">梧州</span>
                            <span class="city_name">北海</span>
                            <span class="city_name">防城港</span>
                            <span class="city_name">钦州</span>
                            <span class="city_name">贵港</span>
                            <span class="city_name">玉林</span>
                            <span class="city_name">百色</span>
                            <span class="city_name">贺州</span>
                            <span class="city_name">河池</span>
                            <span class="city_name">来宾</span>
                            <span class="city_name">崇左</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">贵州</span></div>
                        <div class="city_container_right">
                            <span class="city_name">贵阳</span>
                            <span class="city_name">遵义</span>
                            <span class="city_name">安顺</span>
                            <span class="city_name">铜仁</span>
                            <span class="city_name">毕节</span>
                            <span class="city_name">六盘水</span>
                            <span class="city_name">黔西南</span>
                            <span class="city_name">黔东南</span>
                            <span class="city_name">黔南</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">四川</span></div>
                        <div class="city_container_right">
                            <span class="city_name">成都</span>
                            <span class="city_name">自贡</span>
                            <span class="city_name">攀枝花</span>
                            <span class="city_name">泸州</span>
                            <span class="city_name">德阳</span>
                            <span class="city_name">绵阳</span>
                            <span class="city_name">广元</span>
                            <span class="city_name">遂宁</span>
                            <span class="city_name">内江</span>
                            <span class="city_name">乐山</span>
                            <span class="city_name">南充</span>
                            <span class="city_name">宜宾</span>
                            <span class="city_name">广安</span>
                            <span class="city_name">达州</span>
                            <span class="city_name">眉山</span>
                            <span class="city_name">雅安</span>
                            <span class="city_name">巴中</span>
                            <span class="city_name">资阳</span>
                            <span class="city_name">阿坝</span>
                            <span class="city_name">甘孜</span>
                            <span class="city_name">凉山</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">云南</span></div>
                        <div class="city_container_right">
                            <span class="city_name">昆明</span>
                            <span class="city_name">保山</span>
                            <span class="city_name">昭通</span>
                            <span class="city_name">丽江</span>
                            <span class="city_name">普洱</span>
                            <span class="city_name">临沧</span>
                            <span class="city_name">曲靖</span>
                            <span class="city_name">玉溪</span>
                            <span class="city_name">文山</span>
                            <span class="city_name">西双版纳</span>
                            <span class="city_name">楚雄</span>
                            <span class="city_name">红河</span>
                            <span class="city_name">德宏</span>
                            <span class="city_name">大理</span>
                            <span class="city_name">怒江</span>
                            <span class="city_name">迪庆</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">甘肃</span></div>
                        <div class="city_container_right">
                            <span class="city_name">兰州</span>
                            <span class="city_name">嘉峪关</span>
                            <span class="city_name">金昌</span>
                            <span class="city_name">白银</span>
                            <span class="city_name">天水</span>
                            <span class="city_name">酒泉</span>
                            <span class="city_name">张掖</span>
                            <span class="city_name">武威</span>
                            <span class="city_name">定西</span>
                            <span class="city_name">陇南</span>
                            <span class="city_name">平凉</span>
                            <span class="city_name">庆阳</span>
                            <span class="city_name">临夏</span>
                            <span class="city_name">甘南</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">宁夏</span></div>
                        <div class="city_container_right">
                            <span class="city_name">银川</span>
                            <span class="city_name">石嘴山</span>
                            <span class="city_name">吴忠</span>
                            <span class="city_name">固原</span>
                            <span class="city_name">中卫</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">青海</span></div>
                        <div class="city_container_right">
                            <span class="city_name">西宁</span>
                            <span class="city_name">玉树</span>
                            <span class="city_name">果洛</span>
                            <span class="city_name">海东</span>
                            <span class="city_name">海西</span>
                            <span class="city_name">黄南</span>
                            <span class="city_name">海北</span>
                            <span class="city_name">海南</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">西藏</span></div>
                        <div class="city_container_right">
                            <span class="city_name">拉萨</span>
                            <span class="city_name">那曲</span>
                            <span class="city_name">昌都</span>
                            <span class="city_name">山南</span>
                            <span class="city_name">日喀则</span>
                            <span class="city_name">阿里</span>
                            <span class="city_name">林芝</span>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <div class="city_container">
                        <div class="city_container_left"><span class="style_color">新疆</span></div>
                        <div class="city_container_right">
                            <span class="city_name">乌鲁木齐</span>
                            <span class="city_name">克拉玛依</span>
                            <span class="city_name">吐鲁番</span>
                            <span class="city_name">哈密</span>
                            <span class="city_name">博尔塔拉</span>
                            <span class="city_name">巴音郭楞</span>
                            <span class="city_name">克孜勒苏</span>
                            <span class="city_name">和田</span>
                            <span class="city_name">阿克苏</span>
                            <span class="city_name">喀什</span>
                            <span class="city_name">塔城</span>
                            <span class="city_name">伊犁</span>
                            <span class="city_name">昌吉</span>
                            <span class="city_name">阿勒泰</span>
                        </div>
</div>
<div style="clear:both"></div>
</div>
        </div>

    </div>
    
    <div id="bside_rgiht">
        <div id="container"></div>
    </div>
</div>



<script type="text/javascript">


<!-- $(".change_city").click(function(){ -->

 <!-- $("#city").show(); -->

<!-- }); -->

<!-- $(".close").click(function(){ -->

 <!-- $("#city").hide(); -->

<!-- }); -->


var container = document.getElementById("container");
var map = new qq.maps.Map(container, {
            zoom: 10

        }),
    label = new qq.maps.Label({
         map: map,
         offset: new qq.maps.Size(15,-12),
         draggable: false,
         clickable: false
    }),
    markerArray = [],
    curCity = document.getElementById("cur_city"),
    //btnSearch = document.getElementById("btn_search"),
    bside = document.getElementById("bside_left"),
    url, query_city,
    cityservice = new qq.maps.CityService({
        complete: function (result) {
            curCity.children[0].innerHTML = result.detail.name;
            map.setCenter(result.detail.latLng);
        }
    });
cityservice.searchLocalCity();
map.setOptions({
    draggableCursor: "crosshair"
});

$(container).mouseenter(function () {
    label.setMap(map);
});
$(container).mouseleave(function () {
    label.setMap(null);
});

qq.maps.event.addListener(map, "mousemove", function (e) {
    var latlng = e.latLng;
    label.setPosition(latlng);
    label.setContent(latlng.getLat().toFixed(6) + "," + latlng.getLng().toFixed(6));
});

var url3;
qq.maps.event.addListener(map, "click", function (e) {
    document.getElementById("poi_cur").value = e.latLng.getLat().toFixed(6) + "," + e.latLng.getLng().toFixed(6);
    url3 = encodeURI("http://apis.map.qq.com/ws/geocoder/v1/?location=" + e.latLng.getLat() + "," + e.latLng.getLng() + "&key=PAZBZ-LUI3U-7I3VV-4RTTQ-GTX52-ZQFR5&output=jsonp&&callback=?");
    $.getJSON(url3, function (result) {
	   
        if(result.result!=undefined){
            document.getElementById("addr_cur").value = result.result.address;
        }else{
            document.getElementById("addr_cur").value = "";
        }

    })
});

qq.maps.event.addListener(map, "zoom_changed", function () {
    document.getElementById("level").innerHTML = "当前缩放等级：" + map.getZoom();
});
var listener_arr = [];
var isNoValue = false;


function setAnchor(marker, flag) {
    var left = marker.index * 27;
    if (flag == true) {
        var anchor = new qq.maps.Point(10, 30),
                origin = new qq.maps.Point(left, 0),
                size = new qq.maps.Size(27, 33),
                icon = new qq.maps.MarkerImage("./img/marker10.png", size, origin, anchor);
        marker.setIcon(icon);
    } else {
        var anchor = new qq.maps.Point(10, 30),
                origin = new qq.maps.Point(left, 35),
                size = new qq.maps.Size(27, 33),
                icon = new qq.maps.MarkerImage("./img/marker10.png", size, origin, anchor);
        marker.setIcon(icon);
    }
}
function setCurrent(arr, index, isMarker) {
    if (isMarker) {
        each(markerArray, function (n, ele) {
            if (n == index) {
                setAnchor(ele, false);
                ele.setZIndex(10);
            } else {
                if (!ele.isClicked) {
                    setAnchor(ele, true);
                    ele.setZIndex(9);
                }
            }
        });
    } else {
        each(markerArray, function (n, ele) {
            if (n == index) {
                ele.div.style.background = "#DBE4F2";
            } else {
                if (!ele.div.isClicked) {
                    ele.div.style.background = "#fff";
                }
            }
        });
    }
}
function setFlagClicked(arr, index) {
    each(markerArray, function (n, ele) {
        if (n == index) {
            ele.isClicked = true;
            ele.div.isClicked = true;
            var str = '<div style="width:250px;">' + ele.div.children[1].innerHTML.toString() + '</div>';
            var latLng = ele.getPosition();
            document.getElementById("poi_cur").value = latLng.getLat().toFixed(6) + "," + latLng.getLng().toFixed(6);
        } else {
            ele.isClicked = false;
            ele.div.isClicked = false;
        }
    });
}
var city = document.getElementById("city");

curCity.onclick = function (e) {

    var e = e || window.event,
            target = e.target || e.srcElement;
    if (target.innerHTML == "更换城市") {
	    
        city.style.display = "block";
		console.log('更换城市');
        if(isNoValue){
            bside.innerHTML = "";
            each(markerArray, function (n, ele) {
                ele.setMap(null);
            });
            markerArray.length = 0;
        }

    }
};

var url2;
city.onclick = function (e) {
    var e = e || window.event,
            target = e.target || e.srcElement;
    if (target.className == "close") {
        city.style.display = "none";
    }
    if (target.className == "city_name") {
            curCity.children[0].innerHTML = target.innerHTML;
        
        url2 = encodeURI("http://apis.map.qq.com/ws/geocoder/v1/?region=" + curCity.children[0].innerHTML + "&address=" + curCity.children[0].innerHTML + "&key=PAZBZ-LUI3U-7I3VV-4RTTQ-GTX52-ZQFR5&output=jsonp&&callback=?");
		console.log(url2);
        $.getJSON(url2, function (result) {
            map.setCenter(new qq.maps.LatLng(result.result.location.lat, result.result.location.lng));
            map.setZoom(10);
        });
        city.style.display = "none";
    }
};

var url4;
$(".search_t").autocomplete({
    source:function(request,response){
        url4 = encodeURI("http://apis.map.qq.com/ws/place/v1/suggestion/?keyword=" + request.term + "&region=" + curCity.children[0].innerHTML + "&key=PAZBZ-LUI3U-7I3VV-4RTTQ-GTX52-ZQFR5&output=jsonp&&callback=?");
        $.getJSON(url4,function(result){

            response($.map(result.data,function(item){
                return({
                    label:item.title

                })
            }));
        });
    }
});

function each(obj, fn) {
    for (var n = 0, l = obj.length; n < l; n++) {
        fn.call(obj[n], n, obj[n]);
    }
}

$("#suremap").click(function(){

   var poi_cur = $("#poi_cur").val();
   var addr_cur = $("#addr_cur").val();
   
});




</script>
	
		
		
		
            
			
			
		
			
		<div class="form-item" style="margin-top: 20px;">
			<button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
			<button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
		</div>

        </div>
    
	<div class="tab-content" style="display:none;">
    
    	<div class="form-item">
            <label class="item-label">来实® 系统</label>
            <div class="controls">
                <label class="textarea input-large">
                    <textarea name="description"><?php echo ($vo["description"]); ?></textarea>
                </label>
            </div>
        </div>
    
    	<div class="form-item">
            <label class="item-label">项目内容</label>
            <div class="controls">
                <label class="textarea">
                    <textarea name="content"><?php echo ($vo["content"]); ?></textarea>
                    <?php echo hook('adminArticleEdit', array('name'=>content,'value'=>$vo['content']));?>
                </label>
            </div>
        </div>
        <div class="form-item">
            <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
            <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
        </div>
    
    </div>
   
    
    </form>

        </div>
        <div class="cont-ft">
            <div class="copyright">
                <div class="fl">感谢使用<a href="/" target="_blank">博思格</a>管理平台</div>
                <!--<div class="fr">V<?php echo (ONETHINK_VERSION); ?></div>-->
            </div>
        </div>
    </div>
	
	<!--判断是否有新信息-->
	<input type="hidden" id="if_group" value="<?php echo ($groups); ?>"/>
	<input type="hidden" id="httssession" value="<?php echo ($httssession); ?>"/>
	
	
    <!-- /内容区 -->
    <script type="text/javascript">
    (function(){
        var ThinkPHP = window.Think = {
            "ROOT"   : "", //当前网站地址
            "APP"    : "/index.php?s=", //当前项目地址
            "PUBLIC" : "/Public", //项目公共目录地址
            "DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
            "MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
        }
    })();
    </script>
    <script type="text/javascript" src="/Public/static/think.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/common.js"></script>
    <script type="text/javascript">
        +function(){
            var $window = $(window), $subnav = $("#subnav"), url;
            $window.resize(function(){
                $("#main").css("min-height", $window.height() - 130);
            }).resize();

            /* 左边菜单高亮 */
            url = window.location.pathname + window.location.search;
            url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
            $subnav.find("a[href='" + url + "']").parent().addClass("current");

            /* 左边菜单显示收起 */
            $("#subnav").on("click", "h3", function(){
                var $this = $(this);
                $this.find(".icon").toggleClass("icon-fold");
                $this.next().slideToggle("fast").siblings(".side-sub-menu:visible").
                      prev("h3").find("i").addClass("icon-fold").end().end().hide();
            });

            $("#subnav h3 a").click(function(e){e.stopPropagation()});

            /* 头部管理员菜单 */
            $(".user-bar").mouseenter(function(){
                var userMenu = $(this).children(".user-menu ");
                userMenu.removeClass("hidden");
                clearTimeout(userMenu.data("timeout"));
            }).mouseleave(function(){
                var userMenu = $(this).children(".user-menu");
                userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
                userMenu.data("timeout", setTimeout(function(){userMenu.addClass("hidden")}, 100));
            });

	        /* 表单获取焦点变色 */
	        $("form").on("focus", "input", function(){
		        $(this).addClass('focus');
	        }).on("blur","input",function(){
				        $(this).removeClass('focus');
			        });
		    $("form").on("focus", "textarea", function(){
			    $(this).closest('label').addClass('focus');
		    }).on("blur","textarea",function(){
			    $(this).closest('label').removeClass('focus');
		    });

            // 导航栏超出窗口高度后的模拟滚动条
            var sHeight = $(".sidebar").height();
            var subHeight  = $(".subnav").height();
            var diff = subHeight - sHeight; //250
            var sub = $(".subnav");
            if(diff > 0){
                $(window).mousewheel(function(event, delta){
                    if(delta>0){
                        if(parseInt(sub.css('marginTop'))>-10){
                            sub.css('marginTop','0px');
                        }else{
                            sub.css('marginTop','+='+10);
                        }
                    }else{
                        if(parseInt(sub.css('marginTop'))<'-'+(diff-10)){
                            sub.css('marginTop','-'+(diff-10));
                        }else{
                            sub.css('marginTop','-='+10);
                        }
                    }
                });
            }
        }();
    </script>
	
	
	
    <script>
	
	
	var group = $("#if_group").val();
	
	if(group==1 || group==9){
	
	   //定时请求
	   
	   
		$(function(){
		      var ints = 0;
		      ints = setInterval("noReplyNews()",3000);
		});
		
		
		
		//查询是否有未回复消息
		function noReplyNews(){
			
			$.post("<?php echo U('Ajax/noReplyNews');?>",{type:'news'},function(dd){
			
				if(dd.status==100){
				
				   layer.open({
					  type: 1,
					  closeBtn: 0, //不显示关闭按钮
					  anim: 2,
					  area: ['340px', '215px'],
					  offset: 'rb', //右下角弹出
					  shadeClose: true, //开启遮罩关闭
					  content: '<div style="width:95%; margin:0 auto; margin-top:8px;">'+dd.msg+'</div>'
					});
					
					
				
				}
			
			});
		
		}
	
	}
	
	
	
	
    </script>
	
	
	
	
	
    
<link href="/Public/static/datetimepicker/css/datetimepicker.css" rel="stylesheet" type="text/css">
<?php if(C('COLOR_STYLE')=='blue_color') echo '<link href="/Public/static/datetimepicker/css/datetimepicker_blue.css" rel="stylesheet" type="text/css">'; ?>
<link href="/Public/static/datetimepicker/css/dropdown.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/Public/static/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/Public/static/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript" src="/Public/static/birthday.js"></script>
    <script type="text/javascript">

        

		$('.tab-nav li').click(function(){
			
			$(this).attr('class','current')
						.siblings().attr('class','');
			var n_index=$('.tab-nav li').index(this);
				$('.tab-content').eq(n_index).show()
							 .siblings().hide();
		});
	
        //导航高亮
        highlight_subnav('<?php echo U('Product/index');?>');
		
        $('input[name=shelftime]').datetimepicker({
			format: 'yyyy-mm-dd hh:ii:ss',
			language:"zh-CN",
			minView:2,
			autoclose:true
		});
		
		//年份
		$(function() {
			$.ms_DatePicker({
					YearSelector: "#my_sel",
			});
			$.ms_DatePicker();
		});

		$(".colors").click(function(){
		
		   
		   
		
		});
		
		
		
    </script>

</body>
</html>