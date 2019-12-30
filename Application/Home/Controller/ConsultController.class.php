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
class ConsultController extends HomeController {
	
	public function _initialize(){
	   
	    parent::_initialize();
    }
	

	
	
	
	//在线客服
	public function index(){
       $where[status] = 1;
       $list = M('consult_wenti')->where($where)->order('id desc')->select(); 

       $this->assign('list',$list);
	   $this->display();	
	}

	//在线客服详情
	public function details(){
	   $id = I('request.id');
       $data = M('consult_wenti')->find($id); 

       $this->assign('vo',$data);
	   $this->display();	
	}

    //人工服务下的意见
	public function personidea(){
		  if(IS_POST){
	          $pt = $_POST;
	          session('jishuIdeas',$pt);
	          $res = session('jishuIdeas');
	          if(!empty($res)){
	             
	             $msg[status] = 1;

	          }else{
	          	 $msg[status] = 2;
	          }
	        $this->ajaxReturn($msg);

		  }else{
		  	 $this->display();	
		  }
	   
	}
	
    //人工服务意见填写人信息
	public function personinfo(){
        $uid = session('bsg_user_id');  
		$perInfo = session('jishuIdeas');
		if(IS_POST){

		        if(empty($perInfo)){
		        	$msg[status] = 3;
		        }else{
		        	$_POST['content'] = $perInfo[content];
		        	$_POST['create_time'] = time();
		        	$resu = M('consult')->add($_POST);//添加信息
					
					//查询是否用户信息有变动
					$arrm['id'] = $uid;
					$arrm['username'] = $_POST['name'];
					$arrm['mobile'] = $_POST['mobile'];
					$arrm['company'] = $_POST['company'];
					$userinfo = M('usermember')->where($arrm)->find();
					if(empty($userinfo)){
						M('usermember')->save($arrm);
					}
					
		        	if($resu){
		        		session('jishuIdeas',null);
		        		$msg[status] = 1;

		        	}else{
		        		$msg[status] = 2;
		        	}
		        	
		        }
		        $this->ajaxReturn($msg);

		}else{

            if(empty($perInfo)){
		        $this->redirect('personidea');
		        exit;
		    }
            //用户信息
			
			$userdata = M('usermember')->where('id='.$uid)->find();
			$this->assign('userdata',$userdata);
			
			$this->display();	
		}
        
         
	}



   




}