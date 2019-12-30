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
class UsermemberController extends AdminController {
	
	public function _initialize(){
		
		$map[status] = 1;
		$map[pid] = 34;
		$map[isnav] = 2;
		$typeList = M('category')->where($map)->order('sort asc')->select();
	    $this->assign('typeList',$typeList);
	    parent::_initialize();
    }
	

    public function index(){
	    if($_REQUEST['username']!=''){
		 $where[username] = array('like','%'.$_REQUEST[username].'%');
		} 	
	   $where['status'] = array('gt',-1);
	 //  $where['open_id'] = array('neq','');
	   $where['nickname|open_id'] = array('neq','');
	   
	   
	    $listsCount = M('usermember')->where($where)->count();
		$Page       = new \Think\Page($listsCount,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
	   
	   $list = M('usermember')->where($where)->order('addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
	  
	   $this->assign('_list',$list);
	   $this->assign('_page',$show);
	   $this->display();
    }

	
	public function edit(){ 
	     
	    if(IS_POST){
			$result = M('usermember')->save($_POST);
			if($result){
				$this->success('编辑成功！',U('index'));
			}else{
				$this->error('编辑失败！');
			}
			
			
		}else{
			$id = I('request.id');
			$data = M('usermember')->find($id);
			$this->assign('vo',$data);
			$this->display();
		}
	 
	}
}
