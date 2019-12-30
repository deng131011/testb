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
class TimuresultController extends AdminController {
	
	public function _initialize(){
		$pid = I('request.pid',0,'intval');
		$this->assign('pid',$pid);
		
		//查询题目期数
		$where['pid'] = array('eq',1);
		$where['status'] = array('eq',1);
		$timeList = M('timutype')->where($where)->order('sort asc')->select();
		$this->assign('timeList',$timeList);
		
		//查询题目类型
		$where['pid'] = array('eq',3);
		$timulxList = M('timutype')->where($where)->order('sort asc')->select();
		$this->assign('timulxList',$timulxList);
		
		parent::_initialize();
	}
	
    //首页
	public function index(){
		
		$typeid = I('request.typeid');
		if(empty($typeid)){
			$typeid = 0;
		}
		$this->assign('typeid',$typeid);
		
		$get  = $_GET;
		$this->assign('get',$get);
		
		
		$where[is_complete]    = array('eq',1);
		$where[status] = array('gt',-1);
		
		//分页
		$listsCount = M('timulist_user')->where($where)->count();
		$Page       = new \Think\Page($listsCount,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		
		$list = M('timulist_user')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		if($get[excel]=='excel'){
			$exceldata = M('timulist_user')->where($where)->order('id desc')->select();
			$this->exportExcel($exceldata);
			
		}
		
		$this->assign('list',$list);
		$this->assign('_page',$show);
		
		
		$this->display();
		
	}
	
	//查看结果
	public function seeresult(){
		$id = I('request.id');
		
		$vo = M('timulist_user')->find($id);
		$this->assign('vo',$vo);
		
		//问题列表
		$where['timetype'] = array('eq',$vo['timetype']);
		$where['status'] = array('eq',1);
		$list = M('timulist')->where($where)->order('sort asc')->select();
       
			foreach($list as $ke=>$ve){
				
				if($ve['timutype']==7){
					$erts['pid'] = array('eq',$vo[id]);
					$erts['timu_id'] = array('eq',$ve[id]);
					$hh = M('timulist_result')->where($erts)->find();
					//echo M('timulist_result')->getLastSql();
					$list[$ke][idea] = $hh[content];	
				}else{
				
					$whe['pid'] = array('eq',$ve[id]);
					$whe['status'] = array('eq',1);
					$list[$ke][sonlist] = M('timulist_son')->where($whe)->order('sort asc')->select();
					foreach($list[$ke][sonlist] as $kv=>$vv){
						$ert['pid'] = array('eq',$vo[id]);
						$ert['timu_id'] = array('eq',$ve[id]);
						$ert['answer_id'] = array('eq',$vv[id]);
						$list[$ke][sonlist][$kv][resu] = M('timulist_result')->where($ert)->find();
					}
				
				}
		    }
				
		//exit;
		//p($list);
		$this->assign('list',$list);
		$this->assign('answer',$answer);
		$this->display();
		
	}
	
	
	
	
	//删除选项
	public function delson(){
		
		$pt = $_REQUEST;
		//p($pt[id]);
		if(!empty($pt)){
			
			if(is_array($pt[id])){
				$maps[id] = array('in',implode(',',$pt[id]));
				$res = M('timulist_son')->where($maps)->delete();
			}else{
				$maps[id] = $pt[id];	
				$res = M('timulist_son')->where($maps)->delete();	
			}
			if($res){
				$this->success('删除成功',U('sonlist',array('pid'=>$pt[pid])));
			}else{
				$this->error('删除失败',U('sonlist',array('pid'=>$pt[pid])));
			}
			
		}else{
			
			$this->error('非法操作',U('sonlist',array('pid'=>$pt[pid])));
			
		}
		
		
	}
	
	
	//导出excel
	public function exportExcel($exceldata){

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
		
		
		
		//查询题目
		$where['timetype'] = array('eq',2);
		$where['status'] = array('eq',1);
		$list = M('timulist')->where($where)->order('sort asc')->select();
		$ii = 1;
		
		$objPHPExcel->setActiveSheetIndex(0);
		foreach($list as $kr=>$vr){
			
			$objPHPExcel->getActiveSheet()->SetCellValue(zimu_number($ii).'1',$vr[title]);
			$ii++;
		}
		

		foreach($exceldata as $ks=>$vs){
			if($vs['department']!=17){
				$namedepas = $vs['username'].'（'.modelField($vs['department'],"timutype","title").'）';
			}else{
				$namedepas = $vs['username'].'（'.$vs['other_depar'].'）';
			}
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.($ks+2), $namedepas);
			//$mbs[$ks]['username'] = $vs['username'];
			
			$gh = 1;
			foreach($list as $kf=>$vf){
				
				if($vf['timutype']!=7){
					$map["bhy_timulist_result.timu_id"] = array('eq',$vf['id']);
					$map["bhy_timulist_result.pid"] = array('eq',$vs['id']);
					$result = M('timulist_result')->join('bhy_timulist_son as s on s.id=bhy_timulist_result.answer_id')->where($map)->field('s.title,bhy_timulist_result.*')->select();
					//echo M('timulist_result')->getLastSql();exit;
					$g = 1;
					$answer = '';
					if($vf['timutype']==6){
						foreach($result as $ka=>$va){
							$answer .= ','.$va[star_score].'分';
							$datas[$ks][$kf][answer] = cutstr($answer,1);
					    }
						$objPHPExcel->getActiveSheet()->SetCellValue(zimu_number($gh).($ks+2), cutstr($answer,1));
					}else{
						foreach($result as $ka=>$va){
							if($va['content']!=''){
								$answer .= ','.$va[title].'['.$va[content].']';
							}else{
								$answer .= ','.$va[title];
							}
							
							
							$datas[$ks][$kf][answer] = cutstr($answer,1);
					    }
						$objPHPExcel->getActiveSheet()->SetCellValue(zimu_number($gh).($ks+2), cutstr($answer,1));
					}
					
					
				}else {
					$maps['timu_id'] = array('eq',$vf['id']);
					$maps['pid']     = array('eq',$vs['id']);
					$resty = M('timulist_result')->where($maps)->find();
					$datas[$ks][$kf][answer] = $resty['content'];
					$objPHPExcel->getActiveSheet()->SetCellValue(zimu_number($gh).($ks+2),' '.$resty['content']);
				}
				
				
				$gh++;
			}
			
			
		}
		//p($datas);
		

		//$ii = 1;
	/*	foreach($data as $k=>$v){
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.($k+2), $v[id]);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.($k+2), ' '.$v['card_number']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.($k+2), $v['pass']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.($k+2), $v['vip_type']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.($k+2), $v['create_time']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.($k+2), $v['is_bound']);
          
		}*/

		$objPHPExcel->getActiveSheet()->setTitle('博思格问卷调查结果'.date('Y-m-d'));

		$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
		$objWriter->save(str_replace('.php', '.xls', __FILE__));
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header("Content-Disposition:attachment;filename=博思格问卷调查结果".date('Y-m-d').".xls");
		header("Content-Transfer-Encoding:binary");
		$objWriter->save("php://output");
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
