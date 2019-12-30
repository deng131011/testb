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
class ApplyController extends HomeController {
	
	public function _initialize(){

	    parent::_initialize();
    }
	

	//系统首页
    public function index(){
		$where[status] = 1;
        $list = M('contact_data')->where($where)->order('sort asc')->select();
		$this->assign('list',$list);
        $this->display();
    }
	
	
	//申请表单
	public function forms(){
		if(IS_POST){
			$pt = $_POST;
			$pt['create_time'] = strtotime($pt['create_time']);
			$pt['visit_time'] = strtotime($pt['visit_time']);
			//还差一个申请人id
			session('applyInfos',$pt);
			if(!empty(session('applyInfos'))){
				$msg[status] = 100;
			}else{
				$msg[status] = 200;
				$msg[msg] = '提交失败！';
			}
			
			$this->ajaxReturn($msg);
		}else{
			$this->display();
		}
		
	}
	
	//安排要求列表
	public function applylist(){
		$applyInfos = session('applyInfos');
		
		if(IS_POST){
			$apply = M('apply');
			$request = M('apply_request');
			$apply->startTrans(); $request->startTrans();//开启事物
			
			$resua = $apply->add($applyInfos);
			
			//添加访客要求
			$_POST['apply_id'] = $resua;
			$resub = $request->add($_POST);
			
			if($resua && $resub){
				$apply->commit(); $request->commit();
				session('applyInfos',null);
				$msg[status] = 100;
				$msg[msg] = '提交成功！';
			}else{
				$apply->rollback(); $request->rollback();
				$msg[status] = 200;
				$msg[msg] = '提交失败！';
			}
			$this->ajaxReturn($msg);
		}else{
			if(empty($applyInfos)){
				$this->redirect('forms');
				exit;
			}


			//酒店价格
			$wh[status] = 1;
			$wh[isnav]  = 2;
			$wh[pid]  = 28;
            $hostList = M('category')->where($wh)->order('sort asc')->select();

            $this->assign('hostList',$hostList);
			
			$this->display();
		}
		
		
	}
	

    //申请记录
    public function record(){
        //还差一个员工id条件
        $where['status'] = array('gt',-1);   

        $list = M('apply')->where($where)->order('create_time desc')->select();

        $this->assign('list',$list);

        $this->display();

    }

    //申请记录详情一
     public function detaila(){
        $id = I('request.id');
        if($id>0){

        	$data = M('apply_check')->where('apply_id='.$id)->find();

        	$this->assign('vo',$data);

        }else{
        	$this->error('非法操作');
        }
        

        $this->display();

     }

     //申请记录详情二
     public function detailb(){
        $id = I('request.id');
        if($id>0){

        	$data = M('apply')->where('id='.$id)->find();

        	$this->assign('vo',$data);

        }else{
        	$this->error('非法操作');
        }
        

        $this->display();

     }


}