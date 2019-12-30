<?php


namespace Admin\Controller;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class AjaxController extends \Think\Controller {
    
    
    //取出地址
    public function addr_address(){
    	
    	if(IS_POST){
    		
    		$address = $_POST['address'];
    		
    		if(!empty($address)){
    			
    			$url = 'http://apis.map.qq.com/ws/geocoder/v1/?address='.$address.'&key=PAZBZ-LUI3U-7I3VV-4RTTQ-GTX52-ZQFR5';

				$result = file_get_contents($url);
				

				$res = json_decode($result,true);

				if($res['message'] == 'query ok'){

					$location = $res['result']['location'];

					if(!empty($location['lat'])){
						
						echo $location['lat'].','.$location['lng'];
						
					}
					
					
					
				}
    			
    		}
    		
    		
    	}
    	
    }
    


    //通过省获取所有的市
    public function getJSONCity(){
        $pid = $_REQUEST['pid'];

        $pid = is_numeric($pid)?$pid:0;

        $_wr['province_id'] = array('eq' , $pid);

        $_wr['status'] = array('eq' , 1);

        $city = M('city')->field('id,addr')->where($_wr)->order('id asc')->select();

        foreach ($city as $key=>$val)
        {
            $ret .= '<option value="' . $city[$key]['id'] .'"'.$this->getCurrent($pid,$city[$key]['id']).'>' . $city[$key]['addr'] . '</option>';

        }
        echo $ret;

    }

    public function getCurrent($id,$curID){

        if($id==$curID){
            return 'selected';
        }else{

            return '';
        }


    }

    public function getCounty(){
        $pid = $_REQUEST['pid'];

        $pid = is_numeric($pid)?$pid:0;

        $_wr['city_id'] = array('eq' , $pid);


        $_wr['status'] = array('eq' , 1);
        $city = M('county')->field('id,addr')->where($_wr)->order('sort desc')->select();
        $curCon = is_numeric($_REQUEST['curCon'])?$_REQUEST['curCon']:0;

        foreach ($city as $key=>$val)
        {
            $ret .= '<option value="' . $city[$key]['id'] .'"'.$this->getCurrent($curCon,$city[$key]['id']).'>' . $city[$key]['addr'] . '</option>';

        }
        echo $ret;


    }

    //Ajax取出省市区下的客户
    public function outboundlist(){

        $list = $_POST;
        if(!empty($list['pro'])){

            $map['province_id'] = array('eq',$list['pro']);
        }
        if(!empty($list['cit'])){

            $map['city_id'] = array('eq',$list['cit']);
        }
        if(!empty($list['cou'])){

            $map['county_id'] = array('eq',$list['cou']);
        }

        $map['status'] = array('eq',1);
       // $map['cid'] = array('eq',is_login());  查询当前业务员的客户

        $cust = D('Customer')->where($map)->select();

        foreach($cust as $k => $v){

            $ret .= '<input type="radio" name="cid" id="s'.($k+1).'" value="'.$v['id'].'" ><label style="width:320px;" for="s'.($k+1).'">&nbsp;'.($k+1).'、'.$v['title'].'</label>';

        }

        echo $ret;

    }
	
	 //定时查询是否有未回复消息
	public function noReplyNews(){
		
		if(IS_POST){
			
			$ids = file_get_contents('putfile.txt');
			
		    
			
			if($_POST[type] == 'news'){
				
			    if($ids!=''){
					
					
					$infso = M('labour')->find($ids);
					
					$mtt = time()-$infso['create_time'];
					$htinfo = file_get_contents('htputfile.txt');
					
					
					
					if($mtt>=300 && empty($htinfo)){
						
						$str = '<a href="/admin/Labour/edit?cid='.$infso[cid].'"><p style="margin-top:3px; border-bottom:1px dashed #dddddd;">"'.modelField($infso[cid],'usermember','nickname').'"，待回复<span style="float:right;">（'.date('Y-m-d',$infso[create_time]).'）</span></p></a>' ;
						
						file_put_contents('htputfile.txt','213123',FILE_APPEND);
						
						   
						
						$msg['msg'] = $str;
						$msg['status'] = 100;
							
						
					}else{
						$msg['status'] = 301;
					}
					
					
				}else{
					$msg['status'] = 300;
				}
				
				
				$this->ajaxReturn($msg);
				
				
			}
			
		}
		
		
	}
	
	
	
	

   

}
