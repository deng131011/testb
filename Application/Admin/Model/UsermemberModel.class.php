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
//use Think\Model\RelationModel;
/**
 * 配置模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class UsermemberModel extends Model {
	
    protected $_validate = array(
      //  array('fullname', 'require', '姓名不能为空!', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
		

    );

    protected $_auto = array(
		array('create_time','time',1,'function'),
        array('update_time','time',3,'function'),
		/* array('flag','listflag','3','callback'), */
    );
	
	
	

}
