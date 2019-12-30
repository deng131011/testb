<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
//获取token
function get_token() {
    $info=C('Wx');
    $token=$info['token'];
    session ( 'token', $token );
    return $token;
}
// 获取access_token，自动带缓存功能
function get_access_token($token = '') {
    empty ($token) && $token = get_token();
    $model = M("access_token");
    $map['token'] = $token;
    $info = $model->where($map)->find();
    if(!$info)
    {
        $newaccess_token = getNowAccesstoken($token);
    }
    else
    {
        $nowtime = time();//现在时间
        $time = $nowtime - $info['lasttime'];
        $newaccess_token = $info['access_token'];
        if($time >= 1800){
            $newaccess_token = getNowAccesstoken($token);
            if($newaccess_token == 0){//重新再 调用一次
                $newaccess_token = getNowAccesstoken($token);
            }
        }
    }
    return $newaccess_token;
}
function getNowAccesstoken($token = ''){
    $nowtime = time();//现在时间
    empty ( $token ) && $token = get_token ();
    $info = get_token_appinfo ($token);
    if (empty ($info ['appid'] ) || empty ($info['secret'])) {
        return 0;
    }
    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $info ['appid'] . '&secret=' . $info ['secret'];
    $ch1 = curl_init ();
    $timeout = 5;
    curl_setopt ( $ch1, CURLOPT_URL, $url );
    curl_setopt ( $ch1, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch1, CURLOPT_CONNECTTIMEOUT, $timeout );
    curl_setopt ( $ch1, CURLOPT_SSL_VERIFYPEER, FALSE );
    curl_setopt ( $ch1, CURLOPT_SSL_VERIFYHOST, false );
    $accesstxt = curl_exec ( $ch1 );
    curl_close ( $ch1 );
    $tempArr = json_decode ($accesstxt, true);
    if (!$tempArr->errmsg) {
        $model = M("access_token");
        $map['token'] = $token;
        //保存新access_token到数据库，更新最后时间
        $data = array(
            'access_token'=>$tempArr ['access_token'],
            'lasttime'=>$nowtime
        );
        $info=$model->where($map)->find();
        if($info)
        {
            $model->where($map)->save($data);
        }
        else
        {
            $data['token'] = $token;
            $model->where($map)->add($data);
        }
        return $tempArr ['access_token'];
    }else{
        return 0;
    }
}
// 获取jsapi_ticket，判断是不过期
function getJsapiTicket($token = '') {
    empty ($token) && $token = get_token();
    $model = M("jsapi_ticket");
    $map['token'] = $token;
    $info = $model->where($map)->find();
    if(!$info)
    {
        $new_jsapi_ticket = getNowJsapiTicket($token);
    }
    else
    {
        $nowtime = time();//现在时间
        $time = $nowtime - $info['lasttime'];
        $new_jsapi_ticket = $info['ticket'];
        if($time>=1800){
            $new_jsapi_ticket = getNowJsapiTicket($token);
            if($new_jsapi_ticket == 0){//重新再 调用一次
                $new_jsapi_ticket = getNowJsapiTicket($token);
            }
        }
    }
    return $new_jsapi_ticket;
}
//获取jsapi_ticket
function getNowJsapiTicket($token='')
{
    empty ($token) && $token = get_token();
    $access_token=get_access_token();
    $url='https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' .$access_token. '&type=jsapi';
    $ch1 = curl_init ();
    $timeout = 5;
    curl_setopt ( $ch1, CURLOPT_URL, $url );
    curl_setopt ( $ch1, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch1, CURLOPT_CONNECTTIMEOUT, $timeout );
    curl_setopt ( $ch1, CURLOPT_SSL_VERIFYPEER, FALSE );
    curl_setopt ( $ch1, CURLOPT_SSL_VERIFYHOST, false );
    $accesstxt = curl_exec ( $ch1 );
    curl_close ( $ch1 );
    $tempArr = json_decode ($accesstxt, true);
    $ext=$tempArr['errmsg'];
    if ($ext=='ok') {
        $model = M("jsapi_ticket");
        $map['token'] = $token;
        $nowtime=time();
        //保存新jsapi_ticket到数据库，更新最后时间
        $data = array(
            'ticket'=>$tempArr ['ticket'],
            'lasttime'=>$nowtime
        );
        $info=$model->where($map)->find();
        if($info)
        {
            $model->where($map)->save($data);
        }
        else
        {
            $data['token'] = $token;
            $model->where($map)->add($data);
        }
        return $tempArr['ticket'];
    }
    else
    {
        return 0;
    }
}
// 获取公众号的信息
function get_token_appinfo() {
    $info=C('Wx');
    return $info;
}
//获取signature的值 获取签名值数组
function get_signature()
{
    $url='http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    $ticket=getJsapiTicket();
    $noncestr=createNonceStr();
    $timestamp=time();
    $string='jsapi_ticket='.$ticket.'&noncestr='.$noncestr.'×tamp='.$timestamp.'&url='.$url;
    $signature = sha1($string);
    $signPackage = array(
        "appId"     =>C('Wx.appid'),
        "nonceStr"  =>$noncestr,
        "timestamp" => $timestamp,
        "url"       => $url,
        "signature" => $signature,
        "string" => $string
    );
    return  $signPackage;
}
//随机生成字符串
 function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}

