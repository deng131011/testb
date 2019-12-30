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
    
    <style type="text/css">
        #addBtn{background: #2d7200; padding:2px 10px; color: #fff; margin-left: 20px; border-radius: 2px;}
        #addXuan p{margin-bottom: 10px;}
        .deler{background: gray; color: #fff; padding:5px 10px; cursor:pointer;}
        .select_danxuan,.add_xuan{display: none;}
    </style>


    <div class="main-title">
        <h2>修改</h2>
    </div>
    <ul class="tab-nav nav">


        <li class="current"><a href="javascript:void(0)">基本信息</a></li>
		<li><a href="javascript:void(0)">高级信息</a></li>

    </ul>
    
    <form action="<?php echo U();?>" method="post" class="form-horizontal">
    <div class="tab-content">
	    <!--上级id-->
	   
	
        <div class="form-item">
            <label class="item-label">标题<span class="check-tips">（标题必须填写）</span></label>
            <div class="controls">
                <input type="text" class="text input-large" name="title" value="<?php echo ($vo["title"]); ?>">
                <input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />
            </div>
        </div>
		
		<div class="form-item">
            <label class="item-label">排序</label>
            <div class="controls">
                <input type="text" class="text input-large" name="sort" value="<?php echo ($vo["sort"]); ?>">
            </div>
        </div>
		
		<div class="form-item">
            <label class="item-label">所属活动</label>
            <div class="controls">
                <select name="hd_id" style="width:200px;">
                    <?php if(is_array($meetList)): $i = 0; $__LIST__ = $meetList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$clist): $mod = ($i % 2 );++$i;?><option value="<?php echo ($clist["id"]); ?>" <?php if($vo['hd_id'] == $clist['id']): ?>selected<?php endif; ?>><?php echo ($clist["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
		
		<div class="form-item">
            <label class="item-label">问题类型</label>
            <div class="controls">
                <select name="type" style="width:200px;">
                   
                        <option value="1" <?php if($vo[type] == 1): ?>selected<?php endif; ?>>单选</option>
                      <!--  <option value="2" <?php if($vo[type] == 2): ?>selected<?php endif; ?>>多选</option>
                        <option value="3" <?php if($vo[type] == 3): ?>selected<?php endif; ?>>单选+多选</option> -->
                        <option value="4" <?php if($vo[type] == 4): ?>selected<?php endif; ?>>文本</option>
                    
                </select>
            </div>
        </div>
       
        <div class="form-item select_danxuan">
            <label class="item-label">选择单选选项</label>
            <div class="controls">
                <?php if(is_array(C("WEB_MEETPROBLEM_TYPE"))): $i = 0; $__LIST__ = C("WEB_MEETPROBLEM_TYPE");if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$danxuan): $mod = ($i % 2 );++$i;?><label class="checkbox">
                        <input type="checkbox" name="danxuan[]" value="<?php echo ($key); ?>" <?php if(in_array(($key), is_array($vo["danxuan"])?$vo["danxuan"]:explode(',',$vo["danxuan"]))): ?>checked<?php endif; ?>><?php echo ($danxuan); ?>
                    </label><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
        
        <div class="form-item add_xuan">
            <label class="item-label">添加选项  <span id="addBtn">添加</span></label>
            <div class="controls" id="addXuan">
			    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vor): $mod = ($i % 2 );++$i;?><p class="wentilist<?php echo ($vor[id]); ?>">
				   <input type="text" class="text input-large" name="pro_title<?php echo ($vor[id]); ?>" value="<?php echo ($vor[title]); ?>">
				   <input type="hidden" name="proson_id[]" value="<?php echo ($vor[id]); ?>">
				   <span class="deler" data-id="<?php echo ($vor[id]); ?>">移除</span></p><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>


        
       
		
        <div class="form-item">
            <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
            <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
        </div>
    </div>
    <div class="tab-content" style="display:none;">
        
        <div class="form-item">
            <label class="item-label">修改时间</label>
            <div class="controls">
                 <input type="text" class="text input-large" name="update_time" id="deadline2" value="<?php echo (time_format($vo["create_time"],'Y-m-d H:i')); ?>">
            </div>
        </div>
    	
       
        
        
        <!--
        <div class="form-item">
            <label class="item-label">截止时间<span class="check-tips">（显示截止时间）</span></label>
            <div class="controls">
                <input type="text" class="text input-large" id="deadline" name="deadline" value="<?php echo ($vo["deadline"]); ?>">
            </div>
        </div>
		-->
        <div class="form-item">
            <label class="item-label">来源<span class="check-tips"></span></label>
            <div class="controls">
                <input type="text" class="text input-large" id="source" name="source" value="<?php echo ($vo["source"]); ?>">
            </div>
        </div>
        
        <div class="form-item">
            <label class="item-label">发布人<span class="check-tips"></span></label>
            <div class="controls">
                <input type="text" class="text input-large" id="mid" name="author" value="<?php echo ($vo["author"]); ?>">
            </div>
        </div>
		
		<div class="form-item">
            <label class="item-label">内容</label>
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
    <script type="text/javascript">
	
		$('.tab-nav li').click(function(){
			$(this).attr('class','current')
						.siblings().attr('class','');
			var n_index=$('.tab-nav li').index(this);
				$('.tab-content').eq(n_index).show()
							 .siblings().hide();
		});
	
        //导航高亮
        highlight_subnav('<?php echo U('MeetingProblem/index');?>');
		
		$('#deadline').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        language:"zh-CN",
        minView:2,
        autoclose:true
    });
		$('#deadline2').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        language:"zh-CN",
        minView:2,
        autoclose:true
    });
    </script>
