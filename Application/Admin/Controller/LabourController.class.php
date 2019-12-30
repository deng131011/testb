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
class LabourController extends AdminController {
	
	
	public function _initialize(){
		
		
		
		parent::_initialize();
	}
	

	public function index(){
		
		
		
		
		
		
        //未查看
		$LabourCounts = M('Labour')->where('status=1 and pid=0 and pcid=0')->group('cid')->count();
		$this->assign('LabourCounts',$LabourCounts);
		
		 //已查看
		$ansCounts = M('Labour')->where('status=2 or status=1 and pid=0')->group('cid')->count();
		$this->assign('ansCounts',$ansCounts);
		
		
		$get = $_REQUEST;
		$this->assign('get',$get);
        if($get[status]>0){
			$where[status] = $get[status];
		}else{
			$where[status] =array(array('eq',1),array('eq',2),'or') ;
		}
		
		
		$where[cid] = array('gt',0);
		$where[pcid] = array(array('eq',0),array('eq',''),'or');

		//分页
		$listsCount = M('Labour')->where($where)->group('cid')->count();
		$Page       = new \Think\Page($listsCount,30);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		
		//$lists = M('Labour')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->group('cid')->select();
		$lists = M('Labour')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->group('cid')->select();
		
		$this->assign('list',$lists);
		$this->assign('get',$_GET);
		$this->assign('_page',$show);
		$this->display(); 

	}
	
	//定时请求列表
	public function ajaxindexlist(){
		if($_POST[status]>0){
			$where[status] = array('eq',$_POST[status]);
		}else{
			$where[status] = array(array('eq',1),array('eq',2),'or') ;
		}
		
		
		$where[cid] = array('gt',0);
		$where[pcid] = array(array('eq',0),array('eq',''),'or');
		$lists = M('Labour')->where($where)->order('id desc')->limit(0,30)->group('cid')->select();
		$n=1;
		
		
		foreach($lists as $kt=>$vt){
			
			$str .= '<tr>
            <td><input class="ids" type="checkbox" name="id[]" value="'.$vt[id].'" /></td>
			<td>'.$n.'</td>
			<td>'.modelField($vt[cid],'usermember','open_id').'</td>
			<td>'.modelField($vt[cid],'usermember','nickname').'</td>
			<td>';
			if(modelField($vt[cid],'usermember','headimgurl')!=''){
				$str .= '<a href="'.modelField($vt[cid],'usermember','headimgurl').'" target="_blank"><img src="'.modelField($vt[cid],'usermember','headimgurl').'" style="width:80px; height:50px;" /></a>';
			}else{
				$str .= '暂无头像';
			}
			$str .= '</td>
			<td>'.date('Y-m-d H:i:s',$vt[create_time]).'</td>
			<td>'.onlineStatus($vt[id]).'</td>
			<td>
                <a href="'.U("Labour/edit?cid=".$vt[cid]).'">查看</a>
				<a href="'.U('Labour/del?cid='.$vt[cid]).'" class="confirm ajax-get">删除</a></td></tr>';
            $n++;  
		}
		
        $this->ajaxReturn($str);
          		
		
		
	}


