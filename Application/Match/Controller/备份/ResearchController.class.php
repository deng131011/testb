<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Match\Controller;
use OT\DataDictionary;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class ResearchController extends HomeController {
	
	public function _initialize(){
		
		//查询部门
		$where['status'] = array('eq',1);
		$where['pid']    = array('eq',9);
		$bmlist = M('timutype')->where($where)->order('sort asc')->select();
	    $this->assign('bmlist',$bmlist);
	    parent::_initialize();
    }
	

	//系统首页
    public function index(){

        $openid = session('bsg_user_openid');
         
		if(IS_POST){
			//判断是否已经提交过
			
			$whe['status'] = array('eq',1);
			$whe['pid']    = array('eq',1);
			$resu = M('timutype')->where($whe)->order('sort desc')->find();
			
			$where['open_id']   = array('eq',$openid);	
		    //$where['status']    = array('eq',1);	
		    $where['timetype']  = array('eq',$resu['id']);	
		    $where['is_complete']  = array('eq',1);	
			$tuhs = M('timulist_user')->where($where)->find();
			if(!empty($tuhs)){
				$msg['status'] = 201;
				$msg['msg']    = '对不起，您已经填写过！';
			}else{
				$msg['status'] = 200;
				$msg['msg']    = '继续下一步';
			}
			$this->ajaxReturn($msg);exit;
			
		}else{
			
           $this->display();
		}
		
    }
	
	
	
	
	
	//用户信息
	public function forms(){
	    $openid = session('bsg_user_openid');
		//调查期数
	    $whe['status'] = array('eq',1);
		$whe['pid']    = array('eq',1);
		$resu = M('timutype')->where($whe)->order('sort desc')->find();
		
		if(empty($resu)){
			$this->error('非法操作！',U('index'));exit;
		}


	
	    if(IS_POST){
			
			$pt = $_POST;
			
			$pt['create_time'] = time();
			$pt['open_id']     = $openid;
			$pt['timetype']    = $resu['id'];
			$tus = M('timulist_user')->add($pt);
			
			if($tus){
				$msg['status'] = 200;
				$msg['pid']    = $tus;
				$msg['msg']    = '信息添加成功';
			}else{
				$msg['status'] = 201;
				$msg['msg']    = '信息添加失败';
			}
			$this->ajaxReturn($msg);exit;
			
		}else{
			
		    $where['open_id']   = array('eq',$openid);	
		    //$where['status']    = array('eq',1);	
		    $where['timetype']  = array('eq',$resu['id']);	
		    $where['is_complete']  = array('eq',0);	
			$tuhs = M('timulist_user')->where($where)->find();

			if(!empty($tuhs)){
				$this->redirect('Research/timulist?pid='.$tuhs[id]);exit;
			}
			
		    $this->display();	
			
		}
	    
	}
	
	//题目列表
	public function timulist(){
		$get = $_GET;
		$this->assign('get',$get);
		//查询问卷所属分类
		$whe['status'] = array('eq',1);
		$whe['pid']    = array('eq',1);
		$resu = M('timutype')->where($whe)->order('sort desc')->find();
		$this->assign('resu',$resu);
		
		//查询题目
		$where['timetype'] = array('eq',$resu['id']);
		$where['status']   = array('eq',1);
		$list = M('timulist')->where($where)->order('sort asc')->select();
		
		//查询选项
		foreach($list as $ke=>$ve){
			$map['status'] = array('eq',1);
			$map['pid'] = array('eq',$ve['id']);
			$listson[$ve[id]] = M('timulist_son')->where($map)->select();
		}
		$this->assign('list',$list);
		$this->assign('listson',$listson);
		
		$this->display();	
	}
	
	
	//添加题目答案
	public function addproblem(){
		
		
		$pt = $_POST;
		//判断是否已经答过题
		$sghs['id'] = array('eq',$pt[pid]);
		$sghs['is_complete'] = array('eq',1);
		$rgst = M('timulist_user')->where($sghs)->find();
		if(!empty($rgst)){
			$msg['status'] = 202;
			$msg['msg']    = '您已经提交过';
		    $this->ajaxReturn($msg);exit;
		}
		
		//p($pt);
		$kk = 0;
		foreach($pt['timu_id'] as $ke=>$ve){
			//判断单选题和多选题
			if($pt['answer'.$ve.'_type']==1){
				foreach($pt['answer'.$ve] as $kr=>$vr){
					//判断填空题是否有填
					if($pt['tktype'.$ve.'-'.$vr]>0){
						if($pt['content'.$ve.'-'.$vr]==''){
							$msg['status'] = 201;
			                $msg['msg']    = '请检查，有一个必填项没填';
							$this->ajaxReturn($msg);exit;
						}
					}
					
					$data[$kk]['pid'] = $pt['pid'];
					$data[$kk]['timu_id'] = $ve;
					$data[$kk]['timutype']  = $pt['timutype'.$ve];
					$data[$kk]['create_time'] = time();
					
					$data[$kk]['answer_id'] = $vr;
				    $data[$kk]['content']   = $pt['content'.$ve.'-'.$vr]!=''?$pt['content'.$ve.'-'.$vr]:'';
					$data[$kk]['star_score'] = 0;
			        $kk++;
				}
			}
			//判断星号题
			if($pt['answer'.$ve.'_type']==2){
				foreach($pt['answer'.$ve] as $ks=>$vs){
					$data[$kk]['pid'] = $pt['pid'];
					$data[$kk]['timu_id'] = $ve;
					$data[$kk]['timutype']  = $pt['timutype'.$ve];
					$data[$kk]['create_time'] = time();
					
					$data[$kk]['answer_id']  = $vs; 
					$data[$kk]['content'] = '';
					$data[$kk]['star_score'] = $pt['star_score'.$ve.'-'.$vs]; 
					$kk++;
				}
				
			}
			
			//判断建议题
			if($pt['answer'.$ve.'_type']==3){
				$data[$kk]['pid'] = $pt['pid'];
				$data[$kk]['timu_id'] = $ve;
				$data[$kk]['timutype']  = $pt['timutype'.$ve];
				$data[$kk]['create_time'] = time();
				
				$data[$kk]['answer_id'] = 0;
				$data[$kk]['content'] = $pt['content'.$ve];
				$data[$kk]['star_score'] = 0;
				
				$kk++;
			}
		}
		
		$result = M('timulist_result');
		$user   = M('timulist_user');
		$result->startTrans();  $user->startTrans(); //开启事物
		
		//p($data);
		//添加结果
		$resu = $result->addAll($data);
		
		
		//修改信息表的完成状态
		$dfs['id'] = $pt['pid'];
		$dfs['is_complete'] = 1;
		$ret = $user->save($dfs);
		
		if($resu && $ret){
			$result->commit();  $user->commit(); //提交事物
			$msg['status'] = 200;
			$msg['msg']    = '感谢您百忙之中完成此调查表，您所提出的每一项意见，都将成为我们改进和努力的方向。';
		}else{
			$result->rollback();  $user->rollback(); //回滚事物
			$msg['status'] = 201;
			$msg['msg']    = '提交失败';
		}
		$this->ajaxReturn($msg);exit;
		
		
	}
	
	
	
	
	

}