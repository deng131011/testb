<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use OT\DataDictionary;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class WoodController extends HomeController {
	
	public function _initialize(){
	   
	    parent::_initialize();
    }
	

	//系统首页
    public function index(){
		//顶部图片
		$icon = M('category')->where('id=8 and status=1')->find();
		$this->assign('icon',$icon);
		
		//顶部logo
		$weh[typeid] = 27;
		$weh[status] = 1;
		$logo = M('ad')->where($weh)->order('sort asc')->find();
		$this->assign('logo',$logo);
		
		//列表
		$where['status'] = 1;
		$where['isnav'] = 1;
		$where['pid'] = 8;
		$typeList = M('category')->where($where)->order('sort asc')->select();

        //
        
		$this->assign('voa',$this->comm(20)); 

        $this->assign('vob',$this->comm(21));

        $this->assign('voc',$this->comm(22));

        $this->assign('vod',$this->comm(23));

        $this->assign('voe',$this->comm(24));

        $this->assign('vof',$this->comm(25));



      
		$this->assign('typeList',$typeList);
        $this->display();
    }
	//公共方法
	public function comm($id){
		$vo = M('category')->where('id='.$id)->find();
		return $vo;
	}
	
	public function pageb(){
		$id = I('request.id'); 
		$this->assign('vo',$this->comm($id)); 
		$this->display(); 
	}
	
	public function pagec(){
		$id = I('request.id'); 
		$this->assign('vo',$this->comm($id)); 
		$this->display(); 
	}
	
	public function paged(){
		$id = I('request.id'); 
		$this->assign('vo',$this->comm($id)); 
		$this->display(); 
	}
	
	public function pagee(){
		$id = I('request.id'); 
		$this->assign('vo',$this->comm($id)); 
		$this->display(); 
	}
	
	public function pagef(){
		$id = I('request.id'); 
		$this->assign('vo',$this->comm($id)); 
		$this->display(); 
	}
	
	public function pageg(){
		$id = I('request.id'); 
		$this->assign('vo',$this->comm($id)); 
		$this->display(); 
	}
	
	
	public function tester(){
		
		$return_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			if(!isset($_GET['code'])){
				
				//触发微信返回code码
				$url = createOauthUrlForCode($return_url);
				header('Location:'.$url);

			}else{
                header("Content-type: text/html; charset=utf-8"); 
				$code = $_GET['code'];
				//获取openid
				$appid = C("WEIXIN_APPID");
				$url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.C("WEIXIN_APPSECRET").'&code='.$code.'&grant_type=authorization_code';
			   
				$str=file_get_contents($url);
				$arr=json_decode($str,true);
				$openid=$arr['openid'];
				
				//获取全局acc_token
				$urla = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.C("WEIXIN_APPID").'&secret='.C("WEIXIN_APPSECRET");
				$stra=file_get_contents($urla);
				$arra=json_decode($stra,true); 
				$access_token = $arra['access_token'];
				
				//获取openid对应的用户信息
				$accurl ='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid;
				
				$infostr=file_get_contents($accurl);
				$infoarr=json_decode($infostr,true);
				//p($infoarr);exit;
				
				
				
				
				$data['nickname'] = $infoarr['nickname'];
				$data['open_id'] = $openid;
				$res = M('test')->add($data);
				
				if($res){
					echo 123;
				}else{
					echo 234;
				}
		    }
		
		
		
	}
	
	
	public function test(){
		header("Content-type: text/html; charset=utf-8"); 
		
		$retr = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$return_url =urlencode($retr);
		
		
		//echo $retr;
		if(!isset($_GET['code']) || $_GET['code']==''){
			$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".C('WEIXIN_APPID')."&redirect_uri=".$return_url."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
			header("Location:".$url);

		}else{

			$code = $_GET['code'];
			$state = $_GET['state'];

			/*根据code获取用户openid*/
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".C('WEIXIN_APPID')."&secret=".C('WEIXIN_APPSECRET')."&code=".$code."&grant_type=authorization_code";

			$abs = file_get_contents($url);
			$obj=json_decode($abs,true);

			$access_token = $obj['access_token'];
			$openid = $obj['openid'];
			
			//p($obj);
			//获取信息
			$abs_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
			$abs_url_data = file_get_contents($abs_url);
			$infoarr=json_decode($abs_url_data,true);
			
			if(empty($openid)){
				
				header("Location:http://wechat.bluescope.com.cn/Home/Wood/test");exit;
				//$this->error('微信授权失败,请检查是否有昵称并且重新进入！');exit;
			}
			
			$data['nickname'] = $infoarr['nickname'];
			$data['open_id'] = $openid;
			$data['create_time'] = date('Ymd');
			$res = M('test')->add($data);
			
			if($res){
				echo 123;
			}else{
				echo 234;
			}
			

			
		}
	}
	
	
	
	
	

}