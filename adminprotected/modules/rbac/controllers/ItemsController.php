<?php

/**
 * 管理授权项
 */
class ItemsController extends Controller {

	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
		return array(
			array('allow',
				'actions' => array('index', 'create', 'update', 'roles', 'tasks', 'operations', 'delete', 'createAuthItem', 'child', 'setAllowedAuthItem'),
				'users' => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * @description 添加授权项目
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($type = 'operation') {
		$model = new AuthItem;
		$model->type = RbacHelper::typeNameToId($type);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['AuthItem'])) {
			$model->attributes = $_POST['AuthItem'];
			if ($model->save())
				$this->redirect(array('index', 'type' => $type));
		}

		$this->render('create', array(
			'model' => $model,
		));
	}

	/**
	 * @description 更新授权项目
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $parent the ID of the model to be updated
	 */
	public function actionUpdate($parent) {
		$model = $this->loadModel($parent);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['AuthItem'])) {
			$model->attributes = $_POST['AuthItem'];
			if ($model->save()) {
				$this->redirect(array('index', 'type' => RbacHelper::typeIdToName($model->type)));
			}
		}

		$this->render('update', array(
			'model' => $model,
		));
	}

	/**
	 * @description 删除授权项目
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param string $parent the ID of the model to be deleted
	 */
	public function actionDelete($parent) {
		if (Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$this->authManager->removeAuthItem($parent);

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax'])) {
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
			}
		} else {
			throw new CHttpException(400, RbacHelper::translate('Invalid request. Please do not repeat this request again.'));
		}
	}

	/**
	 * @description 授权项目列表
	 */
	public function actionIndex($type = 'operation') {
		$type = RbacHelper::typeNameToId($type);
		$tableName = $this->authManager->itemTable;
		$items = $this->dbConnection->createCommand("SELECT * FROM {$tableName} WHERE type = :type ORDER BY name ASC")->queryAll(true, array(':type' => $type));
		$allowedAuthItems = RbacHelper::getAllowedAuthItems();
		$rawData = array();
		foreach ($items as $i => $item) {
			$rawData[$i] = $item;
			$rawData[$i]['allowed'] = (in_array($item['name'], $allowedAuthItems)) ? true : false;
		}
		$filterForm = new FilterArrayForm;
		if (isset($_GET['FilterArrayForm'])) {
			$filterForm->filters = $_GET['FilterArrayForm'];
			$rawData = $filterForm->filter($rawData);
		}
		$dataProvider = new CArrayDataProvider($rawData, array(
					'totalItemCount' => count($rawData),
					'pagination' => array(
						'pageVar' => 'page',
						'pageSize' => $this->pageSize,
					),
					'keyField' => 'name',
				));

		$this->render('index', array(
			'type' => RbacHelper::typeIdToName($type),
			'dataProvider' => $dataProvider,
			'filterForm' => $filterForm,
		));
	}

	/**
	 * @description 分配授权项
	 * @param string $parent
	 */
	public function actionChild($type, $parent) {
		$data = $this->loadModel($parent);
		if (Yii::app()->request->isPostRequest) {
			$child = Yii::app()->request->getPost('child');
			$action = Yii::app()->request->getPost('action');
			if (!empty($child)) {
				if ($action == 'add') {
					$this->authManager->addItemChild($parent, $child);
				} else {
					$this->authManager->removeItemChild($parent, $child);
				}
				Yii::app()->end();
			}
		}

		$children = $this->children(RbacHelper::typeNameToId($type));
		$filterForm = new FilterArrayForm;
		$childrenReject = $dataProviders = array();
		foreach ($children as $i => $typeId) {
			$rawData = $this->getItems($typeId, $parent);
			if ($rawData) {
				if (isset($_GET['FilterArrayForm'])) {
					$filterForm->filters = $_GET['FilterArrayForm'];
					$rawData = $filterForm->filter($rawData);
				}
				$dataProviders[] = new CArrayDataProvider($rawData, array(
							'keyField' => 'name',
							'pagination' => array(
								'pageVar' => 'page',
								'pageSize' => $this->pageSize,
							)
						));
			} else {
				$childrenReject[] = $typeId;
			}
		}
		if ($childrenReject) {
			$children = array_values(array_diff($children, $childrenReject));
		}

		$render = (Yii::app()->request->isAjaxRequest) ? 'renderPartial' : 'render';
		$this->$render('child', array(
			'type' => $type,
			'parent' => $parent,
			'children' => $children,
			'dataProviders' => $dataProviders,
			'filterForm' => $filterForm,
		));
	}

	private function getItems($type, $childParent = null) {
		$names = array_merge(RbacHelper::getAllowedAuthItems(), array($childParent), $this->dbConnection->createCommand("SELECT parent FROM {$this->authManager->itemChildTable} WHERE child = :child")->queryColumn(array(':child' => $childParent)));
		$items = $this->dbConnection->createCommand()
				->from($this->authManager->itemTable)
				->where(array('AND', 'type = :type AND name <> :name', array('NOT IN', 'name', $names)), array(':type' => $type, ':name' => $childParent))
				->queryAll();
		if ($items && !empty($childParent)) {
			$existsItems = $this->dbConnection->createCommand("SELECT child FROM {$this->authManager->itemChildTable} WHERE parent = :parent")->queryColumn(array(':parent' => $childParent));
			if ($existsItems) {
				$keys = $headers = array();
				foreach ($items as $key => $item) {
					if (in_array($items[$key]['name'], $existsItems)) {
						$items[$key]['active'] = true;
						$keys[] = $key;
						$headers[] = $items[$key];
					} else {
						$items[$key]['active'] = false;
					}
				}
				foreach ($keys as $key) {
					unset($items[$key]);
				}
				$items = array_merge($headers, $items);
			}
		}

		return $items;
	}

	private function _getDataProvider($type = 0) {
		$tableName = $this->authManager->itemTable;
		$sql = "SELECT * FROM {$tableName} WHERE type = :type ORDER BY name ASC";
		$totalItemCount = $this->dbConnection->createCommand("SELECT COUNT(*) FROM {$tableName} WHERE type = :type")->queryScalar(array(':type' => $type));

		return new CSqlDataProvider($sql, array(
					'params' => array(
						':type' => $type,
					),
					'totalItemCount' => $totalItemCount,
					'pagination' => array(
						'pageVar' => 'page',
						'pageSize' => $totalItemCount,
					),
					'keyField' => 'name',
				));
	}

	/**
	 * @description 设置总是允许授权项目
	 * @throws CHttpException
	 */
	public function actionSetAllowedAuthItem() {
		$request = Yii::app()->request;
		if ($request->isAjaxRequest) {
			$name = $request->getPost('name');
			$action = $request->getPost('action');
			if ($name && in_array($action, array('add', 'remove'))) {
				$allowedAuthItems = RbacHelper::getAllowedAuthItems();
				$changed = false;
				if ($action == 'add' && !in_array($name, $allowedAuthItems)) {
					array_unshift($allowedAuthItems, $name);
					$changed = true;
				} else if (in_array($name, $allowedAuthItems)) {
					$allowedAuthItems = array_diff($allowedAuthItems, array($name));
					$changed = true;
				}
				if ($changed) {
					$data = array('[allowed auth items]');
					foreach ($allowedAuthItems as $item) {
						$data[] = "{$item} = 1";
					}
					file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . '../components' . DIRECTORY_SEPARATOR . 'allowed.ini', implode("\r\n", $data));
				}
			}
			Yii::app()->end();
		} else {
			throw new CHttpException(400, RbacHelper::translate('Invalid request. Please do not repeat this request again.'));
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($name) {
		$model = AuthItem::model()->findByPk($name);
		if ($model === null) {
			throw new CHttpException(404, RbacHelper::translate('The requested page does not exist.'));
		}

		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'auth-item-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * 获取可以授权的项目（角色：角色，任务，操作　任务：任务，操作　操作不可以包含其他授权项目）
	 * @param integer $typeId
	 * @return array
	 */
	protected function children($typeId) {
		switch ($typeId) {
			case CAuthItem::TYPE_TASK:
				$children = array(CAuthItem::TYPE_TASK, CAuthItem::TYPE_OPERATION);
				break;
			case CAuthItem::TYPE_ROLE:
				$children = array(CAuthItem::TYPE_ROLE, CAuthItem::TYPE_TASK, CAuthItem::TYPE_OPERATION);
				break;
			default:
				$children = array();
				break;
		}

		return $children;
	}

}
