<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
	 * 	作用：生成可以获得code的url
	 */
	function createOauthUrlForCode($redirectUrl)
	{
		$urlObj["appid"] = 'wxbb6e3081d3f6ae3d';
		$urlObj["redirect_uri"] = "$redirectUrl";
		$urlObj["response_type"] = "code";
		$urlObj["scope"] = "snsapi_base";
		$urlObj["state"] = "STATE"."#wechat_redirect";
		$bizString = formatBizQueryParaMap($urlObj, false);
		return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
	}
	
	/**
	 * 	作用：通过curl向微信提交code，以获取openid
	 */
	function getOpenid($code)
	{
		$url = createOauthUrlForOpenid($code);
        //初始化curl
       	$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOP_TIMEOUT, 50);
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//运行curl，结果以jason形式返回
        $res = curl_exec($ch);
		curl_close($ch);
		//取出openid
		$data = json_decode($res,true);
		return $data['openid'];
	}
	
	/**
	 * 	作用：生成可以获得openid的url
	 */
	function createOauthUrlForOpenid($code)
	{
		$urlObj["appid"] = 'wxbb6e3081d3f6ae3d';
		$urlObj["secret"] = 'ec5f1bfce22f851d786dde25231db506';
		$urlObj["code"] = $code;
		$urlObj["grant_type"] = "authorization_code";
		$bizString = formatBizQueryParaMap($urlObj, false);
		return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
	}
	
	/**
	 * 	作用：格式化参数，签名过程需要使用
	 */
	function formatBizQueryParaMap($paraMap, $urlencode)
	{
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
		    if($urlencode)
		    {
			   $v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0) 
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}

/**
 * 前台公共库文件
 * 主要定义前台公共函数库
 */

function geticon($id){
	$v=M('usermember')->Field('icon')->find($id);
	$icon=$v['icon'];
	$picture=M('picture')->Field('path')->find($icon);
	if($picture){
		return $picture['path'];
	}
	return '/Public/Home/images/def.jpg';
}
/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function check_verify($code, $id = 1){
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}

