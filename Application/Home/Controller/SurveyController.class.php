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
class SurveyController extends HomeController {
	
	public function _initialize(){
		
		$wxconfig = wx_share_init();
		$news = array(
		       "title"=>'博思格满意度调查',
		       "brief"=>'博思格满意度调查',
		       "picture"=>'http://bsgwxs.59156.cn/Public/Home/images/wxfx.jpg',
		       "link"=>'http://bsgwxs.59156.cn/survey/index?id=13'
			   
		 );
		$this->assign('news',$news);
		$this->assign('wxconfig',$wxconfig);
		
		//查询省份
		$hww['status'] = array('eq',1);
		$listprovince = M('province')->where($hww)->order('id asc')->select();
		
	    $this->assign('listprovince',$listprovince);
		
		//选择行业
		$whew['status'] = array('eq',1);
		$whew['pid']    = array('eq',43);
	    $listhangye = M('category')->where($whew)->order('sort asc')->select();
		
	    $this->assign('listhangye',$listhangye);
	   
	    parent::_initialize();
    }
	

	//系统首页
    public function index(){
	    $id = I('request.id',13,'intval');
	    $data = M('category')->where('id='.$id)->find();
		$this->assign('data',$data);
		
		//增加日志记录
		$openid = session('user_open_id');
		$infoarr = session('user_open_info');
		$timearr = dayTimeLong();
		if($openid!=''){
		$whe['open_id'] = array('eq',$openid);
		$whe['create_time'] = array(array('egt',$timearr['start_time']),array('elt',$timearr['end_time']));
		$rett = M('survey_log')->where($whe)->find();
		if(!empty($rett)){
			
			$whj['id'] = array('eq',$rett['id']);
			M('survey_log')->where($whj)->setInc('urla',1);
		}else{
			$ertarr['open_id'] = $openid;
			$ertarr['nickname'] = $infoarr['nickname'];
			$ertarr['headimg'] = $infoarr['headimgurl'];
			$ertarr['urla']    = 1;
			$ertarr['create_time'] = time();
			M('survey_log')->add($ertarr);
		}
		}
        $this->display();
    }
	
	//客户信息表单
	public function forms(){
		
		if(IS_POST){
            $pt = $_POST;
			
			$uid = session('bsg_user_id');
			//$uid = 28;
			$usermember = M('usermember');
			$dyresult = M('dyresult');
			
			//修改用户信息
			$pt['update_time'] = time();
			
			$rtts['id'] = array('eq',$uid);
			$resu = $usermember->where($rtts)->save($pt);
			
           	//插入dyresult结果表
			$ert[cid]       = $uid;
			$ert[dt_status] = 0;
			$dyresu = $dyresult->where($ert)->order('id desc')->find();
			if(empty($dyresu)){
				$data[create_time] = time();
				$data[cid]         = $uid;
				$data[content]     = array('eq','');
				$dyinfoer = $dyresult->add($data);
			}else{
				$dyinfoer = 1;
			}
            
            if($dyinfoer){
                $msg[status] = 1;
                
            }else{
           	    $msg[status] = 2;
            }

           $this->ajaxReturn($msg); 

		}else{
			//查询之前是否已经答题了一半
			$uids = session('bsg_user_id');
			$erty[dt_status]=0;
			$erty[cid]      =$uids;
			$resarr = M('dyresult')->where($erty)->order('id desc')->find();
			
			if(!empty($resarr)){
				header("Location: http://".$resarr[urls]); 
				//$this->redirect($resarr[urls]);
				exit;
			}
			
			 //客户类型
			$where[status] = 1;
			$where[pid] = 34;
			$list = M('category')->where($where)->order('sort asc')->select();		
			$this->assign('list',$list); 
			
			$signPackage = get_signature();//自定义分享方法,获取签名值数组
			$this->assign('signPackage',$signPackage);
			
			
			//增加日志记录
			$openid = session('user_open_id');
			$infoarr = session('user_open_info');
			$timearr = dayTimeLong();
			if($openid!=''){
			
			$whe['open_id'] = array('eq',$openid);
			$whe['create_time'] = array(array('egt',$timearr['start_time']),array('elt',$timearr['end_time']));
			$rett = M('survey_log')->where($whe)->find();
			if(!empty($rett)){
				
				$whj['id'] = array('eq',$rett['id']);
				M('survey_log')->where($whj)->setInc('urlb',1);
			}else{
				$ertarr['open_id'] = $openid;
				$ertarr['nickname'] = $infoarr['nickname'];
				$ertarr['headimg'] = $infoarr['headimgurl'];
				$ertarr['urlb']    = 1;
				$ertarr['create_time'] = time();
				M('survey_log')->add($ertarr);
			}
			}
			
			//$this->redirect('lista');
			$this->display();
		}
		
    }
	

