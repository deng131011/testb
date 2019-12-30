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
class VideoController extends HomeController {
	
	public function _initialize(){
	   
	    parent::_initialize();
    }
	

	//系统首页
    public function index(){
		$typeid = I('request.id',6,'intval');
		
		$lmtype = M('category')->where('id='.$typeid)->find();
		
		$this->assign('lmtype',$lmtype);
		
		//列表
		$where['status'] = 1;
		$where['typeid'] = $typeid;
		
		M('invest')->where($where)->setInc('view');//修改浏览量
		
		
		$list = M('Invest')->where($where)->order('sort asc')->select();
       
		$this->assign('list',$list);
        $this->display();
    }
	
	
	
	

}