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
class OnlineController extends HomeController {
	
	public function _initialize(){
	   
	    parent::_initialize();
    }
	

	//系统首页
    public function index(){
		
		
		
		//exit;
		
        $this->redirect('onlinelist');
    }
	
	
	//在线客服
	public function onlinelist(){
       $where[status] = 1;
       $list = M('online')->where($where)->order('id desc')->select(); 

       $this->assign('list',$list);
	   
	 
	   
	   $this->display();	
	}

	//在线客服详情
	public function details(){
	   $id = I('request.id');
       $data = M('online')->find($id); 

       $this->assign('vo',$data);
	   $this->display();	
	}
	
	//查询平台是否回复了咨询人
	public function ifReply(){
		
		//$where['pid'] = array('eq',0);
		
		//M('labour')->select();
		
	}
	
	
	

    //人工服务下的意见
	public function personidea(){
		
		  $memberid = session('bsg_user_id');
		  //$memberid = 28;
		  if(IS_POST){
	          $pt = $_POST;
			 
	          //先查询是否有这个问题
			     $tixtimes = '<div class="onlist online_one"><div class="left_jian"><img src="/Public/Home/images/left.png"/></div><div class="touxiang"><img src="/Public/Home/images/logo.jpg"/></div><div class="online_text texta"><p>您好，欢迎联系博思格钢铁。我们将会有专员对您进行一对一的咨询。我们的服务时间为周一至周五：8:30-17:30</p></div></div>'; 
				 
				 $tixtimesfont = '您好，欢迎联系博思格钢铁。我们将会有专员对您进行一对一的咨询。我们的服务时间为周一至周五：8:30-17:30';
				 
				 $rgtixing = '<div class="onlist online_one"><div class="left_jian"><img src="/Public/Home/images/left.png"/></div><div class="touxiang"><img src="/Public/Home/images/logo.jpg"/></div><div class="online_text texta"><p>您好，请稍等，正在为您转接人工服务。</p></div></div>';
			     
				 $rgtixingfont = '您好，请稍等，正在为您转接人工服务。';
	            
                $t = time();
				
				//先判断是否是周末
				
				if((date('w') == 6) || (date('w') == 0)){
					$strbv = $tixtimes; //博思格自动返回的内容
					$zdongcont = $tixtimesfont;
					
					
				}else{
					$workstart = strtotime(date('Y-m-d').' 08:30');
					$workend   = strtotime(date('Y-m-d').' 17:30');
					$dqtimes   = time();
					if($dqtimes<$workstart || $dqtimes>$workend){
						$strbv = $tixtimes; //博思格自动返回的内容
						$zdongcont = $tixtimesfont;
					}else{
						$strbv = $rgtixing;
						$zdongcont = $rgtixingfont;
						
						$start_time = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));  //当天开始时间
						$end_time = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t)); //当天结束时间 
						
