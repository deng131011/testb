<?php


namespace Admin\Controller;
use User\Api\UserApi;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class PublicController extends \Think\Controller {

    /**
     * 后台用户登录
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function login($username = null, $password = null, $verify = null){
        if(IS_POST){
            /* 检测验证码 TODO: */
            if(!check_verify($verify)){
                $this->error('验证码输入错误！');
            }

            /* 调用UC登录接口登录 */
            $User = new UserApi;
            $uid = $User->login($username, $password);
            if(0 < $uid){ //UC登录成功
                /* 登录用户 */
                $Member = D('Member');
                if($Member->login($uid)){ //登录用户
                    //TODO:跳转到登录前页面
                    
                    //判断是否是红娘
//                     if(issermatchmaker($uid)){
                    	
//                     	$this->success('登录成功！', U('Workrecord/index'));
                    	
//                     }elseif(issalematchmaker($uid)){
                    	
//                     	$this->success('登录成功！', U('Workrecord/index'));
                    	
//                     }else{
                    	
                    	$this->success('登录成功！', U('Index/index'));
                    	
//                     }
                } else {
                    $this->error($Member->getError());
                }

            } else { //登录失败
                switch($uid) {
                    case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
                    case -2: $error = '密码错误！'; break;
                    default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
                }
                $this->error($error);
            }
        } else {
            if(is_login()){
                $this->redirect('Index/index');
            }else{
                /* 读取数据库中的配置 */
                $config	=	S('DB_CONFIG_DATA');
                if(!$config){
                    $config	=	D('Config')->lists();
                    S('DB_CONFIG_DATA',$config);
                }
                C($config); //添加配置
                
                $this->display();
            }
        }
    }

    /* 退出登录 */
    public function logout(){
        if(is_login()){
            D('Member')->logout();
            session('[destroy]');
            $this->success('退出成功！', U('login'));
        } else {
            $this->redirect('login');
        }
    }

    public function verify(){
        $verify = new \Think\Verify();
        $verify->entry(1);
    }

	//省市区多级联动
	
	 public function getPronvince(){
		$province_id = $_POST['province_id'];
		$where[status] = 1;
    	$province = M('province')->where($where)->order('sort asc')->select();
		//p($province_id);
    	foreach ($province as $key=>$val)
    	{
    		
			$ret .= '<option value="'.$val[id].'" '.get_selected($province_id,$val[id]).'>'.$val[addr].'</option>';
    	
    	}
		//print_r($ret);
		$this->ajaxReturn($ret);
    	
    	
    }


	//获取城市列表
    public function getCity(){
    	$province_id = $_REQUEST['province_id'];
    	$city_id = $_REQUEST['city_id'];
        $city_id = $city_id==''?0:$city_id;
    	$province_id = is_numeric($province_id)?$province_id:0;

  		$where[province_id] = $province_id;
  		$where[status] = 1;

    	
    	$city = M('city')->where($where)->order('sort asc')->select();
		if(!empty($city)){
			$reta = "<select name='city_id'>";
			foreach ($city as $key=>$val)
			{
				 
				$ret .= "<option value=".$val[id]." ".get_selected($city_id,$val[id]).">".$val[addr]."</option>";
				
			}
			$retb = "</select>";
			$rets = $reta.$ret.$retb;
			
		}
      //  p($rets);
		
    	$this->ajaxReturn($rets);


    }

	
	
	


    //通过省获取所有的市
    public function getJSONCity(){
    	$pid = $_REQUEST['pid'];

    	$pid = is_numeric($pid)?$pid:0;

    	$_wr['province_id'] = array('eq' , $pid);

    	$_wr['status'] = array('eq' , 1);

    	$city = M('city')->field('id,addr')->where($_wr)->order('id asc')->select();

    	$jsonencode = json_encode($city);

    	echo $jsonencode;

    }

   

   

}
