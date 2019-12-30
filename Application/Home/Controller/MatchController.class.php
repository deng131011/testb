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
class MatchController extends HomeController {
	
	public function _initialize(){
		
		 //客户类型
		/*$where[status] = 1;
		$where[pid] = 34;
		$list = M('category')->where($where)->order('sort asc')->select();		
		$this->assign('list',$list);*/ 
	   
	    parent::_initialize();
    }
	

	//系统首页
    public function index(){
		
		
        $this->display();
    }
	//填写用户信息
	public function forms(){
		//$ip = getMobileIP();
		$ip = '192.168.0.104';
		
		//查询开启的竞赛主题名称
		$map['status'] = 1;
		$map['pid']    = 0;
		$resu = M('match_type')->where($map)->find();
		//查询是否已经提交过用户信息
		$mapp['ip']      = $ip;
		$mapp['typepid'] = $resu['id'];
		$resuinfo = M('match_person')->where($mapp)->find();
		
		if(IS_POST){
			$pt = $_POST;
			if(!empty($resuinfo)){
				session('match_person_id',$resuinfo[id]);
				$msg[status] = 2;
				$msg[msg]    = '您已经提交过，不需要再次提交';
			}else{
				$pt[create_time] = time();
				$pt[typepid]     = $resu[id];
				$pt[ip]          = $ip;
				$resut = M('match_person')->add($pt);
				if($resut){
					session('match_person_id',$resut);//存入session
					
					$msg[status] = 1;
				    $msg[msg]    = '提交成功';
				}else{
					$msg[status] = 2;
				    $msg[msg]    = '提交失败';
				}
			}
			$this->ajaxReturn($msg);
			
			
		}else{
			
			if(!empty($resuinfo)){
				$this->redirect('numlist');
			}else{
				$this->display();
			}
			
		}
		
    }
	
	
	
	//次数列表
	public function numlist(){
		$uid = session('match_person_id');
		
		//$uid = 28;
		
		//查询开启的竞赛主题名称
		$map['status'] = 1;
		$map['pid']    = 0;
		$resu = M('match_type')->where($map)->find();
		
	    $where[xianshi] = array('eq',1);
	    $where[status]  = array('gt',-1);
	    $where[pid]    = $resu[id];
		$list = M('match_type')->where($where)->order('sort asc')->select();
		foreach($list as $ke=>$ve){
			$whe[typeid]  = $ve[id];
			$whe[uid]     = $uid;
			//$whe[is_join] = 1;
			$infos = M('match_numlist')->where($whe)->find();
			$list[$ke][numlist] = $infos;
			//echo 
			if(!empty($infos)){
				//查询次数下的问题条数
				$tyu[status] = 1;
				$tyu[typeid] = $ve[id];
				$tyu[pid]    = 0;
				$matchCount = M('match')->where($tyu)->count();
				
				//查询答题了几条
				$tyer[typeid] = $ve[id];
				$tyer[uid]    = $uid;
				$resultCount = M('match_result')->where($tyer)->count();
				
				if($resultCount==0 || $resultCount==''){
					$list[$ke][isjoin] = '未参加';
				}else if($resultCount>0 && $resultCount<$matchCount){
					$list[$ke][isjoin] = '<font style="color:#f8a937;">未完成</font>';
				}
				
				
			}else{
				$list[$ke][isjoin] = '未参加';
			}
		}
		
		$this->assign('list',$list);
		
		
		$this->display();
	}
	//问题
	public function problem(){
		$uid = session('match_person_id');
		
		//$uid = 28;
		
		
	    $typeid = I('request.typeid');
		//查询该typeid是否为开放
		$infos = M('match_type')->find($typeid);
		if($infos[status]!=1||$infos[xianshi]==0){
			$this->error('非法操作',U('index'));exit;
		}
		//查询该子集的顶级是否为开放
		
		$infoer = M('match_type')->find($infos[pid]);
		if($infoer[status]!=1){
			$this->error('非法操作',U('index'));exit;
		}
		
		if($typeid>0){
			
			//查询刚进入页面是否添加了match_numlist表。
			$data[uid] = $uid;
		    $data[typeid] = $typeid;
		    $data[parent_id] = $uid;
			$numResu = M('match_numlist')->where($data)->find();
			if(empty($numResu)){
				
				$data[start_time] = time();
				M('match_numlist')->add($data);
			}
		    
			
			
			
			//查询该次数下已经做了哪些题目
			$maps[uid]    = $uid;
			$maps[typeid] = $typeid;
			$resut = M('match_result')->where($maps)->select();
			if(!empty($resut)){
				foreach($resut as $ke=>$ve){
					$idarr .=$ve[wt_id].','; 
				}
				$ids = substr($idarr,0,-1);
				$where[id] = array('not in',$ids);
			}
			
			
			$where["typeid"] = $typeid;
			$where["pid"]    = array('eq',0);
			$where["status"] = array('eq',1);
			$listarr = M('match')->where($where)->select();
			//p($listarr);
			$matchCount = M('match')->where($where)->count();//查询还剩几个
			if($matchCount==0 || $matchCount==''){
				$this->redirect('numlist');//如果剩余次数没有了就直接跳回次数列表
			}
			$this->assign('matchCount',$matchCount);
			
			$mm = array_rand($listarr,1);
			$list = $listarr[$mm];
			
			//查询选项
			$mp['pid'] = $list[id];
			$list['xuanxiang'] = M('match')->where($mp)->order('xuxiang asc')->select();
			
			
			$this->assign('list',$list);
			
			
		}else{
			
			$this->error('非法操作',U('index'));
			
		}
		
		
		$this->display();
	}
	
