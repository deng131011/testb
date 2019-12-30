<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/3/23
 * Time: 9:17
 */
date_default_timezone_set('Asia/Shanghai');

$dbhost = '127.0.0.1';  //mysql服务器主机地址
$dbuser = 'bsgwxs';      //mysql用户名
$dbpass = 'cnwtoo_bsgwxs_info';//mysql用户名密码
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if(!$conn )
{
    die('Could not connect: ' . mysql_error());
}
mysql_query("set names 'utf8'");
mysql_select_db("bsgwxs", $conn);

 
$sql = 'SELECT * FROM bhy_dyresult where dt_status=0 and status>0';

$result = mysql_query($sql,$conn);

while($row = mysql_fetch_array($result))
{
	
    $sqluser = 'SELECT * FROM bhy_usermember where id='.$row[cid];
    $userinfo = mysql_query($sqluser,$conn);
	$userarr = mysql_fetch_array($userinfo);
    $touser      = $userarr[open_id];
	
	$template_id = '59e2TktP62JWKpcm2lP6-QF3IKTTkqqRATdo1n37qW0';
	$urlweb      = 'http://'.$row[urls];//跳转链接
	
	$data=array(
		'first'=>array(
				'value'=>urlencode("亲爱的用户，您填写的博思格满意度调查问卷还没有完成提交，请尽快完成内容并提交系统，谢谢！"),
				'color'=>"#459ae9"
		),
		'keyword1'=>array(
				'value'=>urlencode('满意度调查问卷'),
				'color'=>'#459ae9'
		),
		
		'keyword2'=>array(
				'value'=>urlencode(date('Y-m-d H:i:s',time())),
				'color'=>'#459ae9'
		),
		'remark'=>array(
				'value'=>'可以直接点击进入详情。',
				'color'=>'#459ae9'
		),
			
	);
    
	$send = new OrderPush();
	$sender = $send->doSend($touser,$urlweb,$data,$template_id,$topcolor='#459ae9');
	
}

//微信推送消息类
class OrderPush
{
    protected $appid;
    protected $secrect;
    protected $accessToken;
    function  __construct()
    {
       
        $this->accessToken = $this->getToken();
    }
    /**
     * 发送post请求
     * @param string $url
     * @param string $param
     * @return bool|mixed
     */
    function request_post($url = '', $param = '')
    {
        if (empty($url) || empty($param)){
            return false;
        }
        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch); //运行curl
        curl_close($ch);
        return $data;
    }
    /**
     * 发送get请求
     * @param string $url
     * @return bool|mixed
     */
    function request_get($url = '')
    {
        if (empty($url)) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    /**
     * @param $appid
     * @param $appsecret
     * @return mixed
     * 获取token
     */
    protected function getToken($appid, $appsecret)
    {
		$appid = 'wxbb6e3081d3f6ae3d';
		$appsecret = 'ec5f1bfce22f851d786dde25231db506';
       
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" .$appid. "&secret=" .$appsecret;
		$token = $this->request_get($url);
		$token = json_decode(stripslashes($token));
		$arr = json_decode(json_encode($token), true);
		$access_token = $arr['access_token'];
		//S($appid, $access_token,720);
     
		
        return $access_token;
    }
    /**
     * 发送自定义的模板消息
     * @param $touser
     * @param $template_id
     * @param $url
     * @param $data
     * @param string $topcolor
     * @return bool
     */
    public function doSend($touser,$url='',$data,$template_id,$topcolor = '#7B68EE')
    {
        
		if($url!=''){
			$template = array(
				'touser' => $touser,
				'url' => $url,
				'template_id' => $template_id,
				'topcolor' => $topcolor,
				'data' => $data
            );
		}else{
			$template = array(
				'touser' => $touser,
				'template_id' => $template_id,
				'topcolor' => $topcolor,
				'data' => $data
            );
		}
		
      
        $json_template = json_encode($template);
		
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $this->accessToken;
	
        $dataRes = $this->request_post($url, urldecode($json_template));
			
        if ($dataRes['errcode'] == 0) {
            return true;
        } else {
            return false;
        }
    }
}