/**
 * 获取列表总行数
 * @param  string  $category 分类ID
 * @param  integer $status   数据状态
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_list_count($category, $status = 1){
    static $count;
    if(!isset($count[$category])){
        $count[$category] = D('Document')->listCount($category, $status);
    }
    return $count[$category];
}




/**
 * 获取段落总数
 * @param  string $id 文档ID
 * @return integer    段落总数
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_part_count($id){
    static $count;
    if(!isset($count[$id])){
        $count[$id] = D('Document')->partCount($id);
    }
    return $count[$id];
}
//生成验证码
function generate_code($length = 4) {
	
	
	return rand(pow(10,($length-1)), pow(10,$length)-1);

	
}



//会员密码加密
//密码加密
function mymd5($password){

	$jmpassword = md5(md5($password));
	return $jmpassword;
}

/**
 * 获取导航URL
 * @param  string $url 导航URL
 * @return string      解析或的url
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_nav_url($url){
    switch ($url) {
        case 'http://' === substr($url, 0, 7):
        case '#' === substr($url, 0, 1):
            break;        
        default:
            $url = U($url);
            break;
    }
    return $url;
}






//返回会员信息
function inforation($mid,$field,$fieldList){
	
	$infor = M('information')->where('mid='.$mid)->field($field)->find();
	
	if($field == 'grade'){

		$vo = ingradeTit($infor[$field],$fieldList);
		
		
	}elseif($field == 'match'){
		
		$vo = inmatchTit($infor[$field],$fieldList);
		
	}elseif($field == 'create_time'){
		
		$vo = date('Y-m-d',$infor['create_time']);
		
	}else{
		
		$vo = $infor[$field];	
		
	}
	
	return $vo;
	
}
//返回会员等级

function ingradeTit($id,$field){

	$grader = M('garde')->find($id);
	
	return $grader[$field];
	
}

function inmatchTit($id,$field){
	
	$match = M('matchmaker')->where('u_m_id='.$id)->find();
	
	return $match[$field];
	
}




//发送短信验证码

function send_sms($config,$phone,$code){

	$sendInfo = D('message')->find($config);
	//短信接口用户名 $uid
	$uid = $sendInfo['account'];
	//短信接口密码 $passwd
	$passwd = $sendInfo['password'];
	//发送到的目标手机号码 $telphone
	$telphone = $phone;
	//短信内容 $message
	$message = $sendInfo['content'];

	if(!empty($code)){
			
		$message = str_replace('{code}',$code,$message);
	}



	$accout=urlencode(mb_convert_encoding($uid,'gb2312','utf-8' ));
	$pwd=urlencode(mb_convert_encoding($passwd,'gb2312','utf-8' ));
	$phone=urlencode(mb_convert_encoding($telphone,'gb2312','utf-8' ));
	$content=urlencode(mb_convert_encoding($message,'gb2312','utf-8' ));

	$urlid = $sendInfo['apiurl'].'?zh='.$accout.'&mm='.$pwd.'&hm='.$phone.'&nr='.$content.'&dxlbid='.$sendInfo['type'];
	$result = file_get_contents($urlid);

	return $result;


}


//上一篇
function pregpage($md,$typeid,$times){
	$where[typeid] = $typeid;
	$where[create_time] = array('gt',$times);
	$pregpage = M($md)->where($where)->order('create_time asc')->find();
	return $pregpage;
}


//下一篇
function nextpage($md,$typeid,$times){
	$where[typeid] = $typeid;
	$where[create_time] = array('lt',$times);
	$nextpage = M($md)->where($where)->order('create_time desc')->find();
	return $nextpage;
}


//下载文件路径
function file_url($icon){
	
	$list = M('file')->where('id='.$icon)->find();
	if(!empty($list)){
		$url = "/Uploads/Download/".$list[savepath].$list[savename];
	}else{
		$url = "javascript:;";
	}
	   
	return $url;
}



//审核状态
function get_check_status($status){

    if($status==0){
          return '审核中';
    }else if($status==1){
          return '审核成功';
    }else if($status==2){
          return '审核失败';
    }

}





//获取每年的数量

function  getYearsNum($year){
    
        $stcd[status] = 1;
        $stcd[years] = $year;
        $counts = M('product')->where($stcd)->count();
        return $counts;
       

}


function getCityNum($city_id){

	$count = M('product')->where('status=1 and city_id='.$city_id)->count();
    return $count;
}
function getProvinceNum($pro_id){

	$count = M('product')->where('status=1 and province_id='.$pro_id)->count();
    return $count;
}

//返回行业子集的数量
function getIndestryNum($where){
    
	$count = M('industry')->where($where)->count();
    return $count;
}

//返回行业的数量
function getHyParentNum($id){
    $map["bhy_industry.pid"] = $id;
    $map["bhy_industry.id"] = $id;
    $map['_logic'] = 'OR';
    $where['_complex'] = $map;
    $where["p.status"] = 1; 
	$count = M('industry')->join('bhy_product as p on p.hy_id=bhy_industry.id')->where($where)->count();
	//return M('industry')->getLastSql();
    return $count;
}

//获取ip地址
function getIp(){
		global  $_SERVER;  
		if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){  
			   $ip  =  $_SERVER("HTTP_X_FORWARD_FOR");  
		}  
		elseif(isset($_SERVER["HTTP_CLIENT_IP"])){  
		   $ip  = $_SERVER["HTTP_CLIENT_IP"]; 
		} 
		else{
		   $ip  =  $_SERVER["REMOTE_ADDR"];  
		}
		return $ip;
	}


//获取距离
function getDistance($start_wd,$start_jd,$end_wd,$end_jd){

    $juli = file_get_contents('http://apis.map.qq.com/ws/distance/v1/?mode=driving&from='.$start_wd.','.$start_jd.'&to='.$end_wd.','.$end_jd.'&key=PAZBZ-LUI3U-7I3VV-4RTTQ-GTX52-ZQFR5');
    $rets = json_decode($juli,true);
    $distance = $rets[result][elements][0][distance];
    //return 'http://apis.map.qq.com/ws/distance/v1/?mode=driving&from='.$start_wd.','.$start_jd.'&to='.$end_wd.','.$end_jd.'&key=PAZBZ-LUI3U-7I3VV-4RTTQ-GTX52-ZQFR5';
    return $distance;
}	