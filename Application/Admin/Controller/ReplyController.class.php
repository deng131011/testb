<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use User\Api\UserApi;

/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class ReplyController extends AdminController {
	
	public function _initialize(){

		$this -> assign('mid',is_login());  //当前登陆用户ID
		
		parent::_initialize();
	}
	
	public function _filter(&$sqlWhere){
		
		if(!empty($_GET['mid'])){
			//查找改用户对应的id
			$tempwhr['nickname']=array('like','%'.$_GET['mid'].'%');
			$member=M('member')->where($tempwhr)->select();
			$temp='';
			foreach ($member as $k=>$v ){
				if($k==0){
					
					$temp=$v['uid'];
					
				}else{
					
					$temp.=','.$v['uid'];
					
				}
			}
			
			$sqlWhere['mid'] = array('in',$temp);
			
		}	
		if(!empty($_GET['title'])){
			
			$sqlWhere['title'] = array('like','%' . $_GET['title'] . '%');
		}	
		
		return $sqlWhere;
		
	}
	
	public function _before_add(){
		
		if(IS_POST){
			
			$_POST['mid']=UID;
			$_POST['create_time']=strtotime($_POST['create_time']);
			
		}else{
			
			//查询素材
			
			$this -> assign('sourcelist',M('wx_source')->where('status=1')->order('create_time desc')->select());
			
		}
		
		
	}
	public function _before_edit(){
		
		
		if(IS_POST){
			
			
			
		}else{
			
			
			//查询素材
			
			$this -> assign('sourcelist',M('wx_source')->where('status=1')->order('create_time desc')->select());
			
		}
		
		
	}
	
	public function zwqp(){
		
		$qp=$_POST['locations'];
		$zwqp=pinyin($qp);
		if($zwqp){
			$arr['zwqp'] = $zwqp;
		}
		
		$this->ajaxReturn($arr);
		
	}
	

}
