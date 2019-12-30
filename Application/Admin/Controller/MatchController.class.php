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
class MatchController extends AdminController {
	
	public function _initialize(){
		$pid = I('request.pid',0,'intval');
		$this->assign('pid',$pid);
		
		//查询主体
		$where['pid'] = array('eq',0);
		$matchtype = M('match_type')->where($where)->order('sort asc')->select();
		foreach($matchtype as $ke=>$ve){
			$where[pid] = $ve[id];
			$matchtype[$ke]['twotype'] = M('match_type')->where($where)->order('sort asc')->select();
		}
		//p($matchtype);
		$this->assign('matchtype',$matchtype);
		
		parent::_initialize();
	}
	
    //首页
	public function index(){
		
		
		
		$typeid = I('request.typeid');
		if(empty($typeid)){
			$typeid = 0;
		}
		$this->assign('typeid',$typeid);
		
		
		$where[pid]    = array('eq',0);
		$where[status] = array('gt',-1);
		
		//分页
		$listsCount = M('match')->where($where)->count();
		$Page       = new \Think\Page($listsCount,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		
		$list = M('match')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign('list',$list);
		$this->assign('_page',$show);
		
		
		$this->display();
		
	}
	
	//增加
	public function add(){
		
		if(IS_POST){
			$pt = $_POST;
		    $pt[wtxx_title] = array_filter($pt[wtxx_title]);
			
			if($pt[title]==''){
				$this->error('标题不能为空');
			}
			if($pt[typeid]==''){
				$this->error('所属分类不能为空');
			}
			if($pt['sort']==''){
				$this->error('排序不能为空');
			}
			if($pt[type]==''||$pt[type]==0){
				$this->error('为题类型不能为空');
			}
			if(empty($pt[wtxx_title] )){
				$this->error('请添加问题选项');
			}
			if(empty($pt[daan])){
				$this->error('请勾选正确答案');
			}
			
			$where[id] = array('eq',$pt[typeid]);
			$resu = M('match_type')->where($where)->find();
			if($resu[pid]==0){
				$this->error('所属分类请选择子集分类');
			}else{
				$data[typeid]  = $pt[typeid];
				$data[typepid] = $resu[pid];
			}
			
			if($pt[type]==1){
				if(count($pt[daan])>1){
					$this->error('单选题只能有一个正确答案');
				}
			}else if($pt[type]==2){
				if(count($pt[daan])<2){
					$this->error('多选题至少有两个正确答案');
				}
			}
			
			$data['title'] = $pt[title];
			$data['icon'] = $pt[icon];
			$data['sort']  = $pt['sort'];
			$data[create_time] = time();
			$data[pid]   = 0;
			$data[type]  = $pt[type];
			foreach($pt[daan] as $kv=>$vv){
				$daanstr .= zimu_number($vv).',';
			}
			$data[answer]  = substr($daanstr,0,-1);
			//p($pt);
			$addmatch = M('match')->add($data);
			
			if($addmatch){
				foreach($pt[wtxx_title] as $ku=>$vu){
					
						$da[$ku][title]     = $vu;
					    $da[$ku][pid]       = $addmatch;
					    $da[$ku][xuxiang]   = zimu_number($ku);
						if(in_array($ku,$pt[daan])){
						    $da[$ku][is_answer] = 1;
						}else{
							$da[$ku][is_answer] = 0;
						}
					
					
				}
				$addarr = M('match')->addAll($da);
				if($addarr){
					$this->success('添加成功！',U('Match/index',array('typeid'=>$pt[typeid])));
				}else{
					$this->error('添加失败！');
				}
			}
			
			
			
		}else{
			$typeid = I('request.typeid');
			if(empty($typeid)){
				$typeid = 0;
			}
			$this->assign('typeid',$typeid);
			
			
			$this->display();
		}
		
	}
	
	//编辑
	public function edit(){
		$survey = M('match');
		if(IS_POST){
			
			$pt = $_POST;
		    $wtxx_title = array_filter($pt[wtxx_title]);
			
			if($pt[title]==''){
				$this->error('标题不能为空');
			}
			if($pt[typeid]==''){
				$this->error('所属分类不能为空');
			}
			if($pt['sort']==''){
				$this->error('排序不能为空');
			}
			if($pt[type]==''||$pt[type]==0){
				$this->error('为题类型不能为空');
			}
			if(empty($wtxx_title)){
				$this->error('请添加问题选项');
			}
			$daan = count($pt[daan]);
			if($daan<1){
				$this->error('请勾选正确答案');
			}
			
			$where[id] = array('eq',$pt[typeid]);
			$resu = M('match_type')->where($where)->find();
			if($resu[pid]==0){
				$this->error('所属分类请选择子集分类');
			}else{
				$data[typeid]  = $pt[typeid];
				$data[typepid] = $resu[pid];
			}
			
			if($pt[type]==1){
				if(count($pt[daan])>1){
					$this->error('单选题只能有一个正确答案');
				}else{
					if($pt[wtxx_title][$pt[daan][0]]==''){
						$this->error('答案对应的选项内容不能为空');
					}
				}
			}else if($pt[type]==2){
				if(count($pt[daan])<2){
					$this->error('多选题至少有两个正确答案');
				}else{
					
					foreach($pt[daan] as $vt=>$yt){
						
						if($pt[wtxx_title][$yt]==''){
							$this->error('答案对应的选项内容不能为空');
						}
						
					}
					
				}
			}
			
			//先修改问题标题
			$data[title]    = $pt[title];
			$data[icon]    = $pt[icon];
			$data['sort']   = $pt['sort'];
			$data[type]     = $pt[type];
			$data[id]       = $pt[id];
			foreach($pt[daan] as $kv=>$vv){
			$daanstr .= zimu_number($vv).',';
			}
			$data[answer]  = substr($daanstr,0,-1);
			$saveresu = M('match')->save($data);
			
			if($saveresu){
				//删除以前的选项
				$del[pid] = $pt[id];
				$resi = M('match')->where($del)->delete();
				if($resi){
					foreach($pt[wtxx_title] as $ku=>$vu){
						
							$da[$ku][title]     = $vu;
							$da[$ku][pid]       = $pt[id];
							$da[$ku][xuxiang]   = zimu_number($ku);
							if(in_array($ku,$pt[daan])){
								$da[$ku][is_answer] = 1;
							}else{
								$da[$ku][is_answer] = 0;
							}
					}
					$addarr = M('match')->addAll($da);
					if($addarr){
						$this->success('编辑成功！',U('Match/index',array('typeid'=>$pt[typeid])));
					}else{
						$this->error('编辑失败！');
					}
				}
			}
			
			
			
		}else{
			$pt = $_REQUEST;
			
			$where[pid] = array('eq',0);
			$where[id]  = array('eq',$pt[id]);
			$vo = $survey->where($where)->find();
			
			//查询选项
			$map['pid'] = array('eq',$pt[id]);
			$vo[xuanxiang] = $survey->where($map)->order('xuxiang asc')->select();
			
			$this->assign('vo',$vo);
			$this->display();
		}
		
	}
	
	
	//禁用启用
	public function changeStatus(){
		
		$pt = $_REQUEST;
		$where[id] = $pt[id];
		if($pt[type]=='qiyon'){
			$res = M('match')->where($where)->save(array('status'=>1));
			if($res){
				$this->success('启用成功');
			}else{
				$this->error('启用失败');
			}
		}else if($pt[type]=='jinyon'){
			$res = M('match')->where($where)->save(array('status'=>0));
			if($res){
				$this->success('禁用成功');
			}else{
				$this->error('禁用失败');
			}
		}
		
		
	}
	
	
	//删除
	public function del(){
		
		$pt = $_REQUEST;
		//p($pt[id]);
		if(!empty($pt)){
			
			if(is_array($pt[id])){
				foreach($pt[id] as $ve=>$ke){
				 
				  $where[pid] = $ke;
				  M('match')->where($where)->delete();
				}
				$maps[id] = array('in',implode(',',$pt[id]));
				$res = M('match')->where($maps)->delete();
				
				
			}else{
				$maps[id] = $pt[id];	
				$res = M('match')->where($maps)->delete();	
				$mapss[pid] = $pt[id];	
				$res = M('match')->where($mapss)->delete();
			}
			if($res){
				$this->success('删除成功',U('index'));
			}else{
				$this->error('删除失败',U('index'));
			}
			
		}else{
			
			$this->error('非法操作',U('index'));
			
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
		$wtList = M('survey')->where($where)->field('title,id')->order('sort asc')->select();
		foreach($wtList as $k=>$v){
			$map["pid"] = $v[id];
			$map["status"] = 1;
			$wtList[$k][problem] = M('survey')->where($map)->field('title,id')->order('sort asc')->select();
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
