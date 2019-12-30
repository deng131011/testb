$(function(){
	
	//改变省
	$("select[name='province_id']").change(function(){
		
		var province_id = $(this).val();
		
		getProvince(province_id);
		
	});
	
	
	var get_proid = $("#get_proid").val();
	var get_ctid = $("#get_ctid").val();
	
	$.post("/Admin/Public/getPronvince",{province_id:get_proid},function(re){
			
		$("#province_id").html("<select name='province_id'>"+re+"</select>");
		
		$("select[name='province_id']").change(function(){
		
			var province_id = $(this).val();
			
			getProvince(province_id);
			
		});
			
	});
	$.post("/Admin/Public/getCity",{province_id:get_proid,city_id:get_ctid},function(res){
			
		$("#city_id").html(res);
			
	});	
	
	
	
});

function getProvince(province_id){
		
		$.post("/Admin/Public/getCity",{province_id:province_id},function(d){
			
			$("#city_id").html(d);
			
		});
		
	}