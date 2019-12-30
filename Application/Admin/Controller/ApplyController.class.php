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
class ApplyController extends AdminController {
	

	public function index(){
        //未审核数
		$ApplyCounts = M('Apply')->where('status=0')->count();
		$this->assign('ApplyCounts',$ApplyCounts);
		$get = $_REQUEST;
		$this->assign('get',$get);
		if($get[status]==''){
			$where[status] = array('gt',-1);
		}
		if($get[status]=='no'){
			$where[status] = 0;
		}
		if($get[status]=='sucs'){
			$where[status] = 1;
		}
		if($get[status]=='fail'){
			$where[status] = 2;
		}
		if($get[name]!=''){
			$where[name] = array('like','%'.$get[name].'%');
		}
       
		//分页
		$listsCount = M('Apply')->where($where)->count();
		$Page       = new \Think\Page($listsCount,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		
		$lists = M('Apply')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$lists);
		$this->assign('get',$_GET);
		$this->assign('_page',$show);
		$this->display();

	}
   
   

	//审核
	public function check(){
            if(IS_POST){
				$pt = $_POST;
				
				$apply = M("apply");
				$applyCheck = M("apply_check");
				$apply->startTrans(); $applyCheck->startTrans();//开启事物
				
				//修改apply状态
				$saveApply = $apply->where('id='.$pt[id])->save(array('status'=>$pt[status]));
				
				//apply_check增加审核信息
				$data[status] = $pt[status];
				$data[apply_id] = $pt[id];
				$data[check_time] = time();
				$data[name] = get_user_name();
				$data[wel_leader] = $pt[wel_leader];
				$data[send_leader] = $pt[send_leader];
				$data[pt_person] = $pt[pt_person];
				$data[market] = $pt[market];
				$data[sale_name] = $pt[sale_name];
				

				if($pt[remark]==''){
					$data[remark] = '审核成功，同意参观！';
				}else{
					$data[remark] = $pt[remark];
				}
				
				$addCheck = $applyCheck->add($data);
				
				if($saveApply && $addCheck){
					$apply->commit(); $applyCheck->commit();//提交事物
					
					//微信推送
				    $uider = modelField($pt[id],'apply','name_id');
					$touser      = modelField($uider,'usermember','open_id');
					
					$template_id = 'S75F7cGb4UmwXn1a1btQwROiLQG6IXdDp9OK1hfnpsE';
					
					if($pt[remark]==''){
						$dat = '审核成功';
					}else{
						$dat = '审核失败';
					}
					
					$data=array(
						'first'=>array(
								'value'=>urlencode("您好！您的博思格工厂参观申请审核结果通知。"),
								'color'=>"#459ae9"
						),
						'keyword1'=>array(
								'value'=>urlencode('工厂参观申请审核结果'),
								'color'=>'#459ae9'
						),
						'keyword2'=>array(
								'value'=>urlencode($dat),
								'color'=>'#459ae9'
						),
						'keyword3'=>array(
								'value'=>urlencode(date('Y-m-d H:i:s',time())),
								'color'=>'#459ae9'
						),
						
						'remark'=>array(
								'value'=>'请到博思格工厂参观申请，您的申请记录里查看。',
								'color'=>'#459ae9'
						),
							
					);
				$url = 'http://bsgwxs.59156.cn/Home/Apply/record';//跳转链接
				$sender = wx_company_apply($touser,$url,$data,$template_id,$topcolor='#459ae9');
				
					$this->success('提交成功！',U('Apply/index'));
				}else{
					$apply->rollback(); $applyCheck->rollback();//事物回滚
					$this->error('提交失败！');
				}
				
				
			}else{
				$id = I('request.id');
				$map["bhy_apply.id"] = $id;
				//$map["bhy_apply.pid"] = array('eq',0);
				$editdata = D('Apply')->join('bhy_apply_request as r on r.apply_id=bhy_apply.id')->field('bhy_apply.name,bhy_apply.create_time,bhy_apply.visit_time,bhy_apply.person_num,bhy_apply.leave_time,bhy_apply.company,bhy_apply.arrive_time,bhy_apply.customer,bhy_apply.customer_info,bhy_apply.rasion,bhy_apply.id as ids,bhy_apply.status,r.*')->where($map)->find();
				//p($editdata);
				$this->assign('vo',$editdata);
				
				$this->display();
			}



	}

   //删除
	public function del(){
		$id = I('request.id');
		if(is_array($id)){
			$where[id] = array('in',implode(',',$id));
			$map[apply_id] = array('in',implode(',',$id));
		}else{
			$where[id] = array('eq',$id);
			$map[apply_id] = array('eq',$id);
		}
		$delResult = M('Apply')->where($where)->delete();
		$delResults = M('Apply_check')->where($map)->delete();
		if($delResult){
			$this->success('删除成功！',U('Apply/index'));
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
		$_POST['mid']=UID;
		$_POST['create_time']=strtotime($_POST['create_time']);
		
	}
	public function _before_edit(){
		$_POST['create_time']=strtotime($_POST['create_time']);
		
		$_POST['mid']=$_POST['mid'];
		
		
	}

}
