<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 系统配文件
 * 所有系统级别的配置
 */
return array(
    /* 模块相关配置 */
    'AUTOLOAD_NAMESPACE' => array('Addons' => ONETHINK_ADDON_PATH), //扩展模块列表
    'DEFAULT_MODULE'     => 'Home',
    'MODULE_DENY_LIST'   => array('Common', 'User'),
    'MODULE_ALLOW_LIST'  => array('Home','Admin','Weixin','Match'),

    /* 系统数据加密设置 */
    'DATA_AUTH_KEY' => 'Ge41o:3>liWXx}<?.0=rRwJp{HzI|Q7(vFfuPcSU', //默认数据加密KEY

    /* 调试配置 */
    'SHOW_PAGE_TRACE' => true,

    /* 用户相关设置 */
    'USER_MAX_CACHE'     => 1000, //最大缓存用户数
    'USER_ADMINISTRATOR' => 1, //管理员用户ID

    /* URL配置 */
    'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'            => 3, //URL模式
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符

    /* 全局过滤配置 */
    'DEFAULT_FILTER' => '', //全局过滤函数

    /* 数据库配置 */
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'bosige', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '123456',  // 密码
    'DB_PORT'   => '3306', // 端口
    'DB_PREFIX' => 'bhy_', // 数据库表前缀

    /* 文档模型配置 (文档模型核心配置，请勿更改) */
    'DOCUMENT_MODEL_TYPE' => array(2 => '主题', 1 => '目录', 3 => '段落'),
	
	
	//发送邮件配置

    'MAIL_HOST' =>'smtp.163.com',//smtp服务器的名称

    'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证

    'MAIL_USERNAME' =>'kunszs@163.com',//发件人的邮箱名

    'MAIL_PASSWORD' =>'1qaz2wsX',//邮箱发件人授权密码

    'MAIL_FROM' =>'kunszs@163.com',//发件人邮箱地址

    'MAIL_FROMNAME'=>'博思格钢铁',//发件人姓名

    'MAIL_CHARSET' =>'utf-8',//设置邮件编码

    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
	
	
	
);
