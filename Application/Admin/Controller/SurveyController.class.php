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
class SurveyController extends AdminController {
	
	public function _initialize(){
		$pid = I('request.pid',0,'intval');
		$this->assign('pid',$pid);
		
		parent::_initialize();
	}
	
    //首页
	public function index(){
		$pid = I('request.pid');
		if($pid>0){
			$where[pid] = $pid;
		}else if($pid==''){
			$where[pid] = 0;
		}
		if($_REQUEST[title]!=''){
			$where[title] = array('like','%'.$_REQUEST[title].'%');
		}
		
		$where[status] = array('gt',-1);
		
		//分页
		$listsCount = M('survey')->where($where)->count();
		$Page       = new \Think\Page($listsCount,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		
		$list = M('survey')->where($where)->order('sort asc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('list',$list);
		$this->assign('_page',$show);
		
		
		$this->display();
		
	}
	
	//增加
	public function add(){
		
		if(IS_POST){
			
			$survey = D('Survey');
			if($survey->create()){
				
				$resu = $survey->add();
				
				if($resu){
					
					$this->success('添加成功！',U('Survey/index',array('pid'=>$_POST[pid])));
					
				}else{
					
					$this->error('添加失败！');
					
				}
				
			}else{
				
				$this->error($survey->getError());
				
			}
			
		}else{
			$this->display();
		}
		
	}
	
	//编辑
	public function edit(){
		$survey = D('Survey');
		if(IS_POST){
			
			if($survey->create()){
				
				$resu = $survey->save();
				
				if($resu){
					
					$this->success('编辑成功！',U('Survey/index',array('pid'=>$_POST[pid])));
					
				}else{
					
					$this->error('编辑失败！');
					
				}
				
			}else{
				
				$this->error($survey->getError());
				
			}
			
		}else{
			$pt = $_REQUEST;
			$vo = $survey->where('id='.$pt[id])->find();
			$this->assign('vo',$vo);
			$this->display();
		}
		
	}
	
	//调研结果列表
	public function result(){
		
		
		//未查看数目
		$noseeCount = M('dyresult')->where("bhy_dyresult.status=1 and bhy_dyresult.dt_status=1")->join('bhy_usermember as u on u.id=bhy_dyresult.cid')->count();
		$this->assign('noseeCount',$noseeCount);
		
		
		$get = $_REQUEST;
		$this->assign('get',$get);
		if($get[status]!=''){
			$where["bhy_dyresult.status"] = $get[status];
		}else{
			$where["bhy_dyresult.status"] = array('gt',-1);
		}
		
		
		$where["bhy_dyresult.dt_status"] = array('eq',1);
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
		$wtList = M('survey')->where($where)->order('sort asc')->select();
		foreach($wtList as $k=>$v){
			$maps["bhy_survey.pid"] = $v[id];
			$maps["bhy_survey.status"] = 1;
			$maps["d.parent_id"] = $id;
			
			$wtList[$k]['problem'] = M('survey')->join('bhy_dyresult_list as d on d.wtid=bhy_survey.id')->join('bhy_dyresult as dy on dy.id=d.parent_id')->field('bhy_survey.*,d.satisfaction,d.important')->where($maps)->order('bhy_survey.sort asc')->select();
			
			
			
			
		}
		//p($wtList);
		
		if($_GET[exceltype]==1){
			
			
			$mapsw["bhy_survey.status"] = 1;
			$mapsw["d.parent_id"] = $id;
			
			$wtLister = M('survey')->join('bhy_dyresult_list as d on d.wtid=bhy_survey.id')->join('bhy_dyresult as dy on dy.id=d.parent_id')->field('bhy_survey.*,d.satisfaction,d.important')->where($mapsw)->order('bhy_survey.id asc')->select();
			
			$this->excelExport($wtLister,$vo);
		}
		
		
		
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
		$where[pid] = array('eq',0);
		$where[status] = array('gt',-1);
		$wtList = M('survey')->where($where)->field('title,id')->order('sort asc')->select();
		foreach($wtList as $k=>$v){
			$map["pid"] = array('eq',$v[id]);
			$map["status"] = array('eq',1);
			$wtList[$k][problem] = M('survey')->where($map)->field('title,id')->order('sort asc')->select();
			foreach($wtList[$k][problem] as $ks=>$vs){
				$mapsa["d.wtid"] = array('eq',$vs[id]);
				$mapsa["bhy_dyresult.dt_status"] = array('eq',1);
				$mapsa["d.satisfaction"] = array('eq',5);
				$wtList[$k][problem][$ks][counta] = M('dyresult')->join('bhy_dyresult_list as d on d.parent_id=bhy_dyresult.id')->where($mapsa)->count();
				
				$mapsb["d.wtid"] = array('eq',$vs[id]);
				$mapsb["bhy_dyresult.dt_status"] = array('eq',1);
				$mapsb["d.satisfaction"] = array('eq',4);
				$wtList[$k][problem][$ks][countb] = M('dyresult')->join('bhy_dyresult_list as d on d.parent_id=bhy_dyresult.id')->where($mapsb)->count();
				
				$mapsc["d.wtid"] = array('eq',$vs[id]);
				$mapsc["bhy_dyresult.dt_status"] = array('eq',1);
				$mapsc["d.satisfaction"] = array('eq',3);
				$wtList[$k][problem][$ks][countc] = M('dyresult')->join('bhy_dyresult_list as d on d.parent_id=bhy_dyresult.id')->where($mapsc)->count();
				
				$mapsd["d.wtid"] = array('eq',$vs[id]);
				$mapsd["bhy_dyresult.dt_status"] = array('eq',1);
				$mapsd["d.satisfaction"] = array('eq',2);
				$wtList[$k][problem][$ks][countd] = M('dyresult')->join('bhy_dyresult_list as d on d.parent_id=bhy_dyresult.id')->where($mapsd)->count();
				
				$mapse["d.wtid"] = array('eq',$vs[id]);
				$mapse["bhy_dyresult.dt_status"] = array('eq',1);
				$mapse["d.satisfaction"] = array('eq',1);
				$wtList[$k][problem][$ks][counte] = M('dyresult')->join('bhy_dyresult_list as d on d.parent_id=bhy_dyresult.id')->where($mapse)->count();
				
				$mapsf["d.wtid"] = array('eq',$vs[id]);
				$mapsf["bhy_dyresult.dt_status"] = array('eq',1);
				$mapsf["d.important"] = array('eq',3);
				$wtList[$k][problem][$ks][zscounta] = M('dyresult')->join('bhy_dyresult_list as d on d.parent_id=bhy_dyresult.id')->where($mapsf)->count();
				
				$tjjb["d.wtid"] = array('eq',$vs[id]);
				$tjjb["bhy_dyresult.dt_status"] = array('eq',1);
				$tjjb["d.important"] = array('eq',2);
				$wtList[$k][problem][$ks][zscountb] = M('dyresult')->join('bhy_dyresult_list as d on d.parent_id=bhy_dyresult.id')->where($tjjb)->count();
				
				$tjjc["d.wtid"] = array('eq',$vs[id]);
				$tjjc["bhy_dyresult.dt_status"] = array('eq',1);
				$tjjc["d.important"] = array('eq',1);
				$wtList[$k][problem][$ks][zscountc] = M('dyresult')->join('bhy_dyresult_list as d on d.parent_id=bhy_dyresult.id')->where($tjjc)->count();
				 
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
	
	
	//删除
	public function changeStatus(){
		
		$pt = $_REQUEST;
		
		if($pt[type]=='dyresult'){
			if(is_array($pt[id])){
				$where['id']      = array('in',implode(',',$pt[id]));
				$map['parent_id'] = array('in',implode(',',$pt[id]));
			}else{
				$where['id'] = $pt[id];
				$map['parent_id'] = $pt[id];
			}
		   
			$dyresu = M('dyresult')->where($where)->delete();
			$dylist = M('dyresult_list')->where($map)->delete();
			if($dyresu){
				$this->success('删除成功！',U('result'));
			}else{
				$this->error('删除失败！',U('result'));
			}
		}
		
	}
	
	
	
	//导出excel(每个人的答题结果)
	public function excelExport(){
		
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

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', '编号');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', '调查内容');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', '5分');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', '4分');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', '3分');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', '2分');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', '1分');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', '');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', '3分');
        $objPHPExcel->getActiveSheet()->SetCellValue('J1', '2分');
        $objPHPExcel->getActiveSheet()->SetCellValue('K1', '1分');
		
		$whe["bhy_dyresult.id"] = array('eq',$_GET[id]);
		$vo = M('dyresult')->where($whe)->join('bhy_usermember as u on u.id=bhy_dyresult.cid')->field('bhy_dyresult.*,u.username,u.mobile,u.position,u.company,u.fax,u.usertype')->find();
		
		//查询答题人
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', '用户信息');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', $vo[username]);
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', ' '.$vo[mobile]);
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', $vo[position]);
		$objPHPExcel->getActiveSheet()->SetCellValue('E2', $vo[company]);
		$objPHPExcel->getActiveSheet()->SetCellValue('F2', '');
		$objPHPExcel->getActiveSheet()->SetCellValue('G2', '');
		$objPHPExcel->getActiveSheet()->SetCellValue('H2','');
		$objPHPExcel->getActiveSheet()->SetCellValue('I2', '');
		$objPHPExcel->getActiveSheet()->SetCellValue('J2','');
		$objPHPExcel->getActiveSheet()->SetCellValue('K2', '');
		
		
		$where[pid] = 0;
		$where[status] = 1;
		$wtList = M('survey')->where($where)->order('sort asc')->select();
		
		$khh = 3;
		foreach($wtList as $kr=>$vr){
			$maps["bhy_survey.pid"] = $vr[id];
			$maps["bhy_survey.status"] = 1;
			$maps["d.parent_id"] = $_GET[id];
			
			$wtList[$kr]['problem'] = M('survey')->join('bhy_dyresult_list as d on d.wtid=bhy_survey.id')->join('bhy_dyresult as dy on dy.id=d.parent_id')->field('bhy_survey.*,d.satisfaction,d.important')->where($maps)->order('bhy_survey.sort asc')->select();
			
			
			foreach($wtList[$kr]['problem'] as $ke=>$ve){
				
				
				if($ve[satisfaction]==5){
					$satisfactiona = '√';
				}else{
					$satisfactiona = '';
				}
				
				if($ve[satisfaction]==4){
					$satisfactionb = '√';
				}else{
					$satisfactionb = '';
				}
				
				if($ve[satisfaction]==3){
					$satisfactionc = '√';
				}else{
					$satisfactionc = '';
				}
				
				if($ve[satisfaction]==2){
					$satisfactiond = '√';
				}else{
					$satisfactiond = '';
				}
				
				if($ve[satisfaction]==1){
					$satisfactione = '√';
				}else{
					$satisfactione = '';
				}
				
				if($ve[important]==3){
					$importanta = '√';
				}else{
					$importanta = '';
				}
				
				if($ve[important]==2){
					$importantb = '√';
				}else{
					$importantb = '';
				}
				
				if($ve[important]==1){
					$importantc = '√';
				}else{
					$importantc = '';
				}
				
				$objPHPExcel->getActiveSheet()->SetCellValue('A'.($khh), $khh-2);
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.($khh), $ve['title']);
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.($khh), $satisfactiona);
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.($khh), $satisfactionb);
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.($khh), $satisfactionc);
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.($khh), $satisfactiond);
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.($khh), $satisfactione);
				$objPHPExcel->getActiveSheet()->SetCellValue('H'.($khh),'');
				$objPHPExcel->getActiveSheet()->SetCellValue('I'.($khh), $importanta);
				$objPHPExcel->getActiveSheet()->SetCellValue('J'.($khh), $importantb);
				$objPHPExcel->getActiveSheet()->SetCellValue('K'.($khh), $importantc);
				$khh++;
			}
				
			
		}
		//exit;
		//p($wtList);
		    
		//$sum = count($wtLister)+1;
		$objPHPExcel->getActiveSheet()->SetCellValue('A35', 35);
		$objPHPExcel->getActiveSheet()->SetCellValue('B35', '建议内容');
		$objPHPExcel->getActiveSheet()->SetCellValue('C35', $vo['content']);
		$objPHPExcel->getActiveSheet()->SetCellValue('D35', '');
		$objPHPExcel->getActiveSheet()->SetCellValue('E35', '');
		$objPHPExcel->getActiveSheet()->SetCellValue('F35', '');
		$objPHPExcel->getActiveSheet()->SetCellValue('G35', '');
		$objPHPExcel->getActiveSheet()->SetCellValue('H35','');
		$objPHPExcel->getActiveSheet()->SetCellValue('I35', '');
		$objPHPExcel->getActiveSheet()->SetCellValue('J35', '');
		$objPHPExcel->getActiveSheet()->SetCellValue('K35', '');
		
		
		$objPHPExcel->getActiveSheet()->setTitle('满意度调查表'.date('Y-m-d'));
		
		$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter->save(str_replace('.php', '.xls', __FILE__));
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header("Content-Disposition:attachment;filename=个人满意度调查表".date('Y-m-d').".xls");
        header("Content-Transfer-Encoding:binary");
        $objWriter->save("php://output");
	}
	
	
	
	
	//操作日志
	public function viewlog(){
		
		$get = $_GET;
		$this->assign('get',$get);
		
		if($get['start_time']!='' && $get['end_time']==''){
			$where['create_time'] = array('egt',strtotime($get['start_time'].' 00:00:00'));
		}
		if($get['start_time']=='' && $get['end_time']!=''){
			$where['create_time'] = array('elt',strtotime($get['end_time'].' 23:59:59'));
		}
		if($get['start_time']!='' && $get['end_time']!=''){
			$where['create_time'] = array(array('egt',strtotime($get['start_time'].' 00:00:00')),array('elt',strtotime($get['end_time'].' 23:59:59'))) ;
		}
		
		
		$where['open_id'] = array('neq','');
		$where['status'] = array('eq',1);
		
		//分页
		$listsCount = M('survey_log')->where($where)->count();
		$Page       = new \Think\Page($listsCount,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		
		$list = M('survey_log')->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('list',$list);
		$this->assign('_page',$show);
		
		
		$this->display();
	}
	
	
	//删除日志
	public function viewlogdel(){
		
		$get = $_GET;
		$where['id'] = array('eq',$get['id']);
		$res = M('survey_log')->where($where)->delete();
		if($res){
			$this->success('删除成功',U('Survey/viewlog'));
		}else{
			$this->error('删除失败');
		}
		
		
	}
	
	
	public function send_suery(){
		
		$data = array(
			
			'client_id' => '3MVG9od6vNol.eBjBSZOEGEjj_aU_2faHpmttzBni1iA6gWWVBUDAgUzOamhwiIjHM0MYPtfjG0_4kEdy7vWy',
			'client_secret' => '1685195450314768011',
			'username' => 'integrated.account@bp.com',
			'password' => 'bp666666',
			'grant_type' => 'password'
		
		);
		$result = $this->send_post('https://test.salesforce.com/services/oauth2/token',$data);
		
		
		$list = json_decode($result,true);
		
		$access_token = $list['access_token'];
		$arr['pid'] = array('neq',0);
		$problem = M('survey')->where($arr)->order('create_time asc')->select();
		$ind = 0;$acc = 0;
		foreach($problem as $k=>$v){
			
			$quition[$acc] = array(
			
				'id' => $v['id'],
				'name' => $v['title'],
				'qtype' => 'Radio',
				'sortnumber' =>($k+1)
			
			);
			$acc++;
			
			$clist[$ind] = array(
			
				'id' => 5,
				'subjectId' => $v['id'],
				'name' => '5分',
				'sortnumber' => 0
			
			);
			$ind++;
			$clist[$ind] = array(
			
				'id' => 4,
				'subjectId' => $v['id'],
				'name' => '4分',
				'sortnumber' => 1
			
			);
			$ind++;
			$clist[$ind] = array(
			
				'id' => 3,
				'subjectId' => $v['id'],
				'name' => '3分',
				'sortnumber' => 2
			
			);
			$ind++;
			$clist[$ind] = array(
			
				'id' => 2,
				'subjectId' => $v['id'],
				'name' => '2分',
				'sortnumber' => 3
			
			);
			$ind++;
			$clist[$ind] = array(
			
				'id' => 1,
				'subjectId' => $v['id'],
				'name' => '1分',
				'sortnumber' => 4
			
			);
			$ind++;
			$clist[$ind] = array(
			
				'id' => 6,
				'subjectId' => $v['id'],
				'name' => '重要',
				'sortnumber' => 5
			
			);
			$ind++;
			$clist[$ind] = array(
			
				'id' => 7,
				'subjectId' => $v['id'],
				'name' => '一般',
				'sortnumber' => 6
			
			);
			$ind++;
			$clist[$ind] = array(
			
				'id' => 8,
				'subjectId' => $v['id'],
				'name' => '不适用',
				'sortnumber' => 7
			
			);
			$ind++;
			
			
			$quition[$acc] = array(
			
				'id' => $v['id'].'0001',
				'name' => $v['title']."-该问题对您的重要性",
				'qtype' => 'Radio',
				'sortnumber' =>($k+1)
			
			);
			$acc++;
			
			$clist[$ind] = array(
			
				'id' => 5,
				'subjectId' => $v['id'].'0001',
				'name' => '5分',
				'sortnumber' => 0
			
			);
			$ind++;
			$clist[$ind] = array(
			
				'id' => 4,
				'subjectId' => $v['id'].'0001',
				'name' => '4分',
				'sortnumber' => 1
			
			);
			$ind++;
			$clist[$ind] = array(
			
				'id' => 3,
				'subjectId' => $v['id'].'0001',
				'name' => '3分',
				'sortnumber' => 2
			
			);
			$ind++;
			$clist[$ind] = array(
			
				'id' => 2,
				'subjectId' => $v['id'].'0001',
				'name' => '2分',
				'sortnumber' => 3
			
			);
			$ind++;
			$clist[$ind] = array(
			
				'id' => 1,
				'subjectId' => $v['id'].'0001',
				'name' => '1分',
				'sortnumber' => 4
			
			);
			$ind++;
			$clist[$ind] = array(
			
				'id' => 6,
				'subjectId' => $v['id'].'0001',
				'name' => '重要',
				'sortnumber' => 5
			
			);
			$ind++;
			$clist[$ind] = array(
			
				'id' => 7,
				'subjectId' => $v['id'].'0001',
				'name' => '一般',
				'sortnumber' => 6
			
			);
			$ind++;
			$clist[$ind] = array(
			
				'id' => 8,
				'subjectId' => $v['id'].'0001',
				'name' => '不适用',
				'sortnumber' => 7
			
			);
			$ind++;
			
			
		}
		$quition[count($quition)] = array(
			
			'id' => 1000,
			'name' => '对于您不够满意之处(1-3分)，恳请您写下您的理由与建议！ 由于篇幅的局限性，我们的问题可能没有涉及所有方面，对于我们没有考虑周全的，也欢迎您写下宝贵的意见及建议。',
			'qtype' => 'Text',
			'sortnumber' =>1000
		
		);
		
		$wenjuan = array(

			'survey'=> array(

				'surveyType' => 'customer satisfaction survey',
				'surveyId' =>'10',   
				'surveyName' => '苏州钢铁客户满意度调研问卷',
				'surveyUrl' => 'http://bsgwxs.59156.cn/home/survey/index/id/13.html',
				'qlist' => $quition,
				'clist' => $clist

			)

		);
		
		$ques_result = $this -> http_post_data($list['instance_url'].'/services/apexrest/createSurvey',json_encode($wenjuan),$access_token);
		
		$survey = json_decode($ques_result[1],true);
		header('Content-Type:text/html;charset=utf-8');
		if($ques_result[0] == 200 && $survey['Status'] == true){
			
			echo "问卷发送成功！";
			
			
		}else{
			
			echo "问卷发送失败！";
			
		}
		
		
		
	}
	
	public function send_post($url, $post_data) {

		  $postdata = http_build_query($post_data);
		  $options = array(
			  'http' => array(
			  'method' => 'POST',//or GET
			  'header' => 'Content-type:application/x-www-form-urlencoded',
			  'content' => $postdata,
			  'timeout' => 15 * 60 // 超时时间（单位:s）
		  )
		  );
		  $context = stream_context_create($options);
		  $result = file_get_contents($url, false, $context);
		  return $result;
	}
	
	//获取TOKEN的HTTP JSON请求
	public function http_post_data($url, $data_string,$access_token) {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json; charset=utf-8',
				'Authorization:Bearer '.$access_token,
				'Content-Length: ' . strlen($data_string))
		);
		ob_start();
		curl_exec($ch);
		
		$return_content = ob_get_contents();
		ob_end_clean();
		$return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		return array($return_code, $return_content);
	}
	
	
	
}
