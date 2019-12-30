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
class InvestController extends AdminController {
	
	public function _initialize(){
		//查询视频分类
		$whet[status] = 1;
		$whet[pid] =array('neq',1);
		$whet[id] =array('neq',1);
		$videoType = M('category')->where($whet)->order('sort asc')->select();
		$this->assign('videoType',sortOut($videoType));
		
		//返回分类ID
		$typeid = I('request.typeid');
		$this->assign('typeid',$typeid);
		
		
		
		parent::_initialize();
	}
	public function index(){

		$typeid = I('request.typeid');
		$title = I('request.title');

		if($typeid>0){
			$where['typeid'] = $typeid;
		}
		if($title!=''){
			$where['title'] = array('like','%'.$title.'%');
		}
		//p($where);exit;
		$where['status'] = array('neq',-1);
		$lists = M('Invest')->where($where)->select();
		
		//p($lists);
		$this->assign('list',$lists);
		$this->assign('get',$_GET);
		$this->display();

	}
    //添加
	public function add(){
		if(IS_POST){
			$result = D('Invest')->create();
			if($result){
				$_POST[create_time] = time();
				$addResult = D('Invest')->add($_POST);
				if($addResult){

					$this->success('新增成功',U('Invest/index',array('typeid'=>$_POST[typeid])));
				}
			}else{
				$this->error(D('Invest')->getError());
			}
		}
		$this->display();
	}

	//编辑
	public function edit(){
        if(IS_POST){
			$result = D('Invest')->create();
			if($result){
				
				$addResult = D('Invest')->save($_POST);
				if($addResult){
					$this->success('修改成功',U('Invest/index',array('typeid'=>$_POST[typeid])));
				}
			}else{
				$this->error(D('Invest')->getError());
			}
		}else{
			$map['id'] = $_GET['id'];
			$map['status'] = 1;
            $editdata = M('invest')->where($map)->find();
			$this->assign('vo',$editdata);
			$this->display();
		}

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
	public function unlimitforlevel($cate,$html='------',$pid=0,$level=0,$topid=0){
		//$cate  查询出来的数据
		//$html   标识符
		//$pid    上级ID
		//$level   层级
		$arr = array();  //定义返回的数组
		foreach($cate as $v){
			if($v['pid'] == $pid){  //等于顶级分离
				$v['topid'] = 0;
				if($level == 0){
					$topid = $v['id'];
				}
				if($level != 0){
					$v['topid'] = $topid;
				}
	
				$v['level'] = $level + 1;     //层级加1
				if($level > 1){
					$html = str_replace('|' , '' , $html);
				}
	
				$v['html'] = str_repeat($html,$level);   //str_repeat  字符串重复次数
				//var_dump($v['id']);
				$lis = M('column')->where('pid=' . $v['id'])->select();
				if($lis){
					$v['num'] = 1;
				}else{
					$v['num'] = 0;
				}
				$arr[]=$v;
				//array_merge  把多个数组合并为一个数组
				$arr = array_merge($arr,$this->unlimitforlevel($cate,$html,$v['id'],$level+1,$topid));
	
			}
		}
		return $arr;
	
	}
	//新闻回收站
	
	public function recycle(){
		
		$map['status']  =   -1;
		
		$list = $this->lists(M('article'),$map,'update_time desc');	
		
		$this -> assign('list',$list);
		$this -> display();
		
	}
	
	public function _before_add(){
		
		$_POST['create_time']=strtotime($_POST['create_time']);
		
	}
	public function _before_edit(){
		
		
		
		
		
	}

}
