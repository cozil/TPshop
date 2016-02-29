<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE'               =>  'mysql',     // 数据库类型
	'DB_HOST'               =>  'localhost', // 服务器地址
	'DB_NAME'               =>  'php14',          // 数据库名
	'DB_USER'               =>  'root',      // 用户名
	'DB_PWD'                =>  'admin88',          // 密码
	'DB_PREFIX'             =>  'it_',    // 数据库表前缀

	// PHP防止XSS过滤函数
    	'DEFAULT_FILTER'        =>  'removeXSS', // 默认参数过滤方法 用于I函数...

    	// 图片上传相关信息（自己添加）
    	'ROOT_PATH' =>'./Public/Uploads/', // 注意尾部的 / 
    	'VIEW_ROOT_PATH' =>'/Public/Uploads/', // 注意尾部的 / 
    	'UPLOAD_MAX_FILESIZE' => '3M',
    	'UPLOAD_ALLOW_EXTS' => array('jpg', 'gif', 'png', 'jpeg'),   

    	//MD5秘钥 (自己写的)
    	'MD5_KEY'=>'afsdf^%$21776^%$&&^%%', 

);