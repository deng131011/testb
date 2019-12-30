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
class MeetingProblemController extends AdminController {
	
	public function _initialize(){
		
		//活动
		$map[status] = array('gt',-1);
		$meetList = M('meeting')->where($map)->order('sort asc')->select();
        $this->assign('meetList',$meetList);  

		parent::_initialize();

	}
	
    //首页
	public function index(){
		
		
		$where[status] = array('gt',-1);
		$where[pid] = 0;
		//分页
		$listsCount = M('meeting_problem')->where($where)->count();
		$Page       = new \Think\Page($listsCount,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		
		$list = M('meeting_problem')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('list',$list);
		$this->assign('_page',$show);
		
		
		$this->display();
		
	}
	
	//增加
	public function add(){
		
		if(IS_POST){
			$pt = $_POST;
			$metproblem = D('MeetingProblem');
			if($metproblem->create()){
                
                $arr['title']  =  $pt['title'];
                $arr['hd_id']  =  $pt['hd_id'];
                $arr['type']   =  $pt['type'];
                $arr['sort']   =  $pt['sort'];
                $arr['author'] =  $pt['author'];
				if(!empty($pt['danxuan'])){
					$arr['danxuan'] =  implode(',', $pt['danxuan']);
				}
                $arr['create_time'] =  time();
	            $resu = $metproblem->add($arr);

               

	            if($resu){
                    //如果有多选，添加多选项
	                if(!empty($pt['sontitle']) && $pt['type']==2||$pt['type']==3){

	                	foreach ($pt['sontitle'] as $ke => $ve) {
							if(trime($ve)!=''){
								$data[$ke]['title'] = $ve;
								$data[$ke]['pid']   = $resu;
								$data[$ke]['hd_id'] = $pt['hd_id'];
							}
	                	}
		                $resuy = $metproblem->addAll($data);
                        if($resuy){
							$this->success('添加成功！',U('index'));
						}else{
							$this->error('添加失败');
						}						
	                }else{
						$this->success('添加成功！',U('index'));
					}

	            }else{
	                $this->error('添加失败');
	            }

				
			}else{
				
				$this->error($meeting_problem->getError());
				
			}
			
		}else{
			$this->display();
		}
		
	}



	
	//编辑
	public function edit(){
		$meeting_problem = D('MeetingProblem');
		if(IS_POST){
			$pt = $_POST;
			
			if($meeting_problem->create()){
				$arr['title']  =  $pt['title'];
				$arr['hd_id']  =  $pt['hd_id'];
				$arr['type']   =  $pt['type'];
				$arr['sort']   =  $pt['sort'];
				$arr['author'] =  $pt['author'];
				$arr['id']     =  $pt['id'];
				if(!empty($pt['danxuan'])){
					$arr['danxuan'] =  implode(',', $pt['danxuan']);
				}else{
					$arr['danxuan'] = '';
				}
				$arr['create_time'] =  time();
				$resu = $meeting_problem->save($arr);	
				
				if($resu){
					if($pt['type']==2 || $pt['type']==3){
					    //修改已有的
						if(count($pt[proson_id])>0){
							foreach($pt[proson_id] as $k=>$v){
								if(trim($pt['pro_title'.$v])!=''){
								  $saveresu = $meeting_problem->where('id='.$v)->save(array('title'=>$pt['pro_title'.$v]));
								}
							}
						}
						//添加没有的
						if(count($pt[sontitle])>0){
							foreach($pt[sontitle] as $kk=>$vv){
								if(trim($vv)!=''){
									$data[$kk]['title'] = $vv;
									$data[$kk]['pid']   = $pt[id];
									$data[$kk]['hd_id'] = $pt['hd_id'];
								}
							}
							$prores = $meeting_problem->addAll($data); 
						 } 
					
				    }
					$this->success('编辑成功！',U('index'));
					

				}else{
					
					$this->error('编辑失败！');
				}
                
				
			}else{
				
				$this->error($meeting_problem->getError());
				
			}
			
		}else{
			$pt = $_REQUEST;
			$vo = $meeting_problem->where('id='.$pt[id])->find();
			
			//查询多选问题
			$where['pid'] = $pt[id];
			$list = $meeting_problem->where($where)->order('id asc')->select();
			
			$this->assign('vo',$vo);
			$this->assign('list',$list);
			$this->display();
		}
		
	}
	
	//问题复制
	public function problemcopy(){
		
		
		if(IS_POST){
		  $pt = $_POST;
		  
		  $result = M('meeting_problem')->where('hd_id='.$pt[hd_id])->select();
		  
			  $ptarr = explode(',',$pt[proid]);
			  $key = array_search('on', $ptarr);
			  if ($key !== false){
				 array_splice($ptarr,$key,1);	
			  }
			  $proids = implode(',',$ptarr);	
			  $where[id] = array('in',$proids);	
			  $list = M('meeting_problem')->where($where)->order('id asc')->select();
			  
			  foreach($list as $ke=>$ve){
				$data[$ke]['hd_id'] = $pt['hd_id'];  
				$data[$ke]['title'] = $ve['title'];  
				$data[$ke]['type']  = $ve['type'];  
				$data[$ke]['content'] = $ve['content'];  
				$data[$ke]['remark'] = $ve['remark'];  
				$data[$ke]['sort'] = $ve['sort'];  
				$data[$ke]['status'] = $ve['status'];  
				$data[$ke]['danxuan'] = $ve['danxuan'];  
				$data[$ke]['pid'] = $ve['pid'];  
				$data[$ke]['create_time'] = time();  
			  }
			  $reauw = M('meeting_problem')->addAll($data);
		      if($reauw){
				 $this->success('复制成功！',U('index'));
			  }else{
				 $this->error('复制失败！',U('index'));
			  }
		  
		    $this->ajaxReturn($msg);
			
		}else{
			
			if($_REQUEST[proid]==''){
				$this->error('非法操作！',U('index'));
			}else{
			    $this->assign('proid',$_REQUEST[proid]);	
			}
			
		}	
		
		
		$this->display();
	}
	

    public function editdelete(){
		$id = $_POST['id'];
		$resut = M('meeting_problem')->where('id='.$id)->delete();
		if($resut){
			$msg[status] = 1;
		}else{
			$msg[status] = 2;
		}
		$this->ajaxReturn($msg);
	}

	
	//调研结果列表
	public function result(){
		
		
		//未查看数目
		$noseeCount = M('dyresult')->where("bhy_dyresult.status=1")->join('bhy_usermember as u on u.id=bhy_dyresult.cid')->count();
		$this->assign('noseeCount',$noseeCount);
		
		
		$get = $_REQUEST;
		$this->assign('get',$get);
		if($get[status]!=''){
			$where["bhy_dyresult.status"] = $get[status];
		}else{
			$where["bhy_dyresult.status"] = array('gt',-1);
		}
		
		
		
		//分页显示输出
		$listsCount = M('dyresult')->where($where)->join('bhy_usermember as u on u.id=bhy_dyresult.cid')->count();
		$Page       = new \Think\Page($listsCount,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		
		$list = M('dyresult')->where($where)->join('bhy_usermember as u on u.id=bhy_dyresult.cid')->order('bhy_dyresult.create_time desc')->field('bhy_dyresult.*,u.username,u.mobile,u.position,u.company,u.fax,u.usertype')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		
		$this->assign('list',$list);
		$this->assign('_page',$show);
		$this->display();
	}
	
	//调研结果查看
	public function see(){
		$id = I('request.id');//调查主表ID
		
		
		
		
		//主表信息
		$vo = M('dyresult')->where('bhy_dyresult.id='.$id)->join('bhy_usermember as u on u.id=bhy_dyresult.cid')->field('bhy_dyresult.*,u.username,u.mobile,u.position,u.company,u.fax,u.usertype')->find();
		
		if($vo[status]==1){
			//修改查看状态
		    M('dyresult')->where('id='.$id)->save(array('status'=>2));
		}
		
		$this->assign('vo',$vo);
		
		
		//查询问题列表
		$where[pid] = 0;
		$where[status] = 1;
		$wtList = M('meeting_problem')->where($where)->order('sort asc')->select();
		foreach($wtList as $k=>$v){
			$maps["bhy_meeting_problem.pid"] = $v[id];
			$maps["bhy_meeting_problem.status"] = 1;
			$maps["d.parent_id"] = $id;
			
			$wtList[$k]['problem'] = M('meeting_problem')->join('bhy_dyresult_list as d on d.wtid=bhy_meeting_problem.id')->join('bhy_dyresult as dy on dy.id=d.parent_id')->field('bhy_meeting_problem.*,d.satisfaction,d.important')->where($maps)->order('bhy_meeting_problem.sort asc')->select();
			
			
		}
		//p($wtList);
		$this->assign('wtList',$wtList);
		
		
	
	   $this->display();
	}
	
	
	//调研结果统计
	public function countresult(){
		
		$wtList = $this->getComResult(); 
		
		$this->assign('wtList',$wtList);
		
		
		$this->display();
	}
	
	
	public function getComResult(){
		//查询问题列表
		$where[pid] = 0;
		$where[status] = 1;
		$wtList = M('meeting_problem')->where($where)->field('title,id')->order('sort asc')->select();
		foreach($wtList as $k=>$v){
			$map["pid"] = $v[id];
			$map["status"] = 1;
			$wtList[$k][problem] = M('meeting_problem')->where($map)->field('title,id')->order('sort asc')->select();
			foreach($wtList[$k][problem] as $ks=>$vs){
				$maps["d.wtid"] = $vs[id];
				$maps["bhy_dyresult.status"] = array('gt',-1);
				$tja["d.satisfaction"] = 5;
				$wtList[$k][problem][$ks][counta] = M('dyresult')->join('bhy_dyresult_list as d on d.parent_id=bhy_dyresult.id')->where($maps)->where($tja)->count();
				
				$tjb["d.satisfaction"] = 4;
				$wtList[$k][problem][$ks][countb] = M('dyresult')->join('bhy_dyresult_list as d on d.parent_id=bhy_dyresult.id')->where($maps)->where($tjb)->count();
				
				$tjc["d.satisfaction"] = 3;
				$wtList[$k][problem][$ks][countc] = M('dyresult')->join('bhy_dyresult_list as d on d.parent_id=bhy_dyresult.id')->where($maps)->where($tjc)->count();
				
				$tjd["d.satisfaction"] = 2;
				$wtList[$k][problem][$ks][countd] = M('dyresult')->join('bhy_dyresult_list as d on d.parent_id=bhy_dyresult.id')->where($maps)->where($tjd)->count();
				
				$tje["d.satisfaction"] = 1;
				$wtList[$k][problem][$ks][counte] = M('dyresult')->join('bhy_dyresult_list as d on d.parent_id=bhy_dyresult.id')->where($maps)->where($tje)->count();
				
				$tjja["d.important"] = 3;
				$wtList[$k][problem][$ks][zscounta] = M('dyresult')->join('bhy_dyresult_list as d on d.parent_id=bhy_dyresult.id')->where($maps)->where($tjja)->count();
				
				$tjjb["d.important"] = 2;
				$wtList[$k][problem][$ks][zscountb] = M('dyresult')->join('bhy_dyresult_list as d on d.parent_id=bhy_dyresult.id')->where($maps)->where($tjjb)->count();
				
				$tjjc["d.important"] = 1;
				$wtList[$k][problem][$ks][zscountc] = M('dyresult')->join('bhy_dyresult_list as d on d.parent_id=bhy_dyresult.id')->where($maps)->where($tjjc)->count();
				 
			}
		}
		
		return $wtList;
	}
	
	
	
	
	//导出调研统计
	
	public function export(){
		 /** Error reporting */
        error_reporting(E_ALL);
        /** PHPExcel */
        Vendor("PHPExcel.PHPExcel");

        /** PHPExcel_Writer_Excel2003用于创建xls文件 */
        include_once 'PHPExcel/Writer/Excel5.php';
        Vendor("PHPExcel.PHPExcel.Writer.Excel5");

        // Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();

        // Set properties
        $objPHPExcel->getProperties()->setCreator("李汉团");
        $objPHPExcel->getProperties()->setLastModifiedBy("李汉团");
        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
        $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
        $objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
		
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
}
