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
class JoinpersonController extends AdminController{
	
	public function _initialize(){
		$hd_id = I('request.hd_id');
		$meet = M('gsmeeting')->where('id='.$hd_id)->find();
		$this->assign('gsmeettile',$meet[title]);
		
		
		//活动列表
		$maper[status] = array('gt',-1);
		$meetingarr = M('gsmeeting')->where($maper)->order('id desc')->select();
		$this->assign('meetingarr',$meetingarr);
		
		parent::_initialize();
		
	}
	
	

	public function index(){
       
		$get = $_REQUEST;
		
		$this->assign('get',$get);
		
		if($get[hd_id]>0){
			$where["bhy_gsmeeting_signin.hd_id"] = $get[hd_id];
		}
		
		
        if($get[status]>0){
			$where["bhy_gsmeeting_signin.status"] = $get[status];
		}else{
			$where["bhy_gsmeeting_signin.status"] = array('gt',0);
		}
		

		//分页
		$listsCount = M('gsmeeting_signin')->join('bhy_gswin_lists as g on g.uid=bhy_gsmeeting_signin.id')->where($where)->count();
		$Page       = new \Think\Page($listsCount,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		
		$lists = M('gsmeeting_signin')->join('bhy_gswin_lists as g on g.uid=bhy_gsmeeting_signin.id')->where($where)->field('bhy_gsmeeting_signin.*,g.rander')->order('bhy_gsmeeting_signin.id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$lists); 
		$this->assign('get',$_GET);
		$this->assign('_page',$show);
		$this->assign('listsCount',$listsCount);
		$this->display();

	}
	
   //删除
	public function del(){
		$id = I('request.id');
		if(is_array($id)){
			$where[id] = array('in',implode(',',$id));
		}else{
			$where[id] = array('eq',$id);
		}
		$delResult = M('gsmeeting_signin')->where($where)->delete();
		if($delResult){
			$this->success('删除成功！',U('index'));
		}
	}


	
	

}
