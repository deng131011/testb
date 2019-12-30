<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;
/**
 * 配置模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class ReplyModel extends Model {
	
	protected $trueTableName = 'bhy_wx_reply'; 
	
    protected $_validate = array(
        array('title', 'require', '回复标题不能为空!', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),

    );

    protected $_auto = array(
		array('create_time','time',1,'function'), 
		array('iconlist','listflag','3','callback'),
    );
	
	protected function listflag(){

		if(!empty($_POST['iconlist'])){
			$flags = $_POST['iconlist'];
			$flag = implode( "," , $flags) ;
			return $flag;
		}else{
			return '';
		}
	
	}

}
