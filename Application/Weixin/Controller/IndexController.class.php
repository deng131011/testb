<?php
namespace Weixin\Controller;
class IndexController extends HomeController {
	
    public function index(){

		// 开发者中心-配置项-AppID(应用ID)
		$appId = C('WEIXIN_APPID');
		// 开发者中心-配置项-AppSecret(应用密钥)
		$appSecret = C('WEIXIN_APPSECRET');
		// 开发者中心-配置项-服务器配置-Token(令牌)
		$token = C('WEIXIN_TOKEN');
		// 开发者中心-配置项-服务器配置-EncodingAESKey(消息加解密密钥)
		$encodingAESKey = C('WEIXIN_ENCODINGAESKEY');

		// wechat模块 - 处理用户发送的消息和回复消息
		$wechat = new \Gaoming13\WechatPhpSdk\Wechat(array(
			'appId' => $appId,
			'token' => 	$token,
			'encodingAESKey' =>	$encodingAESKey //可选
		));
			

		   // 获取微信消息
		$msg = $wechat->serve();
		error_log(json_encode($msg), 0);

		// 用户关注微信号后 - 回复用户普通文本消息
		if ($msg->MsgType == 'event' && $msg->Event == 'subscribe') {
			
			//查询类型为关注回复的信息
			
			$arr['type'] = array('eq',1);$arr['status'] = array('eq',1);
			
			$event = M('wx_reply')->where($arr)->order('sort asc')->find();
			
			if($event){
				
				$data = $this -> replysend($event);
				
				$wechat->reply($data);
				
			
			}else{
				
				$wechat->reply(C('WEIXIN_DEFAULT_REPLY'));
				
			}

			
			exit();
		}

		// 用户回复1 - 回复文本消息
		if ($msg->MsgType == 'text') {

			$result = M('wx_reply')->where('title="'.$msg->Content.'" and status=1 and type=2')->order('sort asc')->find();
			if($result){
				
				$res = $this -> replysend($result);
				
				$wechat->reply($res);
			
			}else{
				
				$wechat->reply(C('WEIXIN_DEFAULT_REPLY'));
				
			}

			
			exit();

		}

		// 默认回复默认信息
		$wechat->reply(C('WEIXIN_DEFAULT_REPLY'));

    }
	
	public function replysend($event){
		
		$msglist = C('WEIXIN_DEFAULT_REPLY');
		
		if($event['typeinfo'] == 1){
				
			return $event['content'];
			
			
		}elseif($event['typeinfo'] == 2){
			
			$sou['id'] = array('in',$event['iconlist']);
			$sou['status'] = array('eq',1);
			
			$sourse = M('wx_source')->where($sou)->order('sort asc')->select();
			
			if($sourse){
				
				$repdata = array();
				foreach($sourse as $k => $v){
					
					$repdata[$k] = array(
						'title' => $v['title'],								//可选
						'description' => $v['descriptions'],						//可选
						'picurl' => C('WEB_SITE_URL').picture($v['icon']),	//可选
						'url' => $v['url']						//可选
					 );
					
				}
				
				$arrlist = array(
					'type' => 'news',
					'articles' => $repdata
				);
				
				return $arrlist;
				
			}else{
				
				return $msglist;
				
			}
			
		}else{
			
			return $msglist;
			
		}
		
	}
}