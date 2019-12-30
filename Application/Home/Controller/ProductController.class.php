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
class ProductController extends HomeController {
	
	public function _initialize(){
	   
	    parent::_initialize();
    }
	

	//系统首页
    public function index(){

       //地图定位
        $rest = file_get_contents('http://apis.map.qq.com/ws/location/v1/ip?ip='.getMobileIP().'&key=PAZBZ-LUI3U-7I3VV-4RTTQ-GTX52-ZQFR5');
        $addrs = json_decode($rest,true);
       
        $start_jd = $addrs[result][location][lng];//经度
        $start_wd = $addrs[result][location][lat];//纬度
        
        //返回当前地址
        $this->assign('dqAddr',$addrs[result][ad_info]);



        $ss = $_REQUEST;

        //搜索条件
        if($ss[years]!=''){

            $serchinfo = '按时间筛选：'. $ss[years].'年';
            $where[years] = $ss[years];
        } 
		if($ss[color_id]!=''){

           
            $where[color_id] = $ss[color_id];
        } 

        if($ss[cp_id]!=''){
            $serchinfo = '按产品筛选：'.cutstr(modelField($ss[cp_id],"industry","title"),0,35);
            $where[cp_id] = array(array('eq',$ss[cp_id]),array('like','%,'.$ss[cp_id].'%'),array('like','%'.$ss[cp_id].',%'),'or');
        } 
        if($ss[size]!=''){
			
            $serchinfo = '按规模筛选：'.modelField($ss[size],'industry','title');
            
            $guino = M('industry')->find($ss[size]);
            
            
            $ss[size] = $guino['title'];
			
			if(strpos($ss[size],'以上') !== false){
                $where[size] = array('egt',cutstr($ss[size],0,-2));
  
            }else{
                $sizearr = explode('-',$ss[size]);
                $where[size] = array(array('egt',$sizearr[0]),array('elt',$sizearr[1]));
            }
			
        } 

        if($ss[city_id]!=''){
            $crt = M('city')->join('bhy_province as r on bhy_city.province_id=r.id')->where('bhy_city.id='.$ss[city_id])->field('r.addr,bhy_city.addr as addrs')->find();
            
         
            $serchinfo = '按地区筛选：'.$crt[addr].'-'.$crt[addrs];
            $where[city_id] = $ss[city_id];
        }else{
			$mapser['city_id'] = array('neq','');
		} 
     
        if($ss[hy_id]!=''){
            $hyinfo = M('industry')->where('pid='.$ss[hy_id])->find();
            if(!empty($hyinfo)){

                 $serchinfo = '按行业筛选：'.modelField($hyinfo[pid],'industry','title').'-'.modelField($ss[hy_id],'industry','title');
            }else{
                 $serchinfo = '按行业筛选：'.modelField($ss[hy_id],'industry','title');
            }


            $where[hy_id] = $ss[hy_id];
        } 
         
        $this->assign('serchinfo',$serchinfo);

		$maps['status']   = 1;
        $maps['addr_map'] = array('neq','');
		
		//$maps['city_id'] = array('neq','');
       
       

        if(IS_POST){
            
            $num = $_POST['num'];

            if($_POST['hy_id']!=''){
                $where['hy_id'] = $_POST['hy_id'];
            }
            if($_POST['size']!=''){
				
				$guinos = M('industry')->find($_POST[size]);
            
                $_POST[size] = $guinos['title'];
				
				if(strpos($_POST['size'],'以上') !== false){
                    $where[size] = array('egt',cutstr($_POST['size'],0,-2));
  
				}else{
					$sizearr = explode('-',$_POST['size']);
					$where[size] = array(array('egt',$sizearr[0]),array('elt',$sizearr[1]));
				}
				
            }
            if($_POST['city_id']!=''){
                $where['city_id'] = $_POST['city_id'];
            }else{
				$mapser['city_id'] = array('neq','');
			}
            if($_POST['cp_id']!=''){
				$where['cp_id'] = array(array('eq',$_POST[cp_id]),array('like','%,'.$_POST[cp_id].'%'),array('like','%'.$_POST[cp_id].',%'),'or');
               
            }
            if($_POST['years']!=''){
                $where['years'] = $_POST['years'];
            }
            
			
			$mapser['status']   = array('eq',1);
            $mapser['addr_map'] = array('neq','');
			$mapser['province_id'] = array('neq','');
			  
			$rrtt = array_merge($where,$mapser);
			
			if($where!=''){
				$nums = $num;
				$rsdtlist =  M('product')->where($rrtt)->order('id desc')->limit(($nums*10).',10')->select();
				//echo M('product')->getLastSql();exit;
				if(empty($rsdtlist)){

                $result['code'] = 500; //加载完成
                $result['content'] = '没有多余的数据了！';
				}else{
					foreach($rsdtlist as $k => $v){

						$ret .= '<div class="lists pro_list">
					<div class="width670">
					   <a href="/Home/Product/details?id='.$v[id].'">
						<div class="titles">
							<span class="span1">'.$v[title].'</span>
							<span class="span2"></span>
						</div>
						<div class="twos">
							<div class="sp_img1 sp_imgg"><img src="/Public/Home/images/tb1.jpg"/></div>
							<div class="sp_img1 sp_imggg"><img src="/Public/Home/images/cppp1.png"/></div>
							<div class="font">'.modelField($v[hy_id],"industry","title").'</div>
							<div class="sp_img2 sp_imgg"><img src="/Public/Home/images/tb2.jpg"></div>
							<div class="sp_img2 sp_imggg"><img src="/Public/Home/images/cppp2.png"></div>
							<div class="font" style="margin-top:0.0533333333rem;">'.$v[years].'</div>
							<div class="sp_img3 sp_imgg"><img src="/Public/Home/images/tb3.jpg"></div>
							<div class="sp_img3 sp_imggg"><img src="/Public/Home/images/cppp3.png"></div>
							<div class="font" style="margin-top:0.0533333333rem;">'.getNumberDou($v[size]).'</div>
							<div class="font">'.modelField($v[province_id],"province","addr").'
							</div><div class="font">';
							if($v[hsr]=='Y'||$v[hsr]=='y'){
								$ret .= '高铁沿线';
							}
							
							
							$ret .= '</div></div></a></div></div>';

					}
					$result['code'] = 200;
                    $result['content'] = $ret;
				}

              }else{
				  
				$result['code'] = 500; //加载完成
                $result['content'] = '没有多余的数据了！';
				  
			  }
				
            exit(json_encode($result));

        }else{
			
               $mapser['status']   = array('eq',1);
               $mapser['addr_map'] = array('neq','');
			   $mapser['province_id'] = array('neq','');
			   $rrtt = array_merge($where,$mapser);
			  
              //列表
               
                if(empty($where)){
					
                    $list = M('product')->where($mapser)->select();
                   
                    $listarr = array();
					
					if($_GET['lth']!='' || $_GET['lth']>0){
						
						foreach ($list as $kj => $vj) {
                        $end_wd = $vj['longitude'];
                        $end_jd = $vj['latitude'];
						
                        $length = getDistance($start_wd,$start_jd,$end_wd,$end_jd);
                       
						
							if($length<=$_GET['lth']*1000 && $length!=null){
                                $vj['length'] = $length;
								$listarr[] = $vj;
                                
							}
                        }
						$this->assign('lth',$_GET['lth']);
						
					}else{
						foreach ($list as $kj => $vj) {
                        $end_wd = $vj['longitude'];
                        $end_jd = $vj['latitude'];
						
                        $length = getDistance($start_wd,$start_jd,$end_wd,$end_jd);
                        //echo $length.'<br/>';
						
                        if($length<=C('EWB_DISTANCE')*1000 && $length!=null){
                            $vj['length'] = $length;
                            $listarr[] = $vj;
                            
                        }
                       }
					}
					$listarr = arraySorter($listarr,'length','SORT_ASC');
					
                    $this->assign('list',$listarr);

                }else{
					 $listcounts = M('product')->where($rrtt)->count();
					
					 $list = M('product')->where($rrtt)->order('id desc')->limit(0,10)->select();
					 
                     $this->assign('listcounts',$listcounts);
                     $this->assign('list',$list);
                }
               // p($list);
                $this->display();

        }


    }
	