function guimo_fu($string,$num){
	
	if(strstr($string,"以")){
		
		$arr = explode('以',$string);
		
		if(is_numeric($arr[0]) && strlen($arr[0]) > $num){
			
			$arr[0] = str_insert($arr[0],strlen($arr[0])-$num,',');
			
		}
		
		return $arr[0].'以上';
		
		
	}else{
		
		$arr = explode('-',$string);
		
		if(is_numeric($arr[0]) && strlen($arr[0]) > $num){
			
			$arr[0] = str_insert($arr[0],strlen($arr[0])-$num,',');
			
		}
		
		if(is_numeric($arr[1]) && strlen($arr[1]) > $num){
			
			$arr[1] = str_insert($arr[1],strlen($arr[1])-$num,',');
			
		}
		
		return $arr[0].'-'.$arr[1];
		
	}
	
}

function str_insert($str, $i, $substr)
{
  for($j=0; $j<$i; $j++){
    $startstr .= $str[$j];
  }
  for ($j=$i; $j<strlen($str); $j++){
    $laststr .= $str[$j];
  }
  $str = ($startstr . $substr . $laststr);
  return $str;
}

/**
	 * 	作用：生成可以获得code的url
	 */
	function createOauthUrlForCode($redirectUrl)
	{
		$urlObj["appid"] = C("WEIXIN_APPID");
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
		$urlObj["appid"] = 'C("WEIXIN_APPID")';
		$urlObj["secret"] = 'C("WEIXIN_APPSECRET")';
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
		$stcd["addr_map"] = array('neq','');
        $stcd["province_id"] = array('neq','');
		$stcd["city_id"] = array('neq','');
        $counts = M('product')->where($stcd)->count();
        return $counts;
       

}


function getCityNum($city_id){
	$stcd[status] = 1;
	$stcd[city_id] = $city_id;
	$stcd["addr_map"] = array('neq','');
	$stcd["province_id"] = array('neq','');
	$count = M('product')->where($stcd)->count();
	
    return $count;
	
}


function getProvinceNum($pro_id){
    $stcd[status] = 1;
	$stcd[province_id] = $pro_id;
	$stcd["addr_map"] = array('neq','');
	$stcd["city_id"] = array('neq','');
	$count = M('product')->where($stcd)->count();
    return $count;
}


function haveSonnav($id){

    $data = M('industry')->where('pid='.$id)->select();
    if(empty($data)){
    	return 2;
    }else{
         return 1;
    }

}

