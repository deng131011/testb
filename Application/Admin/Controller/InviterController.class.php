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
class InviterController extends AdminController {
	
	public function _initialize(){

		//查询活动
	   $map[status]	= array('gt',-1);
       $meetList = M('meeting')->where($map)->order('sort asc')->select();
       $this->assign('meetList',$meetList); 
		
		parent::_initialize();
	}
	
	
	
	
	
	
	
	//添加时字段处理
	public function _before_add(){

		

	}

   
    

    //编辑时字段处理
	public function _before_edit(){
	   
		
	}
	
}
