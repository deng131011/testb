
$(function(){
	//获取市
	
	
	//编辑时的城市、省选择
	var province_id = $("#cur_pro").val();
	var city_id = $("#cur_cit").val();
	$.post("/Admin/Public/getPronvince",{province_id:province_id},function(d){
		
		   $(".sheng").html("<select name='province_id' id='province'>"+d+"</select>");
		
	});
	$.post("/Admin/Public/getCity",{city_id:city_id,province_id:province_id},function(m){
		
		   $(".shi").html(m);
		
	});
	
	$("#province").change(function(){
	   var province_id = $(this).val();
	    alert(123);
		
		$.post("/Admin/Public/getCity",{province_id:province_id},function(d){
		
		   $("#cur_cit").html(d);
		
	    });
		
    });
	
	
});