//返回行业子集的数量
function getIndestryNum($where){
    
	$count = M('industry')->where($where)->count();
    return $count;
}

//返回行业的数量
/*function getHyParentNum($id){
    $map["bhy_industry.pid"] = $id;
    $map["bhy_industry.id"] = $id;
    $map['_logic'] = 'OR';
    $where['_complex'] = $map;
    $where["p.status"] = 1; 
	$stcd["p.addr_map"] = array('neq','');
	//$stcd["p.icon"] = array('neq',0); 
	$count = M('industry')->join('bhy_product as p on p.hy_id=bhy_industry.id')->where($where)->count();
	//return M('industry')->getLastSql();
    return $count;
}*/

function getHyParentNum($id){
    $map["bhy_industry.pid"] = $id;
    $map["bhy_industry.id"] = $id;
    $map['_logic'] = 'OR';
    $where['_complex'] = $map;
    $where["p.status"] = 1; 
	$where["p.addr_map"] = array('neq','');
	//$where["p.rovince_id"] = array('neq','');
	$where["p.city_id"] = array('neq','');
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
function getDistancetyytyty($start_wd,$start_jd,$end_wd,$end_jd){

    $juli = file_get_contents('http://apis.map.qq.com/ws/distance/v1/?mode=driving&from='.$start_wd.','.$start_jd.'&to='.$end_wd.','.$end_jd.'&key=PAZBZ-LUI3U-7I3VV-4RTTQ-GTX52-ZQFR5');
    $rets = json_decode($juli,true);
    $distance = $rets[result][elements][0][distance];
    //return 'http://apis.map.qq.com/ws/distance/v1/?mode=driving&from='.$start_wd.','.$start_jd.'&to='.$end_wd.','.$end_jd.'&key=PAZBZ-LUI3U-7I3VV-4RTTQ-GTX52-ZQFR5';
    return $distance;
}	


//是否为公司内部员工

function isCompanyLabour(){
	
	$uid = session('bsg_user_id');
	$userdata = M('usermember')->where('id='.$uid)->find();
	if($userdata['is_yewu']==1){
		return 100;
	}else{
		return 200;
	}
	
}

function getHycategory($hy_id){
	
	$hyinfo = M('industry')->where('pid='.$hy_id)->find();
	if(!empty($hyinfo)){

		 $serchinfo = modelField($hyinfo[pid],'industry','title').'-'.modelField($ss[hy_id],'industry','title');
	}else{
		 $serchinfo = modelField($hy_id,'industry','title');
	}
	return $serchinfo;
}


function getProducterType($cp_id){
	
	if(empty($cp_id)){
		$str = '<p class="span1">暂无记录</p>';	
	}else{
		$arr = explode(',',$cp_id);
		$data=array();
		$index=0;
		foreach($arr as $kr=>$vr){
			$list = M('industry')->where('id='.$vr)->find();
			
			$str .= '<p class="span1">'.$list[alltitle].'</p>';	
			
		}
		
	}
	
	return $str;
}

function getMemberImg(){
	$uid = session('bsg_user_id');
	$data = M('usermember')->where('id='.$uid)->find();
	if(!empty($data)){
		return $data[headimgurl];
	}else{
		return 0;
	}
	
}

//数字用逗号隔开


function getNumberDou($num){ 
 if(!is_numeric($num)){ 
  return false; 
 } 
 $num = explode('.',$num);//把整数和小数分开 
 $rl = $num[1];//小数部分的值 
 $j = strlen($num[0]) % 3;//整数有多少位 
 $sl = substr($num[0], 0, $j);//前面不满三位的数取出来 
 $sr = substr($num[0], $j);//后面的满三位的数取出来 
 $i = 0; 
 while($i <= strlen($sr)){ 
  $rvalue = $rvalue.','.substr($sr, $i, 3);//三位三位取出再合并，按逗号隔开 
  $i = $i + 3; 
 } 
 $rvalue = $sl.$rvalue; 
 $rvalue = substr($rvalue,0,strlen($rvalue)-1);//去掉最后一个逗号 
 $rvalue = explode(',',$rvalue);//分解成数组 
 if($rvalue[0]==0){ 
  array_shift($rvalue);//如果第一个元素为0，删除第一个元素 
 } 
 $rv = $rvalue[0];//前面不满三位的数 
 for($i = 1; $i < count($rvalue); $i++){ 
  $rv = $rv.','.$rvalue[$i]; 
 } 
 if(!empty($rl)){ 
  $rvalue = $rv.'.'.$rl;//小数不为空，整数和小数合并 
 }else{ 
  $rvalue = $rv;//小数为空，只有整数 
 } 
 return $rvalue; 
} 


//计算两点之间的距离
function getDistance($lat1, $lng1, $lat2, $lng2)  
 {  
     $earthRadius = 6367000; //approximate radius of earth in meters  
   
     /* 
       Convert these degrees to radians 
       to work with the formula 
     */  
   
     $lat1 = ($lat1 * pi() ) / 180;  
     $lng1 = ($lng1 * pi() ) / 180;  
   
     $lat2 = ($lat2 * pi() ) / 180;  
     $lng2 = ($lng2 * pi() ) / 180;  
   
     /* 
       Using the 
       Haversine formula 
  
       http://en.wikipedia.org/wiki/Haversine_formula 
  
       calculate the distance 
     */  
   
     $calcLongitude = $lng2 - $lng1;  
     $calcLatitude = $lat2 - $lat1;  
     $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);    
     $stepTwo = 2 * asin(min(1, sqrt($stepOne)));  
     $calculatedDistance = $earthRadius * $stepTwo;  
   
     return round($calculatedDistance);  
 }  

