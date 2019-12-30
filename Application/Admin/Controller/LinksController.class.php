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
class LinksController extends AdminController {
	
	public function _initialize()
    {
		$linktype = M('category')->where('pid=18 and status=1 and isnav=2')->order('sort asc')->select();
		$this->assign('linktype',$linktype);
		
		parent::_initialize();
	}
	
	
	public function add(){
		if(IS_POST){
			$md = D('Links');
			$mm = $md->create();
			if($mm){
				$_POST[create_time] = time();
				$result = $md->add($_POST);
				if($result){
					$this->success('添加成功',U('links/index'));
				}else{
					$this->error('添加失败',U('links/index'));
				}
			}else{
				$this->error($mm->getError());
			}
		}
		$this->display();
	}
	
	
	public function edit(){
		$id = I('request.id');
		if(IS_POST){
			$md = D('Links');
			$mm = $md->create();
			if($mm){
				$_POST[create_time] = time();
				$result = $md->save($_POST);
				if($result){
					$this->success('编辑成功',U('links/index'));
				}else{
					$this->error('编辑失败',U('links/index'));
				}
			}else{
				$this->error($mm->getError());
			}
		}else{
			
			$list = M("links")->where('id='.$id)->find();
			$this->assign('vo',$list);
			$this->display();
		}
		
	}
	
	
	public function _filter(&$sqlWhere){
		
		if(!empty($_GET['name'])){
			
			$sqlWhere['name'] = array('like','%' . $_GET['name'] . '%');
		}	
		
		return $sqlWhere;
		
	}
}
