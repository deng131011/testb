<?php

namespace Home\Controller;
use OT\DataDictionary;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class MeetingController extends HomeController {
	
	public function _initialize(){

        //查询邀请人
       $map["bhy_meeting_inviter.status"] = 1; 
       $map["m.status"] = 1;
       $peList = M('meeting_inviter')->join('bhy_meeting as m on m.id=bhy_meeting_inviter.meet_type')->where($map)->group('bhy_meeting_inviter.title')->order('bhy_meeting_inviter.sort asc')->field('bhy_meeting_inviter.*')->select();
       $this->assign('peList',$peList);
	   
	   //查询公司
	   $mapp["bhy_meeting_company.status"] = 1; 
       $mapp["m.status"] = 1;
	   $comList = M('meeting_company')->join('bhy_meeting as m on m.id=bhy_meeting_company.meet_type')->where($mapp)->group('bhy_meeting_company.title')->order('bhy_meeting_company.sort asc')->field('bhy_meeting_company.*')->select();
       $this->assign('comList',$comList);


       //活动id
       $hd_id = M('meeting')->where('status=1')->find();
       $this->assign('hd_id',$hd_id);

	   
	    parent::_initialize();
    }
	

	//系统首页
    public function index(){
		
        if(IS_POST){
          
            //查询活动是否开始或者结束、
            $times = time();
            $ret = M('meeting')->where('id='.$_POST['hd_id'])->find();

            if($times>=$ret[begin_time] && $times<=$ret[end_time]){

            	$uid = session('bsg_user_id');
				//$uid = 28;
	            $whe['hd_id'] = $_POST['hd_id'];
	            $whe['cid'] =  $uid;
	            $resu = M('meeting_signin')->where($whe)->find();
	           
	            if(!empty($resu)){
	            	$msg[status] = 2;
	            	$msg[msg] = '对不起！你已经签过到！';
	            }else{
                   
                   //修改客户信息
	            	$kh['username'] = $_POST['username'];
	            	$kh['mobile'] = $_POST['mobile'];
	            	$kh['email'] = $_POST['email'];
	            	$kh['id'] = $uid;
	            	M('usermember')->save($kh);

                   //添加签到信息
	               $arrt[cid] = $uid;
	               $arrt[hd_id] = $_POST['hd_id'];
	               $arrt[inviter] = $_POST['inviter'];
	               $arrt[company] = $_POST['company'];
				   if($_POST['othercompany']!=''){
					   $arrt[othercompany] = $_POST['othercompany'];
				   }
	               $arrt['create_time'] = time();
	               $result = M('meeting_signin')->add($arrt);
	               if($result){
	               	  $msg[status] = 1;
	            	  $msg[msg] = '签到成功！';
	               }else{
	                  $msg[status] = 2;
	            	  $msg[msg] = '签到失败！';
	               }

	            }

            }else{
            	$msg[status] = 2;
	            $msg[msg] = '活动还未开始，或者已经结束。';
            }
            
            $this->ajaxReturn($msg);

        }else{
			
			
			$mm[status] = 1;
			$vo = M('meeting')->where($mm)->find();
			$this->assign('vo',$vo);
			
        	 $this->display();
        }
       
       
    }
	
	

	//答题页面
	public function problem(){
        //$uid = session('bsg_user_id');
        $uid = 28;
        $hd_id = I('request.hd_id');
		
		
       
        if($hd_id>0){
             
             $arrt['cid'] = $uid;
             $arrt['hd_id'] = $hd_id;
             $data = M('meeting_signin')->where($arrt)->find();
			 
			 //查询是否已经答过题
		
			$wtInfos = M('meeting_problemlist')->where($arrt)->select();
			$this->display();
			 
             if(empty($data)){
             	//$this->error('请您先签到！',U('wood/index'));
             	//exit;
             }else if(!empty($wtInfos)){

              //  $this->error('您已经答过题！',U('wood/index'));
               // exit;

             }else{

             	//查询活动题目
             	$where['hd_id']  = $hd_id;
                $where['status'] = 1;
                $where['pid'] = 0;
                $list = M('meeting_problem')->where($where)->order('sort asc')->select();
               
                $this->assign('list',$list);
                $this->assign('hd_id',$hd_id);
                $this->display();

             }
           
        }else{

           $this->error('非法操作！',U('index'));

        }

       
	}

    
    //添加答题结果
    public function addmeetProblem(){
            $uid = session('bsg_user_id');
            //$uid = 28;
            $pt = $_POST;
			
            //查询是否已经答过题
			$arrt['cid'] = $uid;
            $arrt['hd_id'] = $pt['hd_id'];
			$wtInfos = M('meeting_problemlist')->where($arrt)->select();
            if(!empty($wtInfos)){
				$msg[msg] = '您已经答过题！';
				$msg[status] = 2;
				$this->ajaxReturn($msg);
				exit;
			}
			
             foreach ($pt['problem_id'] as $ke => $ve) {
             	$data[$ke][problem_id]   = $ve;
             	$data[$ke][problem_type] = $pt[problem_type.$ve];	
                $data[$ke][cid]          = $uid;	
                $data[$ke][hd_id]        = $pt[hd_id];
                $data[$ke][create_time]  = time();
             	if($pt[result.$ve]==''){
             	   $data[$ke][result] = 0;	
             	}else{
             	   $data[$ke][result] = $pt[result.$ve];
             	}
                
             	if($pt[content.$ve]==''){
             	   $data[$ke][content] = '';	
             	}else{
             	   $data[$ke][content] = $pt[content.$ve];
             	}
             	
             	
             }
             //添加问题结果
			 $meetWtb  = M('meeting_problemlist');
			 $meetSign = M('meeting_signin');
			 $meetWtb->startTrans(); $meetSign->startTrans(); //开启事物
             $resu  = $meetWtb->addAll($data);
			 
			$datwhe['cid'] = array('eq',$uid);
			$datwhe['hd_id'] = array('eq',$pt[hd_id]);
			$rrtuy = $meetSign->where($datwhe)->save(array('dt_status'=>1));
			 

            if($resu&&$rrtuy){
				
				$meetWtb->commit(); $meetSign->commit(); //提交事物
				
				//抽奖
				$dttty[hd_id] = $pt[hd_id];
				$dttty[uid]   = 0;
				$numInfros = M('win_lists')->where($dttty)->order('sort asc')->find(); 
				
				if(!empty($numInfros)){
					
					$mpyu[id] = array('eq',$numInfros[id]);
					
					$resutyui = M('win_lists')->where($mpyu)->save(array('uid'=>$uid));
					
					if($resutyui&&$numInfros[rander]==1){
						$msg[status] = 1;
						$msg[msg]    = '<font style="color:#f00;font-size:17px;">恭喜您已中奖</font>';
					}else{
						$msg[msg]    = '<font style="font-size:17px;">很遗憾，没有中奖！</font>';
                        $msg[status] = 2;
					}
					
				}else{
					
					
					$msg[msg]    = '<font style="font-size:17px;">很遗憾，没有中奖！</font>';
                    $msg[status] = 2;
				} 
				
				
              
             }else{
                $meetWtb->rollback(); $meetSign->rollback(); //回滚事物
                $msg[status] = 2;
                $msg[msg]    = '提交失败';

             }

           $this->ajaxReturn($msg);
            
    } 
    //递归返回抽奖人是第几个抽奖
	public function diguiWinnum($hd_id,$uid){
		$winnum = M('meeting_signin')->where('hd_id='.$hd_id)->order('win_num desc')->find();
		
	    $win_num = $winnum['win_num']+1;
		$fgh[hd_id]   = $hd_id;
		$fgh[win_num] = $win_num;
		$resus = M('meeting_signin')->where($fgh)->find();
		if(!empty($resus)){
			$this->diguiWinnum($hd_id,$uid);
		}else{
			$winmms = M('meeting_signin')->where('cid='.$uid.' and hd_id='.$hd_id)->save(array('win_num'=>$win_num));//及时修改当前人的抽奖位置
			if($winmms){
				return $win_num;
			}else{
				$this->diguiWinnum($hd_id,$uid);
			}
		}
	}

}