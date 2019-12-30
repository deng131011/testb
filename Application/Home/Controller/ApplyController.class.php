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
			$pt['visit_time']  = strtotime($pt['visit_time']);
			$pt['arrive_time'] = strtotime($pt['arrive_time']);
			$pt['leave_time']  = strtotime($pt['leave_time']);
			$pt['name_id']     = session('bsg_user_id');
			
			//还差一个申请人id
			session('applyInfos',$pt);
			$res = session('applyInfos');
			if(!empty($res)){
				$msg[status] = 100;
			}else{
				$msg[status] = 200;
				$msg[msg] = '提交失败！';
			}
			
			$this->ajaxReturn($msg);
		}else{
			//申请人信息
			$uid = session('bsg_user_id');
			$userdata = M('usermember')->where('id='.$uid)->find();
			$this->assign('userdata',$userdata);
			
			
			$this->display();
		}
		
	}
	
	//安排要求列表
	public function applylist(){
		$applyInfos = session('applyInfos');
		
		if(IS_POST){
			$p = $_POST;
		
			$apply = M('apply');
			$request = M('apply_request');
			$apply->startTrans(); $request->startTrans();//开启事物
			
			$resua = $apply->add($applyInfos);
			
			//添加访客要求
			$_POST['apply_id'] = $resua;
			
			$resub = $request->add($_POST);
			
			if($resua && $resub){
				$apply->commit(); $request->commit();
				
			//微信推送消息
			$userarr[id] = array('in','100,296');
			
			$openidarr = M('usermember')->where($userarr)->select();
			
			foreach($openidarr as $kh=>$vh){
				$uid         = session('bsg_user_id');
				$touser      = $vh[open_id];
				$username    = modelField($uid,'usermember','username');
				$mobile      = modelField($uid,'usermember','mobile');
				$template_id = '4v_6CitnnDTIvI5RSWEFpsJwmu955E4L9UlrHDJJcZo';
				
				$data=array(
					'first'=>array(
							'value'=>urlencode("您好！您有一条博思格工厂参观申请记录"),
							'color'=>"#459ae9"
					),
					'keyword1'=>array(
							'value'=>urlencode($username),
							'color'=>'#459ae9'
					),
					'keyword2'=>array(
							'value'=>urlencode($mobile),
							'color'=>'#459ae9'
					),
					'keyword3'=>array(
							'value'=>urlencode(date('Y-m-d H:i:s',time())),
							'color'=>'#459ae9'
					),
					'remark'=>array(
							'value'=>'请及时到博思格后台管理系统进行审核。',
							'color'=>'#459ae9'
					),
						
				);
				
				$sender = wx_company_apply($touser,$url='',$data,$template_id,$topcolor='#459ae9');
			}
			
				
				//发送邮件
				$artye[status] =1;
				$artye[email]  =array('neq','');
				$artye[js_email]  =array('eq',1);
				$emailarr = M('contact_data')->where($artye)->order('sort asc')->select();
				
				//邮件内容
	
				
	$content = '<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="wap-font-scale" content="no">
<title></title>
<style>
   *,body{margin:0; padding:0; font-family: Microsoft Yahei,SimSun;} 
   .width95{width:95%; margin:0 auto;}
   .toptitle{text-align:center;}
    table{margin:0 auto; width:100%;  margin-top:20px; margin-bottom:10px; }
   .userinfos .t1{text-align:right; width:30%;}
   .userinfos .t2{width:40%;}
   .userinfos td{height:30px;}
   .proinfos{border-top:1px solid #D3D8E0;}
   .proinfos .t1{text-align:left; padding-left:10%;}
   .proinfos td{border-bottom:1px dashed #D3D8E0; height:40px;}
</style>
</head>
<body>
    
	<div class="widthall userinfos">
	   <div class="width95">
	   <table>
	       <tr>
		      <td class="t1" colspan="2" style="text-align:center;padding-left:0;color:#000;">博思格工厂参观申请</td>
		    </tr>
	       <tr>
		      <td class="t1">申请员工姓名：</td>
			  <td class="t2">'.$applyInfos[name].'</td>
		   </tr>
		   <tr>
		      <td class="t1">申请时间：</td>
			  <td class="t2">'.date('Y-m-d H:i',$applyInfos[create_time]).'</td>
		   </tr>
		   <tr>
		      <td class="t1">客户拜访日期：</td>
			  <td class="t2">'.date('Y-m-d H:i',$applyInfos[visit_time]).'</td>
		   </tr>
		   <tr>
		      <td class="t1">客户到达时间：</td>
			  <td class="t2">'.date('Y-m-d',$applyInfos[arrive_time]).'</td>
		   </tr>
		   <tr>
		      <td class="t1">客户离开时间：</td>
			  <td class="t2">'.date('Y-m-d',$applyInfos[leave_time]).'</td>
		   </tr>
		   <tr>
		      <td class="t1">客户单位名称：</td>
			  <td class="t2">'.$applyInfos[company].'</td>
		   </tr>
		   <tr>
		      <td class="t1">访客姓名：</td>
			  <td class="t2">'.$applyInfos[customer].'</td>
		   </tr>
		   <tr>
		      <td class="t1">客户信息：</td>
			  <td class="t2">'.$applyInfos[customer_info].'</td>
		   </tr>
		   <tr>
		      <td class="t1">来访目的：</td>
			  <td class="t2">'.$applyInfos[rasion].'</td>
		   </tr>
		   <tr>
		      <td class="t1">访客人数：</td>
			  <td class="t2">'.$applyInfos[person_num].'</td>
		   </tr>
	   </table>
	   <table class="proinfos">
	        <tr>
		      <td class="t1" colspan="2" style="text-align:center;padding-left:0;color:#000;">访客安排要求</td>
		    </tr>
	       <tr>
		      <td class="t1">1、是否需要安排宣传资料？</td>
			  <td class="t2">（ '.get_shifou($p[if_data]).'、'.rekStatus($p[data_remark]).' ）</td>
		   </tr>
		   <tr>
		      <td class="t1">2、是否需要安排礼品？</td>
			  <td class="t2">（ '.get_shifou($p[if_gift]).' 、'.rekStatus($p[gift_remark]).'）</td>
		   </tr>
		   <tr>
		      <td class="t1">3、客户是否需要安排接送？</td>
			  <td class="t2">（ '.get_shifou($p[if_send]).' 、'.rekStatus($p[send_remark]).'）</td>
		   </tr>
		   <tr>
		      <td class="t1">4、是否安排访客午餐？</td>
			  <td class="t2">（ '.get_shifou($p[if_lunch]).' 、'.rekStatus($p[lunch_remark]).'）</td>
		   </tr>
		   <tr>
		      <td class="t1">5、是否需要预定酒店？</td>
			  <td class="t2">（ '.get_shifou($p[if_hotel]).' 、'.rekStatus($p[hotel_remark]).'）</td>
		   </tr>
		   <tr>
		      <td class="t1">6、是否有其它特殊要求？</td>
			  <td class="t2">（ '.rekStatus($p[others_remark]).'）</td>
		   </tr>
	   </table>
	   </div>
	</div>
</body>
</html>';			
				
				
				
				
			    foreach($emailarr as $ku=>$vu){
					$to = $vu[email];
					$title = '工厂参观申请';
					$mm = sendMail($to,$title,$content);
				}
				//$to = 'jacky.zhang@bluescope.com';
				//$to = 'DengGaofeng131011@outlook.com';
				//$to = '1223271177.qq.com';
				
				//$title = '工厂参观申请';
				//$mm = sendMail($to,$title,$content);
				
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
		$where['name_id'] = session('bsg_user_id');
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