						$timetjs[create_time] = array(array('gt',$start_time),array('lt',$end_time));
						$timetjs[pcid] = $memberid;
						$timetjs[txcontent] = 1;
						$timedataere = M('labour')->where($timetjs)->select();
						
					}
				}
				
				//echo  M('labour')->getLastSql();exit;
                //查询第一天的发送时间
				
				$arty[create_time] = array(array('gt',$start_time),array('lt',$end_time));
				$arty[cid] = $memberid; 
				$arty[txtimes] = array('gt',0);
				$shijians = M('labour')->where($arty)->find();
				
				if(empty($shijians)){
                     $_POST['txtimes'] = $t;
                     $stras = '<div class="onlist online_time"><p><span>'.date('Y-m-d H:i',$t).'</span></p></div>';
				}
  

	          //插入我咨询的问题
                $_POST['content'] = $pt[content];
			    $_POST['cid'] = $memberid; 
                $_POST['create_time'] = $t; 
				
                $resu = M('labour')->add($_POST);
				
				
				if($resu){
					 //查看是否给人工客服推送了微信消息
						 $newdate = time();
						 $obj['wx_time'] = array(array('neq',''),array('gt',0),'or');
						 $vow = M('labour')->where($obj)->order('wx_time desc')->find();
						 if(empty($vow)){
							 M('labour')->where('id='.$resu)->save(array('wx_time'=>$newdate));
							$touser = 'oQX6FwqKuIdt5fqUCxf0Oyagxam0'; //客服open_id(心是晴朗的)
							//$touser = 'oQX6Fwr6HCtXME9tkIgFZ0WT57iM'; //客服open_id(桃小姐)
							
					
							$template_id = '59e2TktP62JWKpcm2lP6-QF3IKTTkqqRATdo1n37qW0';
							
							$dat = '有人咨询人工客服';
							
							$data=array(
								'first'=>array(
										'value'=>urlencode("您好！有人咨询人工客服，请在后台查看。"),
										'color'=>"#459ae9"
								),
								'keyword1'=>array(
										'value'=>urlencode('消息待查看'),
										'color'=>'#459ae9'
								),
								
								'keyword2'=>array(
										'value'=>urlencode(date('Y-m-d H:i:s',time())),
										'color'=>'#459ae9'
								),
								'remark'=>array(
										'value'=>'请登录后台查看。',
										'color'=>'#459ae9'
								),
									
							);
							 $url = '';//跳转链接
							 $sender = wx_company_apply($touser,$url,$data,$template_id,$topcolor='#459ae9');
						 }else{
							$chatime = $newdate-$vow['wx_time'];
							if($chatime>300){
								 M('labour')->where('id='.$resu)->save(array('wx_time'=>$newdate));
								 
								$touser = 'oQX6FwqKuIdt5fqUCxf0Oyagxam0'; //客服open_id
								$template_id = '59e2TktP62JWKpcm2lP6-QF3IKTTkqqRATdo1n37qW0';
								$dat = '有人咨询人工客服';
								$data=array(
									'first'=>array(
											'value'=>urlencode("您好！有人咨询人工客服，请在后台查看。"),
											'color'=>"#459ae9"
									),
									'keyword1'=>array(
											'value'=>urlencode('消息待查看'),
											'color'=>'#459ae9'
									),
									
									'keyword2'=>array(
											'value'=>urlencode(date('Y-m-d H:i:s',time())),
											'color'=>'#459ae9'
									),
									'remark'=>array(
											'value'=>'请登录后台查看。',
											'color'=>'#459ae9'
									),
										
								);
								 $url = '';//跳转链接
								 $sender = wx_company_apply($touser,$url,$data,$template_id,$topcolor='#459ae9');
							 }
						 }
				}
				
				
				
				if(empty($timedataere)){
					 $strb = $strbv;//返回的内容
					 
					 $armap[pcid] = $memberid;
					 $armap['pid'] = $resu;
					 $armap[content] =  $zdongcont;
					 $armap[txcontent] = 1;
					 $armap[status] = 1;
					 $armap[create_time] = time();
					 M('labour')->add($armap);
				}else{
					
					 if($resu){
						
					 }else{
						$strb = '<div class="onlist online_one"><div class="left_jian"><img src="/Public/Home/images/left.png"/></div><div class="touxiang"><img src="/Public/Home/images/logo.jpg"/></div><div class="online_text texta"><p>您的信息发送失败！</p></div></div>';

						 M('labour')->where('id='.$resu)->save(array('status'=>1));

						 
						 $armap[pcid] = $memberid;
						 $armap['pid'] = $resu;
						 $armap[content] = '您的信息发送失败！';
						 $armap[status] = 1;
						 $armap[create_time] = time();
						 M('labour')->add($armap); 
					 }
					 
				}
			 
	          $stra = '<div class="onlist online_two"><div class="right_jian"><img src="/Public/Home/images/right.png"/></div><div class="touxiang"><img src="'.getMemberImg().'"/></div><div class="online_text textb"><p>'.$pt[content].'</p></div></div>'; //自己输入的东西
			  
			  
			  
			 
	           if($stras!=''){
	           	  $strs = $stras.$stra.$strb;
	           	  $msg['msg'] = $strs;
	           	  $msg['id'] = $resu;
	           }else{
	           	  $strs = $stra.$strb;
	           	  $msg['msg'] = $strs;
	           	  $msg['id'] = $resu;
	           }
			   
               
	        $this->ajaxReturn($msg);

		  }else{
             $mprt[status] = array('gt',-1);
             $mprt[cid]    = $memberid;
             $mprt[pid]    = 0;
             $lists = M('labour')->where($mprt)->order('id asc')->select();
             foreach ($lists  as $ky => $vy) {
             	$mmper['pid'] = $vy[id];
             	$mmper[status] = array('gt',-1);
             	$lists[$ky][soncont] = M('labour')->where($mmper)->order('id asc')->select();
             }
             $this->assign('lists',$lists);
		  	 $this->display();	
		  }
	   
	}
	
	//判断是否有回复消息
	public function ifReturnNews(){
		
		if(IS_POST){
			
			if($_POST[status]=='qingqiu'){
			  //5分钟没有回复微信推送
				$putfile = file_get_contents('putfile.txt');
				if($putfile==''){
						ignore_user_abort(true);           // 即使Client断开(如关掉浏览器)，PHP脚本也可以继续执行.
						set_time_limit(0);             // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
						
						ob_flush();
                        flush();
						
						sleep(300);
						
						//$mtse = time()-300;
						//$mgh['create_time'] = array('egt',$mtse);
						//$mgh['pid'] = array('eq',0);
						//$mgh['status'] = array('eq',1);
						
						
						$infarr = M('labour')->find($_POST['id']);
						
						
						if(!empty($infarr)){
							
							$touser = 'oQX6FwqKuIdt5fqUCxf0Oyagxam0'; //客服open_id
					
							$template_id = '59e2TktP62JWKpcm2lP6-QF3IKTTkqqRATdo1n37qW0';
							
							$dat = '有人咨询人工客服';
							
							$data=array(
								'first'=>array(
										'value'=>urlencode("您好！有人咨询人工客服，请在后台查看。"),
										'color'=>"#459ae9"
								),
								'keyword1'=>array(
										'value'=>urlencode('消息待查看'),
										'color'=>'#459ae9'
								),
								
								'keyword2'=>array(
										'value'=>urlencode(date('Y-m-d H:i:s',time())),
										'color'=>'#459ae9'
								),
								'remark'=>array(
										'value'=>'请登录后台查看。',
										'color'=>'#459ae9'
								),
									
							);
							 $url = '';//跳转链接
							 $sender = wx_company_apply($touser,$url,$data,$template_id,$topcolor='#459ae9');
							 
							file_put_contents('putfile.txt',$_POST['id'],FILE_APPEND);//写入文件
							ignore_user_abort(false);
							
						}
						
				}
			
			}
			
		}
		
		
		
		
		
		
	}
	
	
	


	//定时请求
	public function ajaxinfo(){
		 $memberid = session('bsg_user_id');
		 //$memberid = 21;
		 
		 //查询新的一条是否回复，超过10秒没有回复，自动回复
		 $artj[cid] = $memberid;
		 $artj[pid] = 0;
		 $ckresult = M('labour')->where($artj)->order('id desc')->find();
		 if(!empty($ckresult)){
			  if($ckresult[status]!=3 && $ckresult[txcontent]!=2){
				$stt = time();
				$sjbetween = $stt-$ckresult['create_time'];
				if($sjbetween>10){
					 $armap['pcid'] = $memberid;
					 $armap['pid'] = $ckresult[id];
					 $armap['content'] =  '由于咨询人员较多，请您耐心等待，或留下您的问题，我们将会第一时间回复您，谢谢。';
					 $armap[status] = 1;
					 $armap[create_time] = time();
					 M('labour')->add($armap);//添加提醒
					 
					 M('labour')->where('id='.$ckresult[id])->save(array('txcontent'=>2));//修改提醒后的状态
				}

			
		   }
		 }
		
		 
		 
		 $mprt[status] = array('gt',-1);
		 $mprt[cid]    = $memberid;
		 $mprt[pid]    = 0;
		 $lists = M('labour')->where($mprt)->order('id asc')->select();
		 foreach ($lists  as $ky => $vy) {
			$mmper['pid'] = $vy[id];
			$mmper[status] = array('gt',-1);
			$lists[$ky][soncont] = M('labour')->where($mmper)->order('id asc')->select();
		 }
		// p($lists);exit;
         foreach($lists as $kt=>$vt){
			 if($vt[txtimes]!=0){
				$str .='<div class="onlist online_time"><p><span>'.date('Y-m-d H:i',$vt[txtimes]).'</span></p></div>'; 
			 }
			 
			 $str .='<div class="onlist online_two">
                <div class="right_jian"><img src="/Public/Home/images/right.png"/></div>
                <div class="touxiang"><img src="'.getMemberImg().'"/></div>
                <div class="online_text textb"><p>'.$vt[content].'</p></div></div>';
			
			foreach($vt['soncont'] as $kj=>$vj){
				
				$str .='<div class="onlist online_one">
               <div class="left_jian"><img src="/Public/Home/images/left.png"/></div>
               <div class="touxiang"><img src="/Public/Home/images/logo.jpg"/></div>
               <div class="online_text texta"><p>'.$vj[content].'</p></div></div>';
				
			}
			 
			 
		 } 		 
		
		
		$this->ajaxReturn($str);
		
	}
	

	
    //人工服务意见填写人信息
	public function personinfo(){
        $uid = session('bsg_user_id');
		$perInfo = session('personIdeas');
		if(IS_POST){

		        if(empty($perInfo)){
		        	$msg[status] = 3;
		        }else{
		        	$_POST['content'] = $perInfo[content];
		        	$_POST['create_time'] = time();
		        	$resu = M('labour')->add($_POST);
					
					//查询是否用户信息有变动
					$arrm['id'] = $uid;
					$arrm['username'] = $_POST['name'];
					$arrm['mobile'] = $_POST['mobile'];
					$arrm['company'] = $_POST['company'];
					$userinfo = M('usermember')->where($arrm)->find();
					if(empty($userinfo)){
						M('usermember')->save($arrm);
					}
					
		        	if($resu){
		        		session('personIdeas',null);
		        		$msg[status] = 1;

		        	}else{
		        		$msg[status] = 2;
		        	}
		        	
		        }
		        $this->ajaxReturn($msg);

		}else{

            if(empty($perInfo)){
		        $this->redirect('personidea');
		        exit;
		    }
			//用户信息
			$userdata = M('usermember')->where('id='.$uid)->find();
			$this->assign('userdata',$userdata);

			$this->display();	
		}
        
         
	}



    //意见及建议下的填写意见页面
    public function ideaone(){

          if(IS_POST){
	          $pt = $_POST;
	          session('opinionIdeas',$pt);
	          $mty = session('opinionIdeas');
	          if(!empty($mty)){
	             
	             $msg[status] = 1;

	          }else{
	          	 $msg[status] = 2;
	          }
	         $this->ajaxReturn($msg);

		    }else{
		  	   $this->display();	
		    }

    }


    //意见及建议下的填写人信息
	public function ideatwo(){
        $uid = session('bsg_user_id'); 
		$perInfo = session('opinionIdeas');
		if(IS_POST){

		        if(empty($perInfo)){
		        	$msg[status] = 3;
		        }else{
		        	$_POST['content'] = $perInfo[content];
		        	$_POST['create_time'] = time();
		        	$resu = M('opinion')->add($_POST);
					
					//查询是否用户信息有变动
					$arrm['id'] = $uid;
					$arrm['username'] = $_POST['name'];
					$arrm['mobile'] = $_POST['mobile'];
					$arrm['company'] = $_POST['company'];
					$userinfo = M('usermember')->where($arrm)->find();
					if(empty($userinfo)){
						M('usermember')->save($arrm);
					}
					
		        	if($resu){
		        		session('opinionIdeas',null);
		        		$msg[status] = 1;

		        	}else{
		        		$msg[status] = 2;
		        	}
		        	
		        }
		        $this->ajaxReturn($msg);

		}else{

            if(empty($perInfo)){
		        $this->redirect('ideaone');
		        exit;
		    }
			//用户信息
			$userdata = M('usermember')->where('id='.$uid)->find();
			$this->assign('userdata',$userdata);

			$this->display();	
		}
        
         
	}




}