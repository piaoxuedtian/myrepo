<?php

/**
 * 用户授权
 */
class AssignmentsController extends Controller {

	/**
	 * @description 用户授权列表
	 */
	public function actionIndex() {
		$rawData = $this->getUsers();
		$users = array();
		foreach ($rawData as $data) {
			$users[$data['id']] = $data['username'];
		}

		$this->render('index', array(
			'users' => $users,
			'roles' => array(),
		));
	}

	/**
	 * @description 用户授权
	 * @param integer $userId
	 * @throws CHttpException
	 */
	public function actionUserRoles($userId) {
		if (Yii::app()->request->isAjaxRequest) {
			$rawRoles = $this->_getRoles();
			$assignmentItems = $this->_getUserAssignmentItems($userId);
			$roles = array();
			foreach ($rawRoles as $role) {
				$roles[] = array(
					'name' => $role['name'],
					'description' => (!empty($role['description'])) ? $role['description'] : $role['name'],
					'active' => (in_array($role['name'], $assignmentItems)) ? true : false,
				);
			}

			$this->renderPartial('_ajaxUserRoles', array(
				'userId' => $userId,
				'roles' => $roles,
					), false, true);
		} else {
			throw new CHttpException(400, RbacHelper::translate('Invalid request. Please do not repeat this request again.'));
		}
	}

	/**
	 * 获取用户列表
	 */
	private function getUsers() {
		$module = $this->module;
		return $this->dbConnection->createCommand("SELECT {$module->userTableId} AS id, {$module->userTableName} AS username FROM {$module->userTable} ORDER BY {$module->userTableName}")->queryAll();
	}

	/**
	 * @description 用户授权操作
	 * @throws CHttpException
	 */
	public function actionAssign() {
		$request = Yii::app()->request;
		if ($request->isAjaxRequest) {
			$name = $request->getPost('name');
			$userId = $request->getPost('userId');
			$action = $request->getPost('action');
			if ($action && $name && $userId) {
				if ($this->authManager->isAssigned($name, $userId)) {
					$this->authManager->revoke($name, $userId);
				} else {
					$this->authManager->assign($name, $userId);
				}
			}
			Yii::app()->end();
		} else {
			throw new CHttpException(400, RbacHelper::translate('Invalid request. Please do not repeat this request again.'));
		}
	}

	/**
	 * 获取所有角色
	 * @return array
	 */
	private function _getRoles() {
		$items = $this->dbConnection->createCommand("SELECT name, description FROM {$this->authManager->itemTable} WHERE type = :type ORDER BY name ASC")->queryAll(true, array(':type' => RbacHelper::TYPE_ROLE));
		return $items;
	}

	/**
	 * 获取用户已经分配的权限项目
	 * @param inetger $userId
	 * @return array
	 */
	private function _getUserAssignmentItems($userId) {
		return $this->dbConnection->createCommand("SELECT itemname FROM {$this->authManager->assignmentTable} WHERE userid = :uid")->queryColumn(array(':uid' => $userId));
	}

}