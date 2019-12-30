<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class PublicController extends HomeController {
	protected function _initialize(){
// 		读取站点配置
		$config = api('Config/lists');
		C($config); //添加配置
	
		if(!C('WEB_SITE_CLOSE')){
			$this->error('站点已经关闭，请稍后访问~');
		}
	}
	
	
	//登陆接口
	
	public function sessionLogin(){
		
		$href = $_POST['href'];
		$data['sourceCode'] = 5;
		$data['backData'] = base64_encode($href);
		$data['key'] = md5('sourceCode=000'.$data['sourceCode'].'&backData='.$href.'wllzztzdr@v_142779052200001');
		
		$arr = json_encode($data);
		
		echo $arr;
		
	}
	
	//注册接口
	
	public function sessionRegister(){
		
		$href = $_POST['href'];
		$data['sourceCode'] = 5;
		$data['backData'] = base64_encode($href);
		$data['key'] = md5('sourceCode=000'.$data['sourceCode'].'&backData='.$href.'wllzztzdr@v_142779052200002');
		
		$arr = json_encode($data);
		
		echo $arr;
		
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
  
  //弹窗是否登陆
  
  public function loginCheck(){
	  
	$this -> display();  
	  
  }
  
  //退出会员中心
  
  public function signout(){
	  	
		session('dr-uid',null);  
		
		$href = $_POST['url'];
		$data['sourceCode'] = 5;
		$data['backData'] = base64_encode($href);
		$data['key'] = md5('sourceCode=000'.$data['sourceCode'].'&backData='.$href.'wllzztzdr@v_142779052200001');
		
		
		echo '200-'.$data['sourceCode'].'-'.$data['backData'].'-'.$data['key'];
		
		//exit('200');
		/*$url = $_POST['url'];
		
		$this -> redirect('http://c.tzdr.com:999/tzdr-web/logout?sourceCode=5&backData='.base64_encode($url).'&key='.md5('sourceCode=0005&backData='.$url.'wllzztzdr@v_142779052200001'));
		
		$uid = session('dr-uid');
	  	$key = md5('sourceCode=0005wllzztzdr@v_142779052200005');
		$post_data = array(
		  'sourceCode' => 5,
		  'key' => $key,
		  'uid' => $uid,
		);
	  	$result = $this -> send_post('http://c.tzdr.com:999/tzdr-web/singleLogout',$post_data);
		
		$memResult = json_decode($result,true);
		
		//exit(var_dump($memResult));
		
		if($memResult['success'] == true){
			
				
			
		}else{
			
			exit('500');
			
		}*/
	  
		
	 
  }
  
  //查询@好友列表
  public function memlist(){
	  
	  	$useration = $this -> getMemberation();
		
		//查询自己的所有好友
		$data['_string'] = ' uid = '.$useration.' OR friendid = '.$useration;
		$fir['status'] = array('eq',1);
		
		//查询最新的所有问题
	   
		$data = M("friend");
		$count = $data->where($fir)->count();

		$Page = new \Think\Pagelist($count,12); // 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show(); // 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$friList = $data->where($fir)->limit($Page->firstRow.",".$Page->listRows)->select();
		$this->assign('page',$show);// 赋值分页输出
		
		$this -> assign('friList',$friList);
		$this -> assign('useration',$useration);
		$this -> display();  
		
  }
  
  //存在登陆session
  public function loginSession(){
	  
		session('loginSession','loginSession');
		
		exit('200');  
	  
  }
  
  //存储注册session
  public function registerSession(){
	  
		session('loginSession','registerSession');
		
		exit('200');  
	  
  }
  
  //获取登陆注册session
  public function layerSession(){
	  
	  $loginSession = session('loginSession');
	  
	  if($loginSession == 'loginSession'){
		  
		  session('loginSession',null);
		  exit('200');
		  
	  }elseif($loginSession == 'registerSession'){
		  session('loginSession',null);
		  exit('201');
		  
	  }else{
		  session('loginSession',null);
		  exit('500'); 
		  
	  }
	  
	  
  }
  
  public function checklogin(){
		
		$memList = str_replace("_","",$_GET['key']);
		
		$memInfo = json_decode($memList,true);
		
		$backData = base64_decode($memInfo['backData']);
		
		$kevVal = md5('sourceCode=000'.$memInfo['sourceCode'].'&backData='.$backData.'7kcb@19910503');
		
		if($memInfo['key'] == $kevVal && $memInfo['sourceCode'] == 5){
			
			
			$uid = $memInfo['uid'];
				
			$key = md5('sourceCode=0005wllzztzdr@v_142779052200003');
			$post_data = array(
			  'sourceCode' => 5,
			  'key' => $key,
			  'uid' => $uid,
			);
			
			$result = $this -> send_post('http://c.tzdr.com:999/tzdr-web/getUserInfo',$post_data);
			
			$memResult = json_decode($result,true);
			
			
			//查询当前会员是否在这边注册
			$memList = $memResult['data'];
			//var_dump($memList);
			$user['uid'] = array('eq',$memList['uid']);
			
			$userList = M('usermember')->where($user)->find();
			if(!$userList){
				
				
				//添加会员
				
				$data['uid'] = $memList['uid'];
				$data['mobile'] = $memList['mobile'];
				$data['username'] = $memList['uname'];
				$data['email'] = $memList['email'];
				$data['position'] = getCateId(31,$memList['position']);
				$data['industry'] = getCateId(16,$memList['industry']);
				$data['education'] = getCateId(9,$memList['education']);
				$data['marriage'] = getCateId(27,$memList['marriage']);
				if(!empty($memList['province'])){
					
					$data['nowsprovince'] = $memList['province'];
					
				}else{
					
					$data['nowsprovince'] = 0;	
					
				}
				
				if(!empty($memList['city'])){
					
					$data['nowscity'] = $memList['city'];
					
				}else{
					
					$data['nowscity'] = 0;	
					
				}
				$data['nowsaddress'] = $memList['address'];
				$data['birthday'] = '';
				$data['leastlogin'] = time();
				$data['addtime'] = $memList['ctime'];
				$data['icon'] = 0;
				$data['point'] = 0;
				$data['birthaddress'] = 0;
				
				if(M('usermember')->add($data)){
					
					session('dr-uid',$memList['uid']);
					
					Header("Location: $backData"); 
				}
				
			}else{
				
				
				
				session('dr-uid',$memList['uid']);
				
				Header("Location: $backData"); 
				
				
			}
			
			
		}else{
			
			$data['success'] = false;
			$data['message'] = '密钥或渠道来源不匹配！';		
			
		}
		
		var_dump($data);
	}
  
  //同步登陆
  
  public function zationlogin(){
	  
	  
	  
  }
	
}
