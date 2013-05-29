<?php

/**
 * RBAC base controller
 */
class RbacController extends CController {

	/**
	 * Biz rule params
	 * @var array
	 */
	protected $bizRuleParams = array();

	public function init() {
		parent::init();
		if (!is_array($this->bizRuleParams)) {
			$this->bizRuleParams = array();
		}
		$this->bizRuleParams = CMap::mergeArray($this->bizRuleParams, $_GET, $_POST);
	}

	protected function beforeAction($action) {
		parent::beforeAction($action);

		// 检测是否为调试状态
		if (Yii::app()->getModule('rbac')->debug) {
			return true;
		}

		// get access
		$access = "{$this->id}.{$this->action->id}";
		if ($this->module !== null) {
			$access = strtr("{$this->module->id}.{$access}", array('/' => '.'));
		}
		$access = strtolower($access);

		// 是否为“总是允许授权项目”
		Yii::import('rbac.components.RbacHelper');
		if (in_array($access, RbacHelper::getAllowedAuthItems())) {
			return true;
		}

		// 权限检测
		if (Yii::app()->user->isGuest || !Yii::app()->user->checkAccess($access, $this->bizRuleParams)) {
			$this->notAuthorizedAccess();
		} else {
			return true;
		}
	}

	/**
	 * 未经授权访问提示
	 * @return boolean
	 */
	protected function notAuthorizedAccess() {
		if (Yii::app()->user->isGuest) {
			Yii::app()->user->loginRequired();
		} else {
			$access = $this->id . '/' . $this->action->id;
			if ($this->module !== null) {
				$access = $this->module->id . '/' . $access;
			}
			$error = array(
				'code' => '403',
				'message' => RbacHelper::translate('Error while trying to access {access}, You are not authorized for this action.', array('{access}' => $access)),
			);
			$view = Yii::app()->getModule('rbac')->notAuthorizedView;
			if (empty($view)) {
				$view = 'rbac.views.default.error';
			}
			if (Yii::app()->request->isAjaxRequest) {
				$this->renderPartial($view, array("error" => $error));
			} else {
				$this->render($view, array("error" => $error));
			}

			return false;
		}
	}

}
