<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/yiibooster');

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Web Application',
		'language'=>'zh_cn',
		'timeZone'=>'Asia/Shanghai',
		'charset'=>'utf-8',
	
	// preloading 'log' component
	'preload'=>array('log','bootstrap'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.modules.rbac.controllers.RbacController',
	),
	'defaultController'=>'site',

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'application.modules.gii.GiiModule',
			'password'=>'123456',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
			'generatorPaths'=>array(//添加一个gii检索的路径
				'bootstrap.gii',
			),
		),
		'rbac' => array(
			'layout' => 'main',
			'disabledScanModules' => array('gii'),
			'userTable' => '{{users}}',
			'userTableId' => 'id',
			'userTableName' => 'username',
			'pageSize' => 20,
			'debug' => false,
			'language' => 'zh_cn',
			'cWebUserStateKeyPrefix' => 'user',
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// assets, 参考www.yiiframework.com/doc/api/CAssetManager
		'assetManager' => array(
			// 改变磁盘上的路径
			//'basePath' => '../assets/',
			// 改变url
			//'baseUrl' => '../assets/',
		),
		'request' => array(
			'enableCsrfValidation' => true, //如果防止post跨站攻击
			'enableCookieValidation' => true, //防止Cookie攻击
		),
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
		'clientScript' => array( 
			'scriptMap' => array(
				'*.js' => false,
				'*.css' => false,
			),
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'urlSuffix'=>'.html',
			'rules'=>array(
				'sites'=>'site/index',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view/<id>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
			),
		),
		'bootstrap'=>array(
			'class'=>'ext.yiibooster.components.Bootstrap',
			'coreCss'=>false,
			'responsiveCss' => true,
			'yiiCss'=>false,
			'jqueryCss'=>false,
			'enableJS'=>false,
		),

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=yii',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '830415',
			'charset' => 'utf8',
			'tablePrefix'=>'tbl_',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
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
);