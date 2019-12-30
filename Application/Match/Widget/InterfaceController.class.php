<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Weixin\Controller;
use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class InterfaceController extends Controller {

	/* 空操作，用于输出404页面 */
	public function _empty(){
		$this->redirect('Index/index');
	}


    protected function _initialize(){
        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置

        if(!C('WEB_SITE_CLOSE')){
            $this->error('站点已经关闭，请稍后访问~');
        }
		
		
		
    }
	
	
	
	public function getWeiXinShare(){
		
		$list = file_get_contents('php://input');

        $post = json_decode($list,true);
		
		if($post['type'] == 1){//巴特勒
			
			$store = array(
				
				'appid' => 'wxc3de1305031db257',
				'appsecret' => '80f8898025f03a8fbe316dde69dcd257',
				'url' => $post['url']
			
			);
			
			$wxconfig = wx_share_init($store);
			
			$msg['code'] = 200;
			$msg['data'] = $wxconfig;
			exit(json_encode($msg));
			
			
		}elseif($post['type'] == 2){//博思格
		
			$store = array(
				
				'appid' => 'wxbb6e3081d3f6ae3d',
				'appsecret' => 'ec5f1bfce22f851d786dde25231db506',
				'url' => $post['url']
			
			);
			
			$wxconfig = wx_share_init($store);
			
			$msg['code'] = 200;
			$msg['data'] = $wxconfig;
			exit(json_encode($msg));

		}elseif($post['type'] == 3){//来实
		
			$store = array(
				
				'appid' => 'wx7e69ab266691264f',
				'appsecret' => '6096c6eac097ad13a6537dbde98a916e',
				'url' => $post['url']
			
			);
			
			$wxconfig = wx_share_init($store);
			
			$msg['code'] = 200;
			$msg['data'] = $wxconfig;
			exit(json_encode($msg));

		}else{
			
			$msg['code'] = 500;
			$msg['message'] = '非法操作！';
			exit(json_encode($msg));
			
		}
		
	}

}
