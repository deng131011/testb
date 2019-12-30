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
class MatchResultController extends AdminController {
	
	public function _initialize(){
		
		parent::_initialize();
	}
	
	
	public function index(){
		$where[status] = 1;
		
		$listsCount = M('match_person')->where($where)->count();
		$Page       = new \Think\Page($listsCount,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		
		$list = M('match_person')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		
		
		$this->assign('list',$list);
	    $this->assign('_page',$show);
		
		
		$this->display();
	}
   //分次列表
	public function edit(){
		
		
		
		
		$pt = $_REQUEST;
		$where["bhy_match_numlist.parent_id"] = $pt['topid'];
		$where["bhy_match_numlist.is_end"]    = 1;
		
		$list = M('match_numlist')->join('bhy_match_type as m on m.id=bhy_match_numlist.typeid')->where($where)->field('m.title,m.id as ids,bhy_match_numlist.*')->select();
		//echo M('match_numlist')->getLastSql();exit;
		
		
		
		foreach($list as $ke=>$ve){
			$map[numlist_id] = $ve[id];
			$map[typeid]     = $ve[ids];
			$resultCount = M('match_result')->where($map)->count();
			
			//查询每次的题目数
			$mapp[typeid] = $ve[ids];
			$mapp[pid]    = array('eq',0);
			$matchCount = M('match')->where($mapp)->count();
			if($resultCount==$matchCount){
				$list[$ke][result] = '<font style="color:blue;">已完成</font>';
			}else if($resultCount<$matchCount){
				$list[$ke][result] = '<font style="color:red;">未完成</font>';
			}
			
		}
		
		$this->assign('list',$list);
		$this->assign('pt',$pt);
	    
		$this->display();
	}
	
	//详情
	public function details(){
		$pt = $_REQUEST;
		$where["m.numlist_id"] = $pt['numid'];
		$where["m.uid"]        = $pt['uid'];
		$where["bhy_match.pid"]= array('eq',0);
		
		$list = M('match')->join('bhy_match_result as m on m.wt_id=bhy_match.id')->where($where)->field('bhy_match.title,bhy_match.answer as ans,bhy_match.id as ids,m.*')->order('bhy_match.id asc')->select();
		
		//p($list);
		
		foreach($list as $ke=>$ve){
			$map[pid] = $ve[ids];
			$list[$ke][xuanxiang] = M('match')->where($map)->order('xuxiang asc')->select();
		}
		$this->assign('list',$list);
		$this->display();
	}
	

	
	
	
	
	
	//更换状态
	public function changeStatus(){
		$get = $_REQUEST;
		if($get['method']=='resumeUser'){
			
			$meet = M('meeting')->where('status=1')->find();
			
			if(!empty($meet)){
				
				$this->error('只能启用一个活动！');
				
			}else{
				
				$resu = M('meeting')->where('id='.$get[id])->save(array('status'=>1));
				
				if($resu){
					
					$this->success('启用成功！');
					
				}else{
					
					$this->error('启用失败');
					
				}
				
			}
		}
		
		if($get['method']=='forbidUser'){
			
			$resu = M('meeting')->where('id='.$get[id])->save(array('status'=>0));
				
			if($resu){
				
				$this->success('禁用成功！');
				
			}else{
				
				$this->error('禁用失败');
				
			}
			
		}
		
		
		
	}
   
	



	
	//删除
	
	public function del(){
		$id = I('request.id');
		
		if(!empty($id)){
			if(is_array($id)){
				$where[id] = array('in',implode(',',$id));
			}else{
				$where[id] = $id;
			}
			$resu = M('match_person')->where($where)->delete();
			if($resu){
				$this->success('删除成功！',U('index'));
			}else{
				$this->error('删除失败');
			}
			
		}else{
			
			$this->error('非法操作！');
			
		}
		
	}
	
	
	
	
   


    

    //编辑时字段处理
	public function _before_edit(){
		$_POST['begin_time']=strtotime($_POST['begin_time']);
		$_POST['end_time']=strtotime($_POST['end_time']);
		
	}
	
}
