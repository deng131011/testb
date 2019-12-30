<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="wap-font-scale" content="no">

<title>优耐板</title>
<meta name="keywords" content="<?php echo C('WEB_SITE_KEYWORD');?>" />
<meta name="description" content="<?php echo C('WEB_SITE_DESCRIPTION');?>" />
<!--fonts-->
<link rel="stylesheet" type="text/css" href="/Public/Home/fonts/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="/Public/Home/css/comm.css"> 
<link rel="stylesheet" type="text/css" href="/Public/Home/css/about.css"> 
<!--js-->
<script type="text/javascript" src="/Public/Home/js/mobile.js"></script>
<script type="text/javascript" src="/Public/Home/js/jquery-1.9.1.min.js"></script>

<!--动画-->
<link rel="stylesheet" type="text/css" href="/Public/Home/css/animate.min.css"> 
<script type="text/javascript" src="/Public/Home/js/wow.min.js"></script>

</head>
<body>
    <div class="main">
          <div class="ab_top width_all wow rollIn" data-wow-delay="0.5s" style="background:url(<?php echo picture($icon['icon']);?>) no-repeat; background-size: 100% 100%;">
             <div class="ab_logo"><img src="<?php echo picture($logo['icon']);?>"/></div>
          </div>

          <div class="width662">
                <div class="width_all ab_indexa ">
                    <div class="imgsizea wow bounceInLeft" data-wow-delay="1.2s"><img src="/Public/Home/images/about/ab2.png"/></div>
                </div>
                <div class="width_all ab_indexb">
                    <div class="imgsizeb wow bounceInRight" data-wow-delay="2s"><img src="/Public/Home/images/about/ab3.png"/></div>
                </div>

                <div class="width_all about_type_list" >
                    <ul>
					
						<?php if(is_array($typeList)): $i = 0; $__LIST__ = $typeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($i%2 == 0): ?><li class='ty_list wow lightSpeedIn' data-wow-delay="<?php echo ($i/2); ?>s">
							   <a href="<?php echo U($vo['url'],array('id'=>$vo[id]));?>"><?php echo ($vo[title]); ?></a>
							</li>
						 <?php else: ?>
                            <li class='ty_list wow rollIn' data-wow-delay="<?php echo ($i/2); ?>s">
							   <a href="<?php echo U($vo['url'],array('id'=>$vo[id]));?>"><?php echo ($vo[title]); ?></a>
							</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </ul>



                </div>



          </div>

	  
	</div>


    <!--底部导航-->
       <div class="navyc"></div>
        <div class="nav">
		
		   <?php if(is_array($navList)): $i = 0; $__LIST__ = $navList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$navtop): $mod = ($i % 2 );++$i;?><div class="nav_title">
        	    <div class="nav_img"><img src="/Public/Home/images/nav.jpg"/></div>
        		<p><?php echo ($navtop[title]); ?></p>
                 <!--二级菜单--> 
                <div class="two_nav">
                    <div class="mids">
					  <?php if(is_array($navtop['twoNavlist'])): $i = 0; $__LIST__ = $navtop['twoNavlist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$twonav): $mod = ($i % 2 );++$i;?><p><a href="<?php if($twonav[id] == 16): ?>http://donghua.59156.cn<?php else: echo U($twonav['url'],array('id'=>$twonav['id'])); endif; ?>"><?php echo ($twonav[title]); ?></a></p><?php endforeach; endif; else: echo "" ;endif; ?>
                	</div>
                </div> 
        	</div><?php endforeach; endif; else: echo "" ;endif; ?>
        	
        	
        </div> 
<script>
   
   $(".nav_title").click(function(){
      
   
      $(".nav_title").children('.two_nav').hide();
	  $(this).children('.two_nav').show();

   });
   
   $(".main,.about_main,.bsg_main").click(function(){
      
	  
      $(".nav_title").find(".two_nav").hide();
   
   });
   
    

</script>
	   
<script>
   
   $(".ty_list").click(function(){
   
       $(".ty_list").removeClass('on');
       $(this).addClass('on'); 
   });
    

</script>
<script>
    if (!(/msie [6|7|8|9]/i.test(navigator.userAgent))){
        new WOW().init();
    };
</script>

</body>
</html>