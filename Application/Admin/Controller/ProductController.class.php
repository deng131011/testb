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
class ProductController extends AdminController {
	
	public function _initialize(){
		$list = session('info_list');

        	

        	/*foreach($list as $k => $v){

        		$listid = M('product')->add($v);

        	}*/
	    $user_infos = session('user_auth');
		
		$selresu = M('auth_group_access')->where('uid='.$user_infos['uid'])->find();
		
		if($selresu[group_id]==8){
			$this->redirect('Apply/index');
			exit;
		}else if($selresu[group_id]==9){
			
			$this->redirect('Labour/index');
			exit;
		}
		
		
		//查询省份
		$province = M('province')->where('status=1')->order('sort asc')->select();
		$this->assign('province',$province);
		
		
		
		//查询规模
		$tja[pid] = 5;
		$this->assign('sizeList',$this->getSearchType($tja));
		
		//查询行业
		$tja[pid] = 10;
        $hangyeList = M('industry')->where($tja)->order('sort asc')->select();
        foreach ($hangyeList as $kk => $vv) {
        	$whes[status] = 1;
        	$whes[pid] = $vv[id];
            $hangyeList[$kk]['erji'] = M('industry')->where($whes)->order('sort asc')->select();
        }
       
		$this->assign('hangyeList',$hangyeList);
      
        
		//查询颜色
		$erty[status] = 1;
		$erty[pid] = 1;
        $ertl = M('industry')->where($erty)->order('sort asc')->select();
        foreach ($ertl as $kt => $vt) {
        	$rtyu[status] = 1;
        	$rtyu[pid] = $vt[id];
            $ertl[$kt]['erji'] = M('industry')->where($rtyu)->order('sort asc')->select();
        } 
        $this->assign('ertl',$ertl);

		//查询产品
		$tja[pid] = 4;
		$this->assign('cpList',$this->getSearchType($tja));
		
		
		parent::_initialize();
	}
	
	public function getSearchType($tj){
		
		
		$lists = M('industry')->where($tj)->order('sort asc')->select();
		
		return $lists;
	}
	
	
	//地图
	public function map(){
		
		$this->display();

	}
	

	public function edit(){
        
        if(IS_POST){
           $rt = explode(',',$_POST['coord']);
           $_POST['longitude'] = $rt[0];
           $_POST['latitude']  = $rt[1];
		   $_POST['cp_id']     = implode(',',$_POST['cp_id']);
		   
           $res = M('product')->save($_POST);

           if($res){
                
                $this->success('编辑成功',U('index'));

           }else{
             
                $this->error('编辑失败');

           }

        }else{


        	

        	

        	$id = I('request.id');
        	$data = M('product')->find($id);
        	$this->assign('vo',$data);
            $this->display(); 
        }
       
		
	}


	public function _before_add(){
             $rt = explode(',',$_POST['coord']);
             $_POST['longitude'] = $rt[0];
             $_POST['latitude']  = $rt[1];
			 $_POST['cp_id']     = implode(',',$_POST['cp_id']);
			 //$_POST['color']= implode(',',$_POST['color']);

	}
	
	
	
	//上传图集
	public function alatsList(){
		
		
		
		if(IS_POST){
			
			//exit(var_dump($_POST));
			
			$data['id'] = $_POST['mid'];
			
			//$data['atlas'] = implode(',',$_POST['atlas']);
			$data['pics'] = $_POST['pics'];
			
			$vo = M('product')->save($data);
			
			exit('200');
			
		}else{
			
			$this -> assign('mid',$_GET['id']);
			//查询当前会员的图集信息
			
			$datas = M('product')->find($_GET['id']);
			
			if(!empty($datas['pics'])){
				
				//查询所属图集
				
				$at['id'] = array('in',$datas['pics']);
				$at['status'] = array('eq',1);
				
				$picture = M('picture')->where($at)->select();
				
				$this -> assign('picture',$picture);
			}
			$this -> display();
			
		}
		
			
		
	}
	
	

	
	
