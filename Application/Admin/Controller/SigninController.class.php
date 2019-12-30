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
class SigninController extends AdminController{
	
	public function _initialize(){
		$hd_id = I('request.hd_id');
		$meet = M('meeting')->where('id='.$hd_id)->find();
		$this->assign('meettile',$meet[title]);
		
		
		//活动列表
		$maper[status] = array('gt',-1);
		$meetingarr = M('meeting')->where($maper)->order('id desc')->select();
		$this->assign('meetingarr',$meetingarr);
		
		parent::_initialize();
		
	}
	
	

	public function index(){
       
		$get = $_REQUEST;
		
		$this->assign('get',$get);
		
		if($get[hd_id]>0){
			$where["bhy_meeting_signin.hd_id"] = $get[hd_id];
		}
		
		
        if($get[status]>0){
			$where["bhy_meeting_signin.status"] = $get[status];
		}else{
			$where["bhy_meeting_signin.status"] = array('gt',0);
		}
		

		//分页
		$listsCount = M('meeting_signin')->join('bhy_usermember as u on u.id=bhy_meeting_signin.cid')->where($where)->count();
		$Page       = new \Think\Page($listsCount,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		
		$lists = M('meeting_signin')->join('bhy_usermember as u on u.id=bhy_meeting_signin.cid')->where($where)->field('u.username,u.mobile,u.id as uids,u.company,bhy_meeting_signin.*')->order('bhy_meeting_signin.id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$lists); 
		$this->assign('get',$_GET);
		$this->assign('_page',$show);
		$this->assign('listsCount',$listsCount);
		$this->display();

	}
	
	
	public function edit(){
		$qd_id = I('request.id');
		
		//更新状态
		M('meeting_signin')->where('id='.$qd_id)->save(array('status'=>2));
		
		$data = M('meeting_signin')->where('id='.$qd_id)->find();
		$hd_id = $data[hd_id];
		$cid   = $data[cid];
		
		$where[hd_id]  = $hd_id;
		$where[status] = 1;
		$list = M('meeting_problem')->where($where)->order('sort asc')->select();
		foreach($list as $ke=>$ve){
			$ert[problem_id] = $ve[id];
			$ert[cid] = $cid;
			$list[$ke][proresult] = M('meeting_problemlist')->where($ert)->field('result,content,resulttwo')->find();
			
		}
		//echo M('meeting_problemlist')->getLastSql();exit;
		$this->assign('list',$list);
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
		$delResult = M('meeting_signin')->where($where)->delete();
		if($delResult){
			$this->success('删除成功！',U('index'));
		}
	}


	
	

}
