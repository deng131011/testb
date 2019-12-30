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
class GsmeetingController extends AdminController {
	
	public function _initialize(){
		
		parent::_initialize();
	}
	
	
	
	//更换状态
	public function changeStatus(){
		$get = $_REQUEST;
		if($get['method']=='resumeUser'){
			
			$meet = M('gsmeeting')->where('status=1')->find();
			
			if(!empty($meet)){
				
				$this->error('只能启用一个活动！');
				
			}else{
				
				$resu = M('gsmeeting')->where('id='.$get[id])->save(array('status'=>1));
				
				if($resu){
					
					$this->success('启用成功！');
					
				}else{
					
					$this->error('启用失败');
					
				}
				
			}
		}
		
		if($get['method']=='forbidUser'){
			
			$resu = M('gsmeeting')->where('id='.$get[id])->save(array('status'=>0));
				
			if($resu){
				
				$this->success('禁用成功！');
				
			}else{
				
				$this->error('禁用失败');
				
			}
			
		}
		
		
		
	}
    //编辑
	public function edit(){
		if(IS_POST){
			$meet = D('Gsmeeting');
            if($meet->create($_POST)){
                $pt = $_POST; 
                $_POST['begin_time']  =strtotime($_POST['begin_time']);
		        $_POST['end_time']    =strtotime($_POST['end_time']);
				
				$meetings = M('gsmeeting');
				$win_lists = M('gswin_lists');
				$meetings->startTrans(); $win_lists->startTrans();//开启事物
				
		        $resu = $meetings->save($_POST);
				
			}else{
				exit;
			}
			//修改中奖纪录顺序表
			
				$delres = $win_lists->where('hd_id='.$pt[id])->delete();
				
				
				$avg = floor($pt[join_person]/$pt[win_person]); //平均数
			
				for($i=1;$i<=$pt[win_person];$i++){
					
					$num[] = rand($i*$avg-$avg+1,$i*$avg);
					
				}
				$winarr = array();
				for($ii=1;$ii<=$pt[join_person];$ii++){
					
					$dat['hd_id'] = $pt[id];
					$dat['sort'] = $ii;
					if(in_array($ii,$num)){
						$dat[rander] = 1;
					}else{
						$dat[rander] = 0;
					}
					$winarr[] = $dat;
				}
				
				$winresu = $win_lists->addAll($winarr);
				
			if($resu&&$winresu){
				$meetings->commit();$win_lists->commit();//提交事物
				$this->success('编辑成功！',U('index'));
			}else{
				$meetings->rollback();$win_lists->rollback();//回滚事物
				$this->error('编辑失败！');
			}
			
			
			
		}else{
			$id = I('request.id');
			$vo = M('meeting')->where('id='.$id)->find();
			$this->assign('vo',$vo);
			$this->display();
		}
		
	}
	
	


	//添加
	public function add(){

       if(IS_POST){
           $meet = D('Gsmeeting');
           if($meet->create($_POST)){
                $pt = $_POST; 
                $_POST['create_time'] = time();
                $_POST['begin_time']  =strtotime($_POST['begin_time']);
		        $_POST['end_time']    =strtotime($_POST['end_time']);
		        $_POST['meeting_id']  =$this->meetingId();
		        $resu                 = $meet->add($_POST);
                
				//存入所有中奖可能记录
				$avg = floor($pt[join_person]/$pt[win_person]); //平均数
				
				for($i=1;$i<=$pt[win_person];$i++){
					
					$num[] = rand($i*$avg-$avg+1,$i*$avg);
					
				}
			
				$winarr = array();
				for($ii=1;$ii<=$pt[join_person];$ii++){
					
					$dat['hd_id'] = $resu;
					$dat['sort'] = $ii;
					if(in_array($ii,$num)){
						$dat[rander] = 1;
					}else{
						$dat[rander] = 0;
					}
					$winarr[] = $dat;
				}
				$winresu = M('gswin_lists')->addAll($winarr);
				
		        if($resu && $winresu){
		       	   //生成存入二维码
		       	   header("Content-type: text/html; charset=utf-8"); 
                   $path    = 'Uploads/company/';
                   $imgname = date('YmdHis');
                   $url     = C('WEB_SITE_URL').'/Match/gsmeeting/index?hd_id='.$resu;
				   
		           $this->qrcode($url,$path,$imgname);
                   //存入二维码路径
                   $arre['erweima'] = $path.$imgname.'.png';
                   $ert = M('gsmeeting')->where('id='.$resu)->save($arre);
				   
                   if($ert){

                   	  $this->success('添加成功！',U('index'));

                   }else{
                   	  $this->error('添加失败！'); 
                   }
                    

		       }else{
		       	  $this->error('添加失败！');
		       }


           }else{
           	   $this->error($meet->getError());
           }

         
            

       }else{

           $this->display();

       }




	}
	
	
	//删除
	
	public function del(){
		$id = I('request.id');
		
		if($id>0){
			if(is_array($id)){
				$where[id] = array('in',implode(',',$id));
			}else{
				$where[id] = $id;
			}
			$resu = M('gsmeeting')->where($where)->delete();
			if($resu){
				$this->success('删除成功！',U('index'));
			}else{
				$this->error('删除失败');
			}
			
		}else{
			
			$this->error('非法操作！');
			
		}
		
	}
	
	
	
	
    //生成二维码
	public function qrcode($data,$path,$imgname,$level=3,$size=4){

        Vendor('Phpqrcode.phpqrcode');
    
        $level = 'L';  
        // 点的大小：1到10,用于手机端4就可以了  
        $size = 15;  
       
        if(!file_exists($path))   
        {   
            mkdir($path, 0700);   
        }  
        //  生成的文件名  
        $fileName = $path.$imgname.'.png';
        ob_end_clean();//清空缓冲区  
        $object = new \QRcode();
        $object->png($data,$fileName,$level, $size);
      
   }


    //随机生成活动id
    public function meetingId(){
    	$meetid = 'Bsgwxsyth'.rand(10000,99999);
		$result = M('gsmeeting')->where('meeting_id='.$meetid)->find();
		if(!empty($result)){
             $this->meetingId();
		}else{
			return $meetid;
		}
   
    }



    //问题统计
    public function seecount(){
        $hd_id = I('request.hd_id');
        $where["hd_id"] = $hd_id;
        $where["status"] = 1;
		$where["pid"] = 0;
        $list = M('meeting_problem')->where($where)->order('sort asc')->select();
		
        foreach ($list as $ke => $ve) {

        	
            if($ve[type]==1){
            	$weta[problem_id] = $ve[id];
                $weta['type'] = 1;
                $weta['result'] = array('gt',0);
            	$arrlist[$ve['id']] = M('meeting_problemlist')->field('result,content')->where($weta)->select();
				$list[$ke][counts] = array_count_values(array_column($arrlist[$ve['id']],'result'));//总数

            }

            if($ve[type]==4){
                $wetd[problem_id] = $ve[id];
                $wetd['type'] = 4;
                $wetd['result'] = array('eq',0);  
                $list[$ke][counts] = M('meeting_problemlist')->where($wetd)->count();

            } 



        }

       
        //总共参与答题人数
        $datcount = M('meeting_problemlist')->where('hd_id='.$hd_id)->group('cid')->select();
       
        $this->assign('list',$list);
        $this->assign('datcount',count($datcount));

    	$this->display();
    }



    

    //编辑时字段处理
	public function _before_edit(){
		$_POST['begin_time']=strtotime($_POST['begin_time']);
		$_POST['end_time']=strtotime($_POST['end_time']);
		
	}
	
}
