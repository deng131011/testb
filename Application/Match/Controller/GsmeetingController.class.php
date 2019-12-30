<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Match\Controller;
use OT\DataDictionary;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class GsmeetingController extends HomeController {
	
	public function _initialize(){

       //活动id
       $hd_id = M('gsmeeting')->where('status=1')->find();
       $this->assign('hd_id',$hd_id);

	   
	    parent::_initialize();
    }
	

	//系统首页
    public function index(){
		
		
		$open_id = session('bsg_user_openid');
        
		
			
		//获取全局acc_token
		$urla = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.C("WEIXIN_APPID").'&secret='.C("WEIXIN_APPSECRET");
		$stra=file_get_contents($urla);
		$arra=json_decode($stra,true); 
		$access_token = $arra['access_token'];
		
		//获取openid对应的用户信息
		$accurl ='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$open_id;
		
		$infostr=file_get_contents($accurl);
		$infoarr=json_decode($infostr,true);
		
		$this->assign('subscribe',$infoarr[subscribe]);
		
		
		$hd_id = I('request.hd_id');
		
        if($hd_id>0){
			
			$this->assign('hd_id',$hd_id);
		
		    $this->display();
			
		}else{
			$this->error('参数错误！');
		}		
		    
    }
	
	//添加信息
	public function addinfo(){
		if(IS_POST){
			$open_id = session('bsg_user_openid');
			//$msg[status] = 2;
			//$msg[msg]    = $open_id;
           // $this->ajaxReturn($msg);exit;
			
			
			
		    $pt = $_POST;
			
			
			if(empty($open_id)){
				$msg[status] = 2;
				$msg[msg]    = '获取Openid失败，尝试重新进入';
				$this->ajaxReturn($msg);exit;
			}
			//判断是否提交过
			$map[open_id] = $open_id;
			$map[hd_id]   = $pt['hd_id'];
			$info = M('gsmeeting_signin')->where($map)->find();
			if(!empty($info)){
				$msg[status] = 2;
				$msg[msg]    = '您已经提交过！';
				$this->ajaxReturn($msg);exit;
			}
			//判断是否关注过公众号
			
			if($pt['subscribe']!=1){
				$msg[status] = 3;
				$msg[openid] = $open_id;
				$msg[msg]    = '您还没有关注公众号';
				$this->ajaxReturn($msg);exit;
			}
			
			//添加记录表
			$data[create_time] = time();
			$data[open_id]     = $open_id;
			$data[username]    = $pt['username'];
			$data[hd_id]       = $pt['hd_id'];
			$resu = M('gsmeeting_signin')->add($data);
			if($resu){
				//抽奖
				$dttty['hd_id'] = $pt['hd_id'];
				$dttty['uid']   = 0;
				$numInfros = M('gswin_lists')->where($dttty)->order('sort asc')->find(); 
				
				if(!empty($numInfros)){
					
					$mpyu[id] = array('eq',$numInfros[id]);
					
					$resutyui = M('gswin_lists')->where($mpyu)->save(array('uid'=>$resu));
					
					if($resutyui&&$numInfros[rander]==1){
						$msg[status] = 1;
						$msg[msg]    = '<font style="color:#f00;font-size:17px;">恭喜您已中奖</font>';
					}else{
						$msg[msg]    = '<font style="font-size:17px;">很遗憾，没有中奖！</font>';
                        $msg[status] = 2;
					}
					
				}else{
					
					
					$msg[msg]    = '<font style="font-size:17px;">很遗憾，没有中奖！</font>';
                    $msg[status] = 2;
				} 
				
				
				
			}else{
				$msg[status] = 2;
				$msg[msg]    = '提交失败';
			}
			
			
			$this->ajaxReturn($msg);exit;
			
		}
		
			
		
	}

	
    
  

}