	//回复
	public function edit(){
		//清楚判断微信发送的文件
		file_put_contents('putfile.txt','');
		file_put_contents('htputfile.txt','');
		
		
            if(IS_POST){
				
                $pt = $_POST;
                $data['content'] = $pt['content']; 
                $data['status'] = 3; 
				$data['pid'] = $pt['pid']; 
				$data['pcid'] = $pt['cid']; 
				$data['mid'] = is_login(); 
				 
                M('Labour')->where('cid='.$pt[cid])->save(array('status'=>3)); 
				
                $result = M('Labour')->add($data); 

                if($result){
					//微信推送
					$rsu = M('labour')->find($pt['pid']);
					$tt = time();
					$chatt = $tt-$rsu[create_time];
					if($chatt>=120){
						$touser = modelField($pt['cid'],'usermember','open_id');
					
						$template_id = '59e2TktP62JWKpcm2lP6-QF3IKTTkqqRATdo1n37qW0';
						
						$dat = '在线客服回复了您的提问';
						
						$data=array(
							'first'=>array(
									'value'=>urlencode("您好！您有一条在线客服消息，请点击查看。"),
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
									'value'=>'可以直接点击进入查看。',
									'color'=>'#459ae9'
							),
								
						);
						 $url = 'http://bsgwxs.59156.cn/Home/online/personidea';//跳转链接
						 $sender = wx_company_apply($touser,$url,$data,$template_id,$topcolor='#459ae9');
					}
					
				
				    $msg[status] = 1;
					
				}else{
					$msg[status] = 2;
				}

				$this->ajaxReturn($msg);
			}else{
				
				file_put_contents('htputfile.txt','');
				file_put_contents('putfile.txt','');
				
				//session('sureReplyNews',null);//清楚首页消息弹框缓存
				
                 $cid = I('request.cid');
				 $this->assign('cid',$cid);
				//已查看
				$arrter['status']=1;
				$arrter['cid']=$cid;
				$arrter['pcid']=0;
				$listty = M('labour')->where($arrter)->select();
				if(!empty($listty)){
					$idarr = array();
					foreach($listty as $kt=>$vt){
					  $idarr[] = $vt['id'];	
					}
					$artyu[id] = array('in',implode(',',$idarr));
					M('labour')->where($artyu)->save(array('status'=>2));
				}
				
                $arrtt['pid'] = 0;
                $arrtt['cid'] = $cid;
                $arrtt['status'] = array('gt',-1);
                $list = M('labour')->where($arrtt)->order('id asc')->select();
                foreach ($list as $ku => $vu) {
                	$arrtts['pid'] =$vu[id];
	                $arrtts['status'] = array('gt',-1);
                	$list[$ku][soncon] = M('labour')->where($arrtts)->order('id asc')->select();
                }
                
                $this->assign('list',$list);

				$this->display();
			}



	}
	
	
	//定时请求
	public function ajaxinfo(){
		
		    $cid = I('request.cid');
			 $this->assign('cid',$cid);
			//已查看
			$arrter['status']=1;
			$arrter['cid']=$cid;
			$arrter['pcid']=0;
			$listty = M('labour')->where($arrter)->select();
			if(!empty($listty)){
				$idarr = array();
				foreach($listty as $kt=>$vt){
				  $idarr[] = $vt['id'];	
				}
				$artyu[id] = array('in',implode(',',$idarr));
				M('labour')->where($artyu)->save(array('status'=>2));
			}
			
			$arrtt['pid'] = 0;
			$arrtt['cid'] = $cid;
			$arrtt['status'] = array('gt',-1);
			$list = M('labour')->where($arrtt)->order('id asc')->select();
			foreach ($list as $ku => $vu) {
				$arrtts['pid'] =$vu[id];
				$arrtts['status'] = array('gt',-1);
				$list[$ku][soncon] = M('labour')->where($arrtts)->order('id asc')->select();
			}
		    
			foreach($list as $kt=>$vt){
				if($vt[txtimes]!=0){
					$str .= '<div class="onlinelist"><p class="timetop">'.date('Y-m-d H:i',$vt[txtimes]).'</p></div>';
				}
				if(count($list)==$kt+1){
					$str .= '<input type="hidden" id="dataids" value="'.$vt[id].'">';
				}
				$str .= '<div class="onlinelist">
             <div class="imgleft"><img src="'.modelField($vt[cid],'usermember','headimgurl').'"/></div><div class="textleft"><p>'.$vt[content].'</p></div></div>';
			    
				foreach($vt[soncon] as $kj=>$vj){
					
					$str .= '<div class="onlinelist"><div class="imgright"><img src="/Public/Home/images/logo.jpg"/></div><div class="textright"><p>'.htmlspecialchars($vj[content]).'</p></div></div>';
					
				}
				
				
				
				
			}
			$this->ajaxReturn($str);
			
			
		
	}
	

   //删除
	public function del(){
		$id = I('request.id');
		if(is_array($id)){
			$where[id] = array('in',implode(',',$id));
			$where[pid] = array('in',implode(',',$id));
		}else{
			$where[id] = array('eq',$id);
			$where[pid] = array('eq',$id);
		}
		
		$delResult = M('Labour')->where($where)->delete();
		if($delResult){
			$this->success('删除成功！',U('index'));
		}
	}

    //定时查询是否有未回复消息
	public function noReplyNews(){
		
		if(IS_POST){
			
			$news = session('sureReplyNews');
			
			if($_POST[type] == 'news'){
			    if($news==''){
					$where['status'] = array(array('eq',1),array('eq',2),'or');
					$where['pid'] = array('eq',0);
					
					$list = M('labour')->where($where)->order('create_time desc')->group('cid')->limit(6)->select();
					foreach($list as $ke=>$ve){
						
						$str .= '<a href="/admin/Labour/edit?cid='.$ve[cid].'"><p style="margin-top:3px; border-bottom:1px dashed #dddddd;">"'.modelField($ve[cid],'usermember','nickname').'"，待回复<span style="float:right;">（'.date('Y-m-d',$ve[create_time]).'）</span></p></a>' ;
						
					}
					session('sureReplyNews','news',3600);//一个小时失效
					if(!empty($list)){
						$msg['msg'] = $str;
						$msg['status'] = 100;
					}else{
						$msg['msg'] = '暂无';
						$msg['status'] = 200;
					}
				}else{
					$msg['status'] = 300;
				}
				
				
				$this->ajaxReturn($msg);
				
				
			}
			
		}
		
		
	}

	

}
