<?php
/**
 * 包含主配置文件
* 当有差异的数组或要删除的数组的时候，才需要unset，否则如果重写结构一样的数组可以不必unset
*/
$main_conf = require(dirname(__FILE__).'/main.php');

// 注销掉main中的配置
unset($main_conf['components']['log']);
unset($main_conf['components']['cache']);
unset($main_conf['components']['sessionMemCache']);

return CMap::mergeArray(
	$main_conf,
	array(
	// application components
	'components'=>array(
		// 缓存
		/*
		'cache' => array(
			'class' => 'CMemCache',
			'servers'=>array( //MemCache缓存服务器配置
				array('host'=>'server1', 'port'=>11211, 'weight'=>60), //缓存服务器1
				array('host'=>'server2', 'port'=>11211, 'weight'=>40), //缓存服务器2
		),
		'session' => array( //memcache session cache
			'class' => 'CCacheHttpSession',
			'autoStart' => 1,
			'sessionName' => 'frontend',
			'cookieParams' => array('lifetime' => '3600', 'path' => '/', 'domain' => '.test.com', 'httponly' => '1'),
			'cookieMode' => 'only',
		),
		 */
		// 你可以使用 scriptMap 来配置脚本来自哪里。
		// 对于一个生产环境的配置，如下
		/*
		'clientScript' => array( 
			'scriptMap' => array(
				//'*.js' => false,
				//'*.css' => false,
			),
		),
		/*
		// 对于一个开发环境，可以这样做
		'clientScript' => array(
			'scriptMap' => array(
				'register.js' => 'register.js',
				'login.js' => 'login.js',
			),
		),
		 */
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=yii',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '123456',
			'charset' => 'utf8',
			'tablePrefix'=>'tbl_',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		'cache'=>array(
			'class'=>'CDummyCache',
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
		/*
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
		 */
	'params'=>require(dirname(__FILE__).'/params.php'),
));