//获取行业顶级数量

function getHycounter($id){
	
	$data = M('industry')->where('status=1 and pid='.$id)->select();
	if(!empty($data)){
		$ids = array();
		$index=0;
		foreach($data as $ke=>$ve){
			$ids[$index] = $ve[id];
			$index++;
		}
		
		$where['hy_id'] = array('in',implode(',',$ids));
		$where['addr_map'] = array('neq','');
		$where['status'] = 1;
		$where['province_id'] = array('gt',0);
		$where['city_id'] = array('gt',0);
		$count = M('product')->where($where)->count();
		
	}else{
		$where['addr_map'] = array('neq','');
		$where['status'] = 1;
		$where['hy_id'] = $id;
		$where['province_id'] = array('gt',0);
		$where['city_id'] = array('gt',0);
		$count = M('product')->where($where)->count();
		
	}
	return $count;
}
 
 
 
 //获取手机ip地址
function getMobileIP()
{
    $ip=getenv('REMOTE_ADDR');
    $ip_ = getenv('HTTP_X_FORWARDED_FOR');
    if (($ip_ != "") && ($ip_ != "unknown"))
    {
        $ip=$ip_;
    }
    return $ip;
}
 
//二维数组根据某值排序
function arraySorter($array, $field, $sort = 'SORT_DESC')
{
    $arrSort = array();
    foreach ($array as $uniqid => $row) {
        foreach ($row as $key => $value) {
            $arrSort[$key][$uniqid] = $value;
        }
    }
    array_multisort($arrSort[$field], constant($sort), $array);
    return $array;
} 
 
 
 //微信分享
 function wx_share_init(){
	 
	 $wxconfig = array();
	 Vendor('Wxshare.class#jssdk');
	 $jssdk = new JSSDK('wxbb6e3081d3f6ae3d','ec5f1bfce22f851d786dde25231db506');
	 $wxconfig = $jssdk->getSignPackage();
	 return $wxconfig;
 }
 
 function rekStatus($remark){
	 
	 if($remark==''){
		return '无'; 
	 }else{
		return $remark;
	 }
	 
	 
 }
 
 
 
 
 
 
 
 