    //项目详情
    public function details(){
        $id = I('request.id');
        $data = M('product')->find($id);


        $this->assign('vo',$data);


       $this->display(); 
    }



	
	//更多筛选
    public function mores(){
      
        //时间
        $art[status] = 1;
        $art[years] = array('neq',0);
		$art['addr_map'] = array('neq','');
        $art["province_id"] = array('neq','');
		$art["city_id"] = array('neq','');
        $timelist = M('product')->where($art)->group('years')->select();
         
        $this->assign('timelist',$timelist);

        //行业
        $where["bhy_industry.pid"] = 10;
        $where["bhy_industry.status"] = 1;
        $where["a.status"] = 1;
        $where["a.hy_id"] = array('gt',0);
		$where["a.addr_map"] = array('neq','');
        $where["a.province_id"] = array('neq','');
		$where["a.city_id"] = array('neq','');
		
	    $hyList = M('industry')->where('status=1 and pid=10')->select();
		
		
		
       // $hyList = M('industry')->join('bhy_product as a on a.hy_id=bhy_industry.id')->where($where)->order('bhy_industry.sort asc')->field('bhy_industry.*')->group('a.hy_id')->select();
        
        $this->assign('hyList',$hyList);



        //省
        $maps["p.status"] = 1;
        $maps["p.province_id"] = array('gt',0);
		$maps["p.addr_map"] = array('neq','');
	    $maps["p.province_id"] = array('neq','');
		$maps["p.city_id"] = array('neq','');
		
        $province = M('province')->join('bhy_product as p on p.province_id=bhy_province.id')->where($maps)->order('bhy_province.sort asc')->field('bhy_province.*')->group('p.province_id')->select();
       
        foreach ($province as $kbb => $vbb) {
        	
        	$stcc["status"] = 1;
        	$stcc["province_id"] = $vbb[id];
			$stcc["addr_map"] = array('neq','');
		    $stcc["city_id"] = array('neq','');
         	$province[$kbb][counts] = M('product')->where($stcc)->count();
            
         } 
         
        $this->assign('province',$province);


        //规模
        $wap[pid] = 5;
        $wap[status] = 1;
        $gmList = M('industry')->where($wap)->order('sort asc')->select();
       
        foreach ($gmList as $kb => $vb) {
            if(strpos($vb[title],'以上') !== false){
                $stc[size] = array('egt',cutstr($vb[title],0,-2));
  
            }else{
                $sizearr = explode('-',$vb[title]);
                $stc[size] = array(array('egt',$sizearr[0]),array('elt',$sizearr[1]));
            }
        	
        	$stc[status] = 1;
        	$stc["addr_map"] = array('neq','');
			$stc["province_id"] = array('neq','');
			$stc["city_id"] = array('neq','');
           
         	$gmList[$kb][counts] = M('product')->where($stc)->count();
         	
         } 
        
        $this->assign('gmList',$gmList);
        
        //产品
        $wapp[pid] = 4;
        $wapp[status] = 1;
        $cpList = M('industry')->where($wapp)->order('sort asc')->select();
        foreach ($cpList as $ka => $va) {
        	$stb[status] = 1;
        	$stb[cp_id] = array(array('eq',$va[id]),array('like','%,'.$va[id].'%'),array('like','%'.$va[id].',%'),'or');
			$stb[addr_map] = array('neq','');
			$stb["province_id"] = array('neq','');
			$stb["city_id"] = array('neq','');
         	$cpList[$ka][counts] = M('product')->where($stb)->count();
         } 

        $this->assign('cpList',$cpList);

        //颜色
        $wapps[pid] = 1;
        $wapps[status] = 1;
        $ysList = M('industry')->where($wapps)->order('sort asc')->select();
        foreach ($ysList as $kk => $vv) {
        	$sta[status] = 1;
        	$sta[color_id] = $vv[id];
         	$ysList[$kk][counts] = M('product')->where($sta)->count();
         } 
       
        $this->assign('ysList',$ysList);



         $this->display();
    
       


        

		}
       
	

}