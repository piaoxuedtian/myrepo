<?php

class RegionController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Region;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Region']))
		{
			$model->attributes=$_POST['Region'];
			if($model->save())
			{
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'regions'=>$model->showAllDroplist(),
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Region']))
		{
			$model->attributes=$_POST['Region'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
			'regions'=>$model->showAllDroplist
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model=new Region('search');
 		$criteria=new CDbCriteria;
		$_GET['Region'] = Yii::app()->request->getQuery('Region',array());
		$model->attributes = $_GET['Region'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Region::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
		
	/**
	 * 批量操作
	 * @param CModel the model to be validated
	 */
	public function actionBatch()
	{
		if(isset($_POST['batch']) && isset($_POST['ids']) && !empty($_POST['ids']))
		{
			switch($_POST['batch'])
			{
				case 'del':
					$this->batch_del('Region');
					break;
				default:
					break;
			}
		}
	}		
}
