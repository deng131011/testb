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
class ConsultController extends AdminController {
	

	public function index(){
        //未查看
		$ConsultCounts = M('Consult')->where('status=1 and pid=0')->count();
		$this->assign('ConsultCounts',$ConsultCounts);
		
		 //已查看、未回复
		$ansCounts = M('Consult')->where('status=2 or status=1 and pid=0')->count();
		$this->assign('ansCounts',$ansCounts);
		
		
		$get = $_REQUEST;
		$this->assign('get',$get);
        if($get[status]>0){
			$where[status] = $get[status];
		}else{
			$where[status] = array('gt',0);
		}
		$where[pid] = 0;
		

		//分页
		$listsCount = M('Consult')->where($where)->count();
		$Page       = new \Think\Page($listsCount,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		
		$lists = M('Consult')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$lists);
		$this->assign('get',$_GET);
		$this->assign('_page',$show);
		$this->display();

	}


	//回复
	public function edit(){
            if(IS_POST){
				$pid = I('request.pid');
				$_POST[create_time] = time();
				$result = D('Consult')->add($_POST);
                if($result){
					$mdrt = M('Consult')->where('id='.$pid)->save(array('status'=>3));
					if($mdrt){
						$this->success('回复成功！',U('index'));
					}
				}else{
					$this->error('回复失败！',U('index'));
				}
			}else{
				$id = I('request.id');
				$map[id] = $id;
				$map[pid] = array('eq',0);
				
				$editdata = D('Consult')->where($map)->find();
				if($editdata[status]==1){
					//已查看
				    D('Consult')->where($map)->save(array('status'=>2));
				}
				
				
				
				$this->assign('vo',$editdata);
				$this->display();
			}



	}

   //删除
	public function del(){
		$id = I('request.id');
		if(is_array($id)){
			$where[id] = array('in',implode(',',$id));
			$map[pid] = array('in',implode(',',$id));
		}else{
			$where[id] = array('eq',$id);
			$map[pid] = array('eq',$id);
		}
		$delResult = M('Consult')->where($where)->delete();
		$delResults = M('Consult')->where($map)->delete();
		if($delResult){
			$this->success('删除成功！',U('index'));
		}
	}


	
	

	

}