<script type="text/javascript" src="/Public/Admin/js/jquery-1.8.0.js"></script>
<script>


    //点击添加选项
     $("#addBtn").click(function(){

             
            $("#addXuan").append('<p><input type="text" class="text input-large" name="sontitle[]" value=""> <span class="deler">移除</span></p>'); 

     });

     //移除
     $(".deler").live('click',function(){
         
		  var proson_id = $(this).attr('data-id'); 
		  
		  if(proson_id>0){
		     $.post("<?php echo U('editdelete');?>",{id:proson_id},function(dr){
			     if(dr.status==1){
				    $('.wentilist'+proson_id).remove();
				 }else if(dr.status==2){
				    update.alert('移除失败！');
					return false;
				 }
			 });  
		  }else{
		     $(this).parent('p').remove();
		  }
		 
     }); 
	 
	 //初始化问题类型效果
	 
	 var wenstatus = $("select[name='type'").val();
	    
	 if(wenstatus==1){
	    $(".select_danxuan").show();
		$(".add_xuan").hide();
	 }else if(wenstatus==2){
         $(".select_danxuan").hide();
         $(".add_xuan").show();
     }else if(wenstatus==3){
         $(".select_danxuan").show();
         $(".add_xuan").show();
     }else{
         $(".select_danxuan").hide();
         $(".add_xuan").hide();
     }
     
     //改变问题类型的效果
     $("select[name='type'").change(function(){

         var seval = $(this).val();
         var xuancount = $("#addXuan").find('p').length;
		
		 if(xuancount==0){
		  $("#addXuan").html('');
		 }
         $(".select_danxuan").find('input[type="checkbox"]:checked').attr('checked',false);
        

         if(seval==1){
              $(".select_danxuan").show();
              $(".add_xuan").hide();
         }else if(seval==2){
              $(".select_danxuan").hide();
              $(".add_xuan").show();
         }else if(seval==3){
              $(".select_danxuan").show();
              $(".add_xuan").show();
         }else{
              $(".select_danxuan").hide();
              $(".add_xuan").hide();
         }

     });


</script>



    

</body>
</html>