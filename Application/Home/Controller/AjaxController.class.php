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


class AjaxController extends HomeController {


    //ajax提交留言
    public function leveler(){
		
        if(IS_POST){
			p($_POST);exit;
            $_POST[create_time] = time();
            $result = M('messages')->add($_POST);
            if($result){
                $msg[msg] = '留言成功！';
            }else{
                $msg[msg] = '留言失败，请重新提交！';
            }
            $this->ajaxReturn($msg);
        }
    }

    //ajax获取申请失败原因
     public function getCheckRasio(){
           $id = I('request.id');
           if($id>0){

                $data = M('apply_check')->where('apply_id='.$id)->find();
                if($data['remark']!=''){
                  
                     $msg[msg] = $data['remark'];
                     $msg[status] = 1;
                }else{

                    $msg[msg] = '暂无原因！';
                    $msg[status] = 1;
                }

           }else{

                $msg[status] = 2;

                $msg[msg] = '参数错误';

           }

      
      $this->ajaxReturn($msg);
     }

     //返回市
     public function get_city(){

         $pro_id = I('request.pro_id');


         $province = M('province')->find($pro_id);

         $where["bhy_city.province_id"] = $pro_id;
         $where["p.status"] = 1;
         $where["p.city_id"] = array('gt',0);
         $where["p.addr_map"] = array('neq',''); 
         
		 
         $list = M('city')->join('bhy_product as p on p.city_id=bhy_city.id')->where($where)->order('bhy_city.sort asc')->group('p.city_id')->field('bhy_city.*')->select();


         $stra = '<div class="widthall mianmao">
                <div class="width420">
                    <p class="p_mb" id="addrer_one">
                        <a href="javascript:;" >按地址选择</a> >
                        <a href="javascript:;">'.$province[addr].'</a>
                        <span><img src="/Public/Home/images/tb5.jpg"/></span>
                    </p>
                </div>
            </div><div class="xh_liebiao">';



         foreach ($list as $ke =>$ve) {
             $strb .= '<div class="widthall height100 search_list " data-id="'.$ve[id].'">
                    <a href="/Home/Product/index?city_id='.$ve[id].'">
                    <div class="width420">
                        <p class="p_mb" >
                            
                                <span class="span1">'.$ve[addr].'</span>
                                <span class="num_pro">'.getCityNum($ve[id]).'</span>
                                <span class="span2">></span>
                           
                        </p>
                    </div>
                    </a>
                </div>
           
            ';
         }
		  
         $strc = '</div><div class="widthaller height100 search_list"></div>';
         $str =  $stra.$strb.$strc;
        

         $this->ajaxReturn($str);

     }



     //返回省
     public function getProvince(){

          if($_POST[type]=='province'){

              // $list = M('province')->where('status=1')->order('sort asc')->select();
               

               $maps["p.status"] = 1;
               $maps["p.province_id"] = array('gt',0);
			   $maps["p.addr_map"] = array('neq','');
               $list = M('province')->join('bhy_product as p on p.province_id=bhy_province.id')->where($maps)->order('bhy_province.sort asc')->field('bhy_province.*')->group('p.province_id')->select();

                $stra = '<div class="widthall mianmao">
                <div class="width420">
                    <p class="p_mb">
                        <a href="javascript:;">按地址选择</a>
                        <span><img src="/Public/Home/images/tb5.jpg"/></span>
                    </p>
                </div>
                </div><div class="xh_liebiao">';

               foreach ($list as $ke => $ve) {
                   $strb .= '<div class="widthall height100 search_list addrs_list " data-id="'.$ve[id].'">
                    
                    <div class="width420">
                        <p class="p_mb" >
                            <a href="javascript:;">
                                <span class="span1">'.$ve[addr].'</span>
                                <span class="num_pro">'.getProvinceNum($ve[id]).'</span>
                                <span class="span2">></span>
                            </a>
                        </p>
                    </div>
                   
                    </div>
           
                    ';
               }

               $strc = '</div><div class="widthaller height100 search_list"></div>';
               $str =  $stra.$strb.$strc;

          }

           $this->ajaxReturn($str);

     }



     //返回行业子集
     public function get_hangye(){

         $hy_id = I('request.hy_id');


         $hangye  = M('industry')->find($hy_id);

         $where["bhy_industry.pid"] = $hy_id;
         $where["bhy_industry.status"] = 1;
         $where["p.status"] = 1;
         $where["p.hy_id"] = array('gt',0);
		 $where["p.addr_map"] = array('neq','');
         $list = M('industry')->join('bhy_product as p on p.hy_id=bhy_industry.id')->where($where)->order('bhy_industry.sort asc')->group('p.hy_id')->field('bhy_industry.*')->select();
        // echo M('industry')->getLastSql();exit;

        

         $stra = '<div class="widthall mianmao">
                <div class="width420">
                    <p class="p_mb">
                        <a href="javascript:;" id="hangye_one">按行业选择</a> >
                        <a href="javascript:;">'.$hangye[title].'</a>
                        <span><img src="/Public/Home/images/tb5.jpg"/></span>
                    </p>
                </div>
            </div><div class="xh_liebiao">';



         foreach ($list as $ke => $ve) {
             $wheres[status] = 1;
             $wheres[pid] = $ve[id];
             $strb .= '<div class="widthall height100 search_list hy_lists">
                    <a href="/Home/Product/index?hy_id='.$ve[id].'">
                    <div class="width420">
                        <p class="p_mb" >
                            
                                <span class="span1">'.$ve[title].'</span>
                                <span class="num_pro">'.getHyParentNum($ve[id]).'</span>
                                <span class="span2">></span>
                           
                        </p>
                    </div>
                    </a>
                </div>
           
            ';
         }
         $strc = '</div><div class="widthaller height100 search_list"></div>';
         $str =  $stra.$strb.$strc;
        

         $this->ajaxReturn($str);

     }



     //返回行业大类
     public function getHangTop(){
 
         if($_POST[type]=='hytop'){
			 
			 

               $list = M('industry')->where('status=1 and pid=10')->order('sort asc')->select();


                $stra = '<div class="widthall mianmao">
                <div class="width420">
                    <p class="p_mb">
                        <a href="javascript:;">按行业选择</a>
                        <span><img src="/Public/Home/images/tb5.jpg"/></span>
                    </p>
                </div>
                </div><div class="xh_liebiao">';

               foreach ($list as $ke => $ve) {
                   $strb .= '<div class="widthall height100 search_list hy_lists " data-id="'.$ve[id].'">
                    
                    <div class="width420">
                        <p class="p_mb" >
                            <a href="javascript:;">
                                <span class="span1">'.$ve[title].'</span>
                                <span class="num_pro">'.getHyParentNum($ve[id]).'</span>
                                <span class="span2">></span>
                            </a>
                        </p>
                    </div>
                   
                    </div>
           
                    ';
               }

               $strc = '</div><div class="widthaller height100 search_list"></div>';
               $str =  $stra.$strb.$strc;

          }

           $this->ajaxReturn($str);

     }
     

}