    //列表1
	public function lista(){
		
		$uid = session('bsg_user_id');
		//$uid = 28;
        $ert[cid]       = $uid;
		$ert[dt_status] = 0;
		$dyresu = M('dyresult')->where($ert)->order('id desc')->find();
		
		if(empty($dyresu)){
		   $this->redirect('forms');	
		   exit;
		}
		//获取当前链接
		$host = $_SERVER['HTTP_HOST'];
		$url  = $_SERVER['PHP_SELF'];
		$urlarr[urls] = $host.$url;
		M('dyresult')->where('id='.$dyresu[id])->save($urlarr);
		
		$surinfo = M('usermember')->find($uid);
        //判断是否是设计院
		if($surinfo['usertype']==38){
			$arer[id] = array('not in','5,6');
			//$map[pid] = array('not in','5,6');
		}
		
        $id = I('request.id');
		$arer[status] = 1;
		$arer[pid]    = 0;
		
        if($id==''){
        	$vo = M('survey')->where($arer)->order('sort asc')->find();
        	$id = $vo['id'];
        }
        
        $map[status] = 1;
        $map[pid] = $id;
        $list = M('survey')->where($map)->order('sort asc')->select();
        $this->assign('list',$list);
        $this->assign('topid',$id); 


        //查询标题
         
        $datatitle = M('survey')->find($id);
        $this->assign('datatitle',$datatitle); 

		$this->display();
	}


    //列表存入session中

    public function addlist(){
            $uid = session('bsg_user_id');
		    //$uid = 28;
			$ert[cid]       = $uid;
			$ert[dt_status] = 0;
			$dyresu = M('dyresult')->where($ert)->order('id desc')->find();
            $pt = $_POST;
		    
		    $erty[parent_id] = $dyresu[id];
		    $erty[topid]     = $pt[topid];
		    $listbresu = M('dyresult_list')->where($erty)->select();
		    if(empty($listbresu)){
				foreach($pt[wtid] as $kt=>$vt){
				  $data[$kt][satisfaction] = $pt['satisfaction'.$vt];
				  $data[$kt][important]    = $pt['important'.$vt];
				  $data[$kt][parent_id]    = $dyresu[id];
				  $data[$kt][wtid]         = $vt;
				  $data[$kt][topid]        = $pt[topid];
			    }
				
			    $result = M('dyresult_list')->addAll($data);
			}
		     
            $surinfo = M('usermember')->find($uid);
		    if($surinfo[usertype]==38){
			   $wher['id'] = array('not in','5,6'); 
		    }
		   
           $wher['sort'] = array('gt',$pt[sort]);
           $wher['status'] = 1; 
           $wher['pid'] = 0; 
           $data = M('survey')->where($wher)->order('sort asc')->find();
           if(!empty($data)){
                $this->redirect('/Home/Survey/lista?id='.$data[id]);  
           }else{
                 $this->redirect('Survey/ideas');
           }
          
    }

    //建议与意见
    public function ideas(){
		
		
		    $uid = session('bsg_user_id');
			//$uid = 28;
			$ert[cid]       = $uid;
			$ert[dt_status] = 0;
			$dyresuer = M('dyresult')->where($ert)->order('id desc')->find();

    	if(IS_POST){
			
			if(empty($dyresuer)){
				$this->redirect('forms');	
		        exit;
			}
			
            //存入调查信息
            $dydata[content]   = $_POST['content']; 
            $dydata[dt_status] = 1; 
            $dydata[urls]      = 'bsgwxs.59156.cn/index.php/survey/index'; 
            $dyResu = M('dyresult')->where('id='.$dyresuer[id])->save($dydata);
			
            if($dyResu){
				$msg['status'] = 1;
			}else{
				$msg['status'] = 2;
			}
           
            $this->ajaxReturn($msg);

    	}else{
			
			//获取当前链接
			$host = $_SERVER['HTTP_HOST'];
			$url = $_SERVER['PHP_SELF'];
			$urlarr['urls'] = $host.$url;
			
			$mpto['id'] = $dyresuer[id];
			M('dyresult')->where($mpto)->save($urlarr);
			
    		$this->display();
    	}

    }
	
	
	
	//分享怎加操作日志记录
	public function ajaxAddfxlog(){
		
		if(IS_POST){
			//增加日志记录
			$pt = $_POST;
			
			$openid = session('user_open_id');
			$infoarr = session('user_open_info');
			$timearr = dayTimeLong();
			
			$whe['open_id'] = array('eq',$openid);
			$whe['create_time'] = array(array('egt',$timearr['start_time']),array('elt',$timearr['end_time']));
			$rett = M('survey_log')->where($whe)->find();
			
			if($pt['type']=='fxurla'){
				
				if(!empty($rett)){
					
					$whj['id'] = array('eq',$rett['id']);
					M('survey_log')->where($whj)->setInc('fxurla',1);
				}else{
					$ertarr['open_id'] = $openid;
					$ertarr['nickname'] = $infoarr['nickname'];
					$ertarr['headimg'] = $infoarr['headimgurl'];
					$ertarr['fxurla']    = 1;
					$ertarr['create_time'] = time();
					M('survey_log')->add($ertarr);
				}
				
			}else if($pt['type']=='fxurlb'){
				
				if(!empty($rett)){
					
					$whj['id'] = array('eq',$rett['id']);
					M('survey_log')->where($whj)->setInc('fxurlb',1);
				}else{
					$ertarr['open_id'] = $openid;
					$ertarr['nickname'] = $infoarr['nickname'];
					$ertarr['headimg'] = $infoarr['headimgurl'];
					$ertarr['fxurlb']    = 1;
					$ertarr['create_time'] = time();
					M('survey_log')->add($ertarr);
				}
				
			}
			
			
			
		}
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
    

}