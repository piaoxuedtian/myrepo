<?php

/**
 * RBAC Module
 */
class RbacModule extends CWebModule {

	/**
	 * 是否开启调试模式
	 * @var boolean
	 */
	public $debug = false;

	/**
	 * 界面语言设定
	 * @var string
	 */
	public $language = null;

	/**
	 * 未经授权访问提示信息页面
	 * @var string
	 */
	public $notAuthorizedView = null;

	/**
	 * 资源文件发布路径
	 * @var string
	 */
	private $_assetsUrl;

	/**
	 * 禁止扫描前端控制器
	 * @var boolean
	 */
	public $disabledScanFrontend = true;

	/**
	 * 禁止自动扫描的模块
	 * @var array
	 */
	public $disabledScanModules = array('gii');

	/**
	 * 用户存储表名称
	 * @var string
	 */
	public $userTable = 'user';

	/**
	 * 用户表中的对应 id 字段
	 * @var string
	 */
	public $userTableId = 'id';

	/**
	 * 用户表中的对应 name 字段
	 * @var string
	 */
	public $userTableName = 'name';

	/**
	 * CWebUser stateKeyPrefix 设定
	 * @var string
	 */
	public $cWebUserStateKeyPrefix = null;

	/**
	 * Default role name
	 * If you set it, when you add new user, you can call RbacHelper::setDefaultRole(user id)
	 * to set default role for this new user.
	 * @var string
	 */
	public $defaultRole;

	/**
	 * 每页数据显示量
	 * @var integer
	 */
	public $pageSize = 10;

	public function init() {
		parent::init();
		$imports = array(
			'rbac.models.*',
			'rbac.components.*',
			'rbac.forms.*',
			'rbac.extensions.*',
		);
		$this->setImport($imports);

		Yii::app()->setComponents(array(
			'errorHandler' => array(
				'class' => 'CErrorHandler',
				'errorAction' => '/rbac/default/error',
			)), false);
	}

	/**
	 * @param string $value the base URL that contains min published asset files of RBAC.
	 */
	public function setAssetsUrl($value) {
		$this->_assetsUrl = $value;
	}

	/**
	 * @return string the base URL that contains min published asset files of RBAC.
	 */
	public function getAssetsUrl() {
		if ($this->_assetsUrl === null) {
			$this->_assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('rbac.assets'));
		}
		return $this->_assetsUrl;
	}

	public function beforeControllerAction($controller, $action) {
		if (parent::beforeControllerAction($controller, $action)) {
			return true;
		} else {
			return false;
		}
	}

}