	//添加答题结果
	public function addproblem(){
		$uid = session('match_person_id');
		//$uid = 28;
	    $pt  = $_POST;
		
		//查询该题是否已经答过
		$rpe[uid]   = $uid;
		$rpe[wt_id] = $pt[wt_id];
		$infot = M('match_result')->where($rpe)->find();
		
		$msg[typeid] = $pt[typeid];
		if(!empty($infot)){
			$msg[status] = 5;
			$msg[msg]    = '此题您已经做过';
			$this->ajaxReturn($msg);exit;
		}
		
		//查询match_numlist表是否有记录
		$mpa[uid]    = $uid;
		$mpa[typeid] = $pt[typeid];
		$numlist = M('match_numlist')->where($mpa)->find();
		
		
		if(empty($numlist)){
			
              //查询match_person用户信息表
			  $mpp[id]     = $uid;
			  $mpp[typepid] = $pt[typepid];
			  $person = M('match_person')->where($mpp)->find();
			  if(empty($person)){
				$msg[status] = 3;
				$msg[msg]    = '您还没有填写用户信息';
				$this->ajaxReturn($msg);exit;
			  }else{
				  //添加match_numlist
				  $data[uid] = $uid;
				  $data[typeid] = $pt[typeid];
				  $data[parent_id] = $person[id];
				  $data[start_time]= time();
				  $resu = M('match_numlist')->add($data);
				  
				  $datarr['numlist_id'] = $resu;
			  }
		}else{
			
			$datarr['numlist_id'] = $numlist[id];
		}
		
		$sureanser = modelField($pt['wt_id'],'match',"answer");//正确答案
		
		
		
		$datarr['wt_id']       = $pt['wt_id'];
		if($pt[type]==2){
			$datarr['answer']  = implode(',',$pt['answer']);
		}else if($pt[type]==1){
			$datarr['answer']  = $pt['answer'];
		}
		
		if($datarr['answer']!=$sureanser){
			$datarr['is_sure']  = 0;
		}else{
			$datarr['is_sure']  = 1;
		}
		
		$datarr['uid']         = $uid;
		$datarr['typeid']      = $pt['typeid'];
		$datarr['create_time'] = time();
		$reter = M('match_result')->add($datarr);
		if($reter){
			if($pt[end_num]==1){
				
				//计算分数
				$score = num_score($datarr['numlist_id'],$uid,$pt['typeid']);
				
				//修改结束时间
				$mptt[id] = $datarr['numlist_id'];
				
				$sadata[end_time] = time();
				$sadata[is_end]   = 1;
				$sadata[score]    = $score;
				M('match_numlist')->where($mptt)->save($sadata);
				
				//计算总时间
				$numTime = M('match_numlist')->where($mptt)->find();
				$times = time_jiange($numTime[end_time],$numTime[start_time]);
				
				
				$msg[score] = $score;
				$msg[times] = $times;
				$msg[num]   = modelField($pt[typeid],"match_type","title");
				$msg[ender] = 1;
			}
			
			
			if($datarr['answer']==$sureanser){
				
				$msg[status] = 1;	
			    $msg[msg]    = '提交成功';
				
			}else{
			
				$msg[status] = 4;
			    $msg[msg]    = '正确答案为：'.$sureanser;
			}
			
			
		}else{
			$msg[status] = 2;
			$msg[msg]    = '提交失败';
		}
		
		//p($msg);exit;
		
		$this->ajaxReturn($msg);exit;
		
		
       // p($pt);
        	   
		
		
	}
	
	
	

}