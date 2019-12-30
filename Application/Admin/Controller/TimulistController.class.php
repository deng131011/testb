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
class TimulistController extends AdminController {
	
	public function _initialize(){
		$pid = I('request.pid',0,'intval');
		$this->assign('pid',$pid);
		
		//查询题目期数
		$where['pid'] = array('eq',1);
		$where['status'] = array('eq',1);
		$timeList = M('timutype')->where($where)->order('sort asc')->select();
		$this->assign('timeList',$timeList);
		
		//查询题目类型
		$where['pid'] = array('eq',3);
		$timulxList = M('timutype')->where($where)->order('sort asc')->select();
		$this->assign('timulxList',$timulxList);
		
		parent::_initialize();
	}
	
    //首页
	public function index(){
		
		
		
		$typeid = I('request.typeid');
		if(empty($typeid)){
			$typeid = 0;
		}
		$this->assign('typeid',$typeid);
		
		
		$where[pid]    = array('eq',0);
		$where[status] = array('gt',-1);
		
		//分页
		$listsCount = M('timulist')->where($where)->count();
		$Page       = new \Think\Page($listsCount,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		
		$list = M('timulist')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('list',$list);
		$this->assign('_page',$show);
		
		
		$this->display();
		
	}
	
	//增加
	public function add(){
		
		if(IS_POST){
			$pt = $_POST;
			if($pt['title']==''){
				$this->error('标题不能为空');
			}
			if($pt['timetype']==''){
				$this->error('请选择问卷期数');
			}
			if($pt['timutype']==''){
				$this->error('请选择题目类型');
			}
			$pt['create_time'] = time();
			
			$res = M('timulist')->add($pt);
			
			if($res){
				$this->success('添加成功',U('index'));
			}else{
				$this->error('添加失败');
			}
			
			
		}else{
		
			
			$this->display();
		}
		
	}
	
	//编辑
	public function edit(){
		$survey = M('timulist');
		if(IS_POST){
			
		    $pt = $_POST;
			if($pt['title']==''){
				$this->error('标题不能为空');
			}
			if($pt['timetype']==''){
				$this->error('请选择问卷期数');
			}
			if($pt['timutype']==''){
				$this->error('请选择题目类型');
			}
			$pt['create_time'] = time();
			
			$res = M('timulist')->save($pt);
			
			if($res){
				$this->success('编辑成功',U('index'));
			}else{
				$this->error('编辑失败');
			}
			
			
		}else{
			$pt = $_REQUEST;
			$where[id]  = array('eq',$pt[id]);
			$vo = $survey->where($where)->find();
			
			$this->assign('vo',$vo);
			$this->display();
		}
		
	}
	
	
	//禁用启用
	public function changeStatus(){
		
		$pt = $_REQUEST;
		$where[id] = $pt[id];
		if($pt[type]=='qiyon'){
			$res = M('timulist')->where($where)->save(array('status'=>1));
			if($res){
				$this->success('启用成功');
			}else{
				$this->error('启用失败');
			}
		}else if($pt[type]=='jinyon'){
			$res = M('timulist')->where($where)->save(array('status'=>0));
			if($res){
				$this->success('禁用成功');
			}else{
				$this->error('禁用失败');
			}
		}
		
		
	}
	
	
	//删除
	public function del(){
		
		$pt = $_REQUEST;
		//p($pt[id]);
		if(!empty($pt)){
			
			if(is_array($pt[id])){
				$maps[id] = array('in',implode(',',$pt[id]));
				$res = M('timulist')->where($maps)->delete();
			}else{
				$maps[id] = $pt[id];	
				$res = M('timulist')->where($maps)->delete();	
			}
			if($res){
				$this->success('删除成功',U('index'));
			}else{
				$this->error('删除失败',U('index'));
			}
			
		}else{
			
			$this->error('非法操作',U('index'));
			
		}
		
		
	}
	
	
	
	/*
	*
	*题目选项
	**/
	
	public function sonlist(){
		$pid = $_GET['pid'];
		$vv = M('timulist')->find($pid);
		$this->assign('vv',$vv);
		
		
		//分页
		$where['status'] = array('gt',-1);
		$where['pid'] = array('eq',$pid);
		$listsCount = M('timulist_son')->where($where)->count();
		$Page       = new \Think\Page($listsCount,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		
		//列表
		$list = M('timulist_son')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('list',$list);
		$this->assign('_page',$show);
		
		$this->display();
	}
	//新增选项
	public function addson(){
		
		if(IS_POST){
			
			$pt = $_POST;
			if($pt['title']==''){
				$this->error('标题不能为空');
			}
			$pt['create_time'] = time();
			
			$res = M('timulist_son')->add($pt);
			
			if($res){
				$this->success('添加成功',U('sonlist',array('pid'=>$pt[pid])));
			}else{
				$this->error('添加失败');
			}
			
			
		}else{
			
			$this->display();
		}
		
	}
	
	//编辑选项
	public function editson(){
		
		if(IS_POST){
			
			$pt = $_POST;
			if($pt['title']==''){
				$this->error('标题不能为空');
			}
			
			$res = M('timulist_son')->save($pt);
			
			if($res){
				$this->success('编辑成功',U('sonlist',array('pid'=>$pt[pid])));
			}else{
				$this->error('编辑失败');
			}
			
		}else{
			
			$id = I('request.id');
			$vo = M('timulist_son')->find($id);
			$this->assign('vo',$vo);
			
			$this->display();
		}
		
	}
	
	//删除选项
	public function delson(){
		
		$pt = $_REQUEST;
		//p($pt[id]);
		if(!empty($pt)){
			
			if(is_array($pt[id])){
				$maps[id] = array('in',implode(',',$pt[id]));
				$res = M('timulist_son')->where($maps)->delete();
			}else{
				$maps[id] = $pt[id];	
				$res = M('timulist_son')->where($maps)->delete();	
			}
			if($res){
				$this->success('删除成功',U('sonlist',array('pid'=>$pt[pid])));
			}else{
				$this->error('删除失败',U('sonlist',array('pid'=>$pt[pid])));
			}
			
		}else{
			
			$this->error('非法操作',U('sonlist',array('pid'=>$pt[pid])));
			
		}
		
		
	}
	
	
	
	
}
