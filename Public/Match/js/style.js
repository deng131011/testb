// JavaScript Document
$(document).ready(function(e) {
    //mo首页导航弹出框
	if($(window).width()<768){
    $(".pro_types").on("click",function(){
        var num = $(this).index(); 

        if($(this).hasClass('xz1')){
            $(".sidebar").hide();
            $(".zz1").eq(num).fadeOut(100);
            $(".navbar_nav1").eq(num).removeClass('navbar-transiton');
            $("body").css({overflow:"scroll"});
            $(this).removeClass('xz1');
        }else{
            $("body").css({overflow:"scroll",overflowY:"hidden"});
            $(".sidebar").show();
            $(".zz1").eq(num).css('z-index', 13).fadeIn(100);
            $(".navbar_nav1").eq(num).addClass('navbar-transiton');
            $(".classify_box").removeClass('classify-box-transition');
            $(this).addClass('xz1');
        }
    });

    $(".zz1").on("click",function(){
        $(".sidebar").hide();
        $(".zz1").fadeOut(100);
        $(".navbar_nav1").removeClass('navbar-transiton');
        $("body").css({overflow:"scroll"});
        $('.pro_types').removeClass('xz1');
    });
	
	
	 /*mo导航下拉*/
    $('.navbar_body ul li').on('click','a',function(){
    	if($(this).parent().hasClass('xz')){
    		$(this).parent().children('ol').slideUp();
    		$(this).parent().removeClass('xz');
    		$(this).children('i').removeClass('fa-angle-down');
    		$(this).children('i').addClass('fa-angle-right');
    	}
    	else{
    		$(this).parent().children('ol').slideDown();
    		$(this).parent().addClass('xz');
    		$(this).parent().prevAll().children('ol').slideUp();
    		$(this).parent().nextAll().children('ol').slideUp();
    		$(this).parent().prevAll().removeClass('xz');
    		$(this).parent().nextAll().removeClass('xz');
    		$(this).parent().prevAll().children('a').children('i').removeClass('fa-angle-down');
    		$(this).parent().nextAll().children('a').children('i').removeClass('fa-angle-down');
    		$(this).parent().prevAll().children('a').children('i').addClass('fa-angle-right');
    		$(this).parent().nextAll().children('a').children('i').addClass('fa-angle-right');
    	}
    })
	
	
	}


   //返回市
    $(".addrs_list").live('click',function(){
        
        var pro_id = $(this).attr("data-id");

        $.post("/Home/Ajax/get_city",{pro_id:pro_id},function(dd){
      
             
              $("#address").html(dd);

        });

    });

   



    //返回行业下的子集
    $(".hy_lists").live('click',function(){
        
        var hy_id = $(this).attr("data-id");
		var ishyson = $(this).attr("data-status");
        if(ishyson!=2){
			 $.post("/Home/Ajax/get_hangye",{hy_id:hy_id},function(dds){
      
             
                 $("#hangyeer").html(dds);

             });
		}
       

    });
    
	
    
})