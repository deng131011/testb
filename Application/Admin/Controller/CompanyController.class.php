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
class CompanyController extends AdminController {
	
	public function _initialize(){

		//查询活动
	   $map[status]	= array('gt',-1);
       $meetList = M('meeting')->where($map)->order('sort asc')->select();
       $this->assign('meetList',$meetList); 
		
		parent::_initialize();
	}
	
	
	public function daoru(){
		
		
		
		$this->display();
	}
	
	
	
	//上传方法
	public function uploadexcle()
	{
		
		header("Content-Type:text/html;charset=utf-8");
		$upload = new \Think\Upload();// 实例化上传类
		
		$upload->maxSize   =     3145728 ;// 设置附件上传大小
		$upload->exts      =     array('xls', 'xlsx');// 设置附件上传类
		$upload->savePath  =      '/'; // 设置附件上传目录
		// 上传文件
		$info   =   $upload->uploadOne($_FILES['excelData']);
		
		
		$filename = './Uploads'.$info['savepath'].$info['savename'];
		$exts = $info['ext'];
		//print_r($info);exit;
		if(!$info) {// 上传错误提示错误信息
			$this->error($upload->getError());
		}else{// 上传成功
			$this->goods_import($filename, $exts,$_REQUEST['meet_type']);
		}
	}
	
	//导入数据方法
	protected function goods_import($filename, $exts='xls',$hd_id)
	{
		//导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
		import("Org.Util.PHPExcel");
		//创建PHPExcel对象，注意，不能少了\
		$PHPExcel=new \PHPExcel();
		//如果excel文件后缀名为.xls，导入这个类
		if($exts == 'xls'){
			import("Org.Util.PHPExcel.Reader.Excel5");
			$PHPReader=new \PHPExcel_Reader_Excel5();
		}else if($exts == 'xlsx'){
			import("Org.Util.PHPExcel.Reader.Excel2007");
			$PHPReader=new \PHPExcel_Reader_Excel2007();
		}
	
	
		//载入文件
		$PHPExcel=$PHPReader->load($filename);
		//获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
		$currentSheet=$PHPExcel->getSheet(0);
		//获取总列数
		$allColumn=$currentSheet->getHighestColumn();
		//获取总行数
		$allRow=$currentSheet->getHighestRow();
		//循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
		for($currentRow=1;$currentRow<=$allRow;$currentRow++){
			//从哪列开始，A表示第一列
			for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
				//数据坐标
				$address=$currentColumn.$currentRow;
				//读取到的数据，保存到数组$arr中
				$data[$currentRow][$currentColumn]=$currentSheet->getCell($address)->getValue();
				
				if(is_object($data[$currentRow][$currentColumn])){
					$data[$currentRow][$currentColumn]= $data[$currentRow][$currentColumn]->__toString();
				}  
			}
	
		}
		
		$this->save_import($data,$hd_id);
	}
	
	//保存导入数据
	public function save_import($data,$hd_id)
	{

	  // p($data);
		$stock = M('meeting_company');
		$create_time =  time();
		$arr = array();
		$infos['title'] = '其他';
		//活动id
		$infos['meet_type'] = $hd_id;
		$infos['create_time'] = $create_time;
		$infos['status'] = 1;
        $i=0;		
		foreach ($data as $k=>$v){
			if($k > 1){
				
				if(!empty($v[A])){
					$title=$v['A'];
					$info['title'] = $title;
					//活动id
					$info['meet_type'] = $hd_id;

					//添加时间  (默认值)
					$info['create_time'] = $create_time;
					$info['status'] = 1;
					$info['sort']   = $i;
					
					$infoList[]=$info;
				}
				
			}
	        $i++;
		}
		    $infos[sort] = count($infoList)+1;
		    $arr[] = $infos;
		   
 			$arrinfos = array_merge($infoList,$arr);
			
			
			
			
			$result = $stock->addAll($arrinfos);
	
		if($result){
			$this->success('导入成功', 'Admin/Company/index');
		}else{
			$this->error('导入失败');
		}
		//print_r($info);
	
	}
	
	
	
	
	
	
	
	//添加时字段处理
	public function _before_add(){

		

	}

   
    

    //编辑时字段处理
	public function _before_edit(){
	   
		
	}
	
}
