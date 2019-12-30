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
    <div class="main-title">
        <h2>查看</h2>
    </div>
    <ul class="tab-nav nav">

        <li class="current"><a href="javascript:void(0)">基本信息</a></li>

    </ul>
    
    <form action="<?php echo U();?>" method="post" id="form1" class="form-horizontal">
    <input type="hidden" name="pid" value="<?php echo ($vo[id]); ?>">
    <div class="tab-content">
	
	
	<?php if(!empty($list)): if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($k % 2 );++$k;?><div class="form-item">
            <label class="item-label"><?php echo ($k); ?>.<?php echo ($vos[title]); ?></label>
            <div class="controls">
                <?php if($vos[type] == 1): if(is_array(C("WEB_MEETPROBLEM_TYPE"))): $i = 0; $__LIST__ = C("WEB_MEETPROBLEM_TYPE");if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$danxuan): $mod = ($i % 2 );++$i; if(in_array(($key), is_array($vos[danxuan])?$vos[danxuan]:explode(',',$vos[danxuan]))): if($key == 1): ?><label class="checkbox">
							<input type="checkbox" name="flag[<?php echo ($key); ?>]" value="<?php echo ($key); ?>" <?php if(in_array(($key), is_array($vos[proresult][result])?$vos[proresult][result]:explode(',',$vos[proresult][result]))): ?>checked<?php endif; ?>>A.<?php echo ($danxuan); ?>&nbsp;&nbsp;
						 <?php elseif($key == 2): ?>	
						   <label class="checkbox">
							<input type="checkbox" name="flag[<?php echo ($key); ?>]" value="<?php echo ($key); ?>" <?php if(in_array(($key), is_array($vos[proresult][result])?$vos[proresult][result]:explode(',',$vos[proresult][result]))): ?>checked<?php endif; ?>>B.<?php echo ($danxuan); ?>&nbsp;&nbsp;
						<?php elseif($key == 3): ?>	
						   <label class="checkbox">
							<input type="checkbox" name="flag[<?php echo ($key); ?>]" value="<?php echo ($key); ?>" <?php if(in_array(($key), is_array($vos[proresult][result])?$vos[proresult][result]:explode(',',$vos[proresult][result]))): ?>checked<?php endif; ?>>C.<?php echo ($danxuan); ?>&nbsp;&nbsp;
						<?php elseif($key == 4): ?>	
						   <label class="checkbox">
							<input type="checkbox" name="flag[<?php echo ($key); ?>]" value="<?php echo ($key); ?>" <?php if(in_array(($key), is_array($vos[proresult][result])?$vos[proresult][result]:explode(',',$vos[proresult][result]))): ?>checked<?php endif; ?>>D.<?php echo ($danxuan); ?>&nbsp;&nbsp;
						<?php elseif($key == 5): ?>	
						   <label class="checkbox">
							<input type="checkbox" name="flag[<?php echo ($key); ?>]" value="<?php echo ($key); ?>" <?php if(in_array(($key), is_array($vos[proresult][result])?$vos[proresult][result]:explode(',',$vos[proresult][result]))): ?>checked<?php endif; ?>>E.<?php echo ($danxuan); ?>&nbsp;&nbsp;<?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
						
                <?php elseif($vos[type] == 4): ?>  
				    
					<label class="textarea input-large">
						<textarea style="text-indent:2em;" disabled><?php echo ($vos[proresult][content]); ?></textarea>
					</label><?php endif; ?>
            </div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>	
    <?php else: ?>

        <div class="form-item">
            <label class="item-label">暂无数据！<span class="check-tips"></span></label>
            
        </div><?php endif; ?>


       
        
        <div class="form-item">
            <a href="<?php echo U('index');?>" class="btn btn-return">返 回</a>
        </div>
    </div>
	<div class="tab-content" style="display:none;">
	    <div class="form-item">
            <label class="item-label">回复者</label>
            <div class="controls">
                <input type="text" name="name" class="text input-large" value="<?php echo get_user_name();?>"/>
            </div>
        </div>
		<div class="form-item">
            <label class="item-label">留言回复</label>
            <div class="controls">
                <textarea style="width:400px; height:100px;" name="content"></textarea>
            </div>
        </div>
		<div class="form-item">
            
            <a href="<?php echo U('index');?>" class="btn btn-return">返 回</a>
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
	
	
	
	
	
    	
	<script>
	
	  
		
		//提交回复
		$("#submiter").click(function(){
		
		   var content = $("textarea[name='content']").val();
		   
		   if(content==''){
		   
		    updateAlert('回复内容不能为空！');
			return false;
		   
		   }
		   
		   
		   $("#form1").submit();
		   
		   
		
		});
		
		
		
	
	
	 //导航高亮
       highlight_subnav('<?php echo U('Signin/index');?>');
	
	</script>

</body>
</html>