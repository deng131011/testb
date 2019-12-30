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
            

            
<script type="text/javascript" src="/Public/layer/layer/layer.min.js"></script>
	<!-- 标题栏 -->
	<div class="main-title">
		<h2>项目列表</h2>
	</div>
	
	
	<div class="formBox">
	  <form id="addform" action="<?php echo U('Admin/product/upload');?>" method="post" enctype="multipart/form-data">
	  <input name="id" type="hidden" value="<?php echo ($goods_info["id"]); ?>" />
	        
	
	    </form>
  	</div>
	
	
	<div class="cf">
		<div class="fl">
            <a class="btn" href="<?php echo U('Product/add');?>">新 增</a>
            <button class="btn ajax-post" url="<?php echo U('Product/changeStatus',array('method'=>'resumeUser'));?>" target-form="ids">启 用</button>
            <button class="btn ajax-post" url="<?php echo U('Product/changeStatus',array('method'=>'forbidUser'));?>" target-form="ids">禁 用</button>
            <button class="btn ajax-post confirm" onClick="foreverdel(0,'/Admin/Product');" target-form="ids">删 除</button>
        </div>
        <!--
         <form id="addform" action="<?php echo U('Admin/Product/upload');?>" method="post" enctype="multipart/form-data">
  <input name="id" type="hidden" value="<?php echo ($goods_info["id"]); ?>" />
        <div class="control-group" style="margin-bottom: 20px;">
          		<label>Excel表格：</label>
                <input type="file" name="excelData" value=""  datatype="*4-50"  nullmsg="请填写产品！" errormsg="不能少于4个字符大于50个汉字"/>
                <span class="Validform_checktip"></span>
                <img style="display:none;" src="images/loading.gif" />
          		<input type="submit" class="btn btn-primary Sub "  value="导入" />
          		 <a class="btn" href="/public/admin/stock.xls">模板下载</a>
        </div>

    </form>-->
		<!-- 高级搜索 -->
		<div class="search-form fr cf">
			<form action="<?php echo U('index');?>" id="form1" method="get">
				<div class="sleft">
					<input type="text" name="title" class="search-input" value="<?php echo ($get[title]); ?>" placeholder="项目名称">
					<a class="sch-btn" href="javascript:;" id="search" url="<?php echo U('index');?>"><i class="btn-search"></i></a>
				</div>
			</form>
		</div>
    </div>
    <!-- 数据列表 -->
    <div class="data-table table-striped">
	<table class="">
    <thead>
        <tr>
		<th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
		<th class="">编号</th>
		<th class="">项目名称</th>
		<th class="">项目类型</th>
		<th class="">项目区域</th>
		<th class="">项目地址</th>
        <th class="">添加时间</th>
		<th class="">状态</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
		<?php if(!empty($list)): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
            <td><input class="ids" type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>" /></td>
			<td><?php echo ($vo["id"]); ?> </td>
			<td><?php echo ($vo["title"]); ?></td>
			<td><?php echo ($vo["typeid"]); ?></td>
			<td><?php echo (modelfield($vo["province_id"],"province","addr")); ?>-<?php echo (modelfield($vo["city_id"],"city","addr")); ?></td>
            <td><?php echo (cutstr($vo["addr_map"],0,30)); ?></td>
			<td><?php echo (time_format($vo["create_time"])); ?></td>
			<td>
			  <?php if($vo[status] == 0): ?>禁用
			  <?php elseif($vo[status] == 1): ?>
			    启用<?php endif; ?>
			
			</td>
			<td><?php if(($vo["status"]) == "1"): ?><a href="<?php echo U('Product/changeStatus?method=forbidUser&id='.$vo['id']);?>" class="ajax-get">禁用</a>
				<?php else: ?>
				<a href="<?php echo U('Product/changeStatus?method=resumeUser&id='.$vo['id']);?>" class="ajax-get">启用</a><?php endif; ?>
                <a href="<?php echo U('Product/edit?id='.$vo['id']);?>">编辑</a>
                <!-- <a href="javascript:void(0)"  onclick="alatsList(<?php echo ($vo["id"]); ?>)">上传图集</a>
                 <a href="<?php echo U('Product/registration?id='.$vo['id']);?>">项目备案</a>-->
				<a href="<?php echo U('Product/changeStatus?method=deleteUser&id='.$vo['id']);?>" class="confirm ajax-get">删除</a>
                </td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		<?php else: ?>
		<td colspan="9" class="text-center"> aOh! 暂时还没有内容! </td><?php endif; ?>
	</tbody>
    </table>
	</div>
    <div class="page">
        <?php echo ($_page); ?>
    </div>

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
	
	
	
	
	
    
	<script src="/Public/static/thinkbox/jquery.thinkbox.js"></script>

	<script type="text/javascript">
	//搜索功能
	$("#search").click(function(){
		var url = $(this).attr('url');
        var query  = $('.search-form').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        if( url.indexOf('?')>0 ){
            url += '&' + query;
        }else{
            url += '?' + query;
        }
		window.location.href = url;
	});
	//回车搜索
	$(".search-input").keyup(function(e){
		if(e.keyCode === 13){
			$("#search").click();
			return false;
		}
	});
	
	
	//搜索条件
	$("#searchs").click(function(){
		var url = $(this).attr('url');
        var query  = $('#searchFrm').find('input,select').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        if( url.indexOf('?')>0 ){
            url += '&' + query;
        }else{
            url += '?' + query;
        }
		window.location.href = url;
	});
    //导航高亮
    highlight_subnav('<?php echo U('Product/index');?>');
	
	function alatsList(id){

		var index = $.layer({
			type : 2,
			fix : true,
			shade : [0.5 , '#000' , true],
			shadeClose : true,
			closeBtn: false,
			border : [!0],
			title : false,
			//offset : ['25px',''],
			area : ['900px', '700px'],
			iframe : {src : '/Admin/Product/alatsList?id='+id}
		});
		
		
	}
	</script>

</body>
</html>