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
class IndexController extends HomeController {
	
	public function _initialize(){
	   
	    parent::_initialize();
    }
	

	//系统首页
    public function index(){
		

		
        $this->redirect('/home/Wood/index');
    }
	
	 public function apply(){
		 $mapa[pid] = 0;
		 $mapa[status] = 1;
		 $list = M('survey')->where($mapa)->order('sort asc')->select();
		 foreach($list as $k=>$v){
			 $mapa[pid] = $v[id];
			 $list[$k]['problem'] = M('survey')->where($mapa)->order('sort asc')->select();
		 }
		 
		 
		 $meet = M('meeting')->where('status=1')->find();
		 $this->assign('hd_id',$meet[id]);
		 
		 $this->assign('list',$list);
		 $this->display();
	 }
	
	//问卷调查
	public function add(){
		
		$get = $_POST;
		//p($get);
		$datas[create_time] = time();
		$datas[content] = $get[content];
		$datas[cid] = $get[cid];
		$dyresult = M('dyresult')->add($datas);
		
		foreach($get[wtid] as $k=>$v){
			$da[$k][parent_id] = $dyresult;
			$da[$k][wtid] = $v;
			$da[$k][satisfaction] = $get['like'.$v];
			$da[$k][important] = $get['important'.$v];
		}		
		//p($da);
		 
		 M('dyresult_list')->addAll($da);
		// echo M('dyresult_list')->getLastSql();
		 $this->display();
	 }
	
	
	  //活动签到
	  public function addsign(){
		  
		  $data = $_POST;
		  $arr[username] = $data[username];
		  $arr[mobile]   = $data[mobile];
		  $arr[company]  = $data[company];
		  $user = M('usermember')->add($arr);
		  
		  
		  $da[hd_id] = $data[hd_id];
		  $da[cid] = $user;
		  $da[create_time] = time();
		  M('meeting_signin')->add($da);
		  
		  
		  
	  }
	  
	  
	  
	
	
	
	
	
	
	
	

}