	//上传方法
	public function upload()
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
			$this->goods_import($filename, $exts);
		}
	}
	
	
	//导入数据方法
	protected function goods_import($filename, $exts='xls')
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
		
		$this->save_import($data);
	}
	public function trimall($str)
{
    $qian=array(" ","　","\t","\n","\r");
    $hou=array("","","","","");
    return str_replace($qian,$hou,$str); 
}

	public function locations(){


		$list = M('product')->field('id,addr_map')->select();

		foreach($list as $k => $v){

			if(!empty($v['addr_map']) && empty($v['coord'])){

				$address = $v['addr_map'];

				$url = 'http://apis.map.qq.com/ws/geocoder/v1/?address='.$this ->trimall($address).'&key=PAZBZ-LUI3U-7I3VV-4RTTQ-GTX52-ZQFR5';

				$result = file_get_contents($url);

				$res = json_decode($result,true);

				if($res['message'] == 'query ok'){

					$location = $res['result']['location'];

					$data['id'] = $v['id'];
					$data['coord'] = $location['lat'].','.$location['lng'];
					$data['longitude'] = $location['lat'];
					$data['latitude'] = $location['lng'];

					M('product')->save($data);

				}

			}


		}

		

	}

	//保存导入数据
	public function save_import($data)
	{

	    //p($data);exit;
		$stock = M('product');
		$create_time =  time();
		foreach ($data as $k=>$v){
			if($k > 2){
				$title=$v['A'];
				$info['title'] = $title;
				if(empty($title))break;

				$info['sale_office'] = $v['B'];
				$info['years'] = $v['C'];

				$typeid = empty($v['D'])?0:$this -> indutryid($v['D'],10);
				$info['hy_id'] = $typeid;

				$pro['addr'] = array('like','%'.$v['E'].'%');
				$province_id = M('province')->where($pro)->find();
				$info['province_id'] = empty($province_id)?0:$province_id['id'];

				$pro['addr'] = array('like','%'.$v['F'].'%');
				$pro['province_id'] = array('eq',$province_id['id']);
				$city_id = M('city')->where($pro)->find();
				$info['city_id'] = empty($city_id)?0:$city_id['id'];

				$info['size'] = $v['G'];
				
				
				$info['cp_id'] = empty($v['H'])?0:$this -> caifencp($v['H']);
				
				//$info['color_id'] = empty($v['I'])?0:$this -> indutryid($v['I'],1);
				$info['addr_map'] = empty($v['I'])?'':$v['I'];
				$info['hsr'] = $v['J'];
				$info['pic_number'] = $v['K'];
				

				//添加时间  (默认值)
				$info['create_time'] = $create_time;
				$info['update_time'] = $create_time;
				$info['status'] = 1;
				
				$infoList[]=$info;
	
	

			}
	
		}
		
		
 				session('info_list',$infoList);
                //p($infoList); 
 				var_dump(session('info_list'));
				//$result = $stock->addAll($infoList);
	
		if($result){
			$this->success('导入成功', 'Admin/product/index');
		}else{
			$this->error('导入失败');
		}
		//print_r($info);
	
	}

    public function caifencp($arr){
		
		$arrt = explode("\n",$arr);
		$dataid = array();
		$index=0;
		$ert = array();
		foreach($arrt as $ke=>$ve){
			$ert[status] = 1;
			$ert[pid] = 4;
			$ert[alltitle] = array('like','%'.$ve.'%');
			$dat = M('industry')->where($ert)->find();
			if(!empty($dat)){
				$dataid[$index] = $dat[id];
				$index++;
			}
		}
		return implode(',',$dataid);
		
	}
	
	
	public function indutryid($title,$pid){

		$typeinfo = explode('-',$title);
		$arr['pid'] = array('eq',$pid);
		$arr['title'] = array('like','%'.$typeinfo[0].'%');
		$tpone = M('industry')->where($arr)->find();
		$time = time();
		if(empty($tpone)){

			$data['title'] = $typeinfo[0];
			$data['create_time'] = $time;
			$data['update_time'] = $time;
			$data['pid'] = $pid;

			$tid = M('industry')->add($data);

			if(!empty($typeinfo[1])){

				$data['title'] = $typeinfo[1];
				$data['pid'] = $tid;

				$uid = M('industry')->add($data);

				return $uid;

			}else{

				return $tid;

			}

		}else{

			if(!empty($typeinfo[1])){

				$arr['pid'] = array('eq',$tpone['id']);
				$arr['title'] = array('like','%'.$typeinfo[1].'%');

				$tptwo = M('industry')->where($arr)->find();

				if(!empty($tptwo)){

					return $tptwo['id'];

				}else{

					$data['title'] = $typeinfo[1];
					$data['create_time'] = $time;
					$data['update_time'] = $time;
					$data['pid'] = $tpone['id'];

					$tid = M('industry')->add($data);

					return $tid;

				}

			}else{

				return $tpone['id'];

			}

			


		}

	}

}
