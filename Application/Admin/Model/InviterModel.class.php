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

class InviterModel extends Model {
	
	protected $trueTableName = 'bhy_meeting_inviter';
	
    protected $_validate = array(
        array('title', 'require', '姓名不能为空!', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
       // array('title', '', '新闻标题已经存在！', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
		

    );

    protected $_auto = array(
		 array('create_time','time',1,'function'), 
        
		
    );
	
	
	

}
