<?php
return array(
	//设置为重写模式
	'URL_MODEL'=>2,
	//设置默认模块
	'DEFAULT_MODULE'=>'Home',
	// 设置容许访问的模块
	'MODULE_ALLOW_LIST'=>array('Home','Admin'),
	// 自定义模板替换
	'TMPL_PARSE_STRING'=>array(
		'__ADMIN__'=>'/Public/Admin',
		'__HOME__'=>'/Public/Home'
	),
	// 数据库配置
	'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'shop',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'shop_',    // 数据库表前缀
    'VERISION'				=>'201712051523',
    // 指定域名
    'DOMAIN'				=>'http://www.shopproject.com/'
);