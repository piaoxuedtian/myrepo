<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
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
		$model=new <?php echo $this->modelClass; ?>;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->attributes=$_POST['<?php echo $this->modelClass; ?>'];
<?php
			foreach($this->tableSchema->columns as $name=>$column)
			{
				if($setting['type'][$column->name] == 'pic' || $setting['type'][$column->name] == 'file')
				{
			?>
			$upload=CUploadedFile::getInstance($model,'<?php echo $column->name; ?>');
			if(!empty($upload))
			{
				$model-><?php echo $column->name; ?>=Upload::createFile($upload,'image','create');
			}
<?php
				}
			}
			?>
			if($model->save())
			{
				$this->redirect(array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
			}
		}

		$this->render('create',array(
			'model'=>$model,
<?php
			foreach($setting['editable'] as $name)
			{
				if($setting['type'][$name] == 'cat_select' && $name == 'parent_id')
				{
					echo "\t\t\t'" . lcfirst($this->modelClass) . "s'=>\$model->showAllDroplist(),\n";
				}elseif(preg_match('/(region|cat_select|select|radio|checkbox)/i', $setting['type'][$name]) && preg_match('/^^(.+)_id$/i',$name)){
					$className = $this->getClassNameByVar($name);
					echo "\t\t\t'" . lcfirst($className) . "s'=>{$className}::model()->get{$className}s(),\n";
				}
			}
		?>
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

		if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->attributes=$_POST['<?php echo $this->modelClass; ?>'];
<?php
			foreach($this->tableSchema->columns as $name=>$column)
			{
				if($setting['type'][$column->name] == 'file' || $setting['type'][$column->name] == 'pic')
				{
			?>
			$upload=CUploadedFile::getInstance($model,'<?php echo $column->name; ?>');
			if(!empty($upload))
			{
				$model-><?php echo $column->name; ?>=Upload::createFile($upload,'image','update',$model-><?php echo $column->name; ?>);
			}
<?php
				}
			}
			?>
			if($model->save())
				$this->redirect(array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
		}

		$this->render('update',array(
			'model'=>$model,
<?php
			foreach($setting['editable'] as $name)
			{
				if($setting['type'][$name] == 'cat_select' && $name == 'parent_id')
				{
					echo "\t\t\t'" . lcfirst($this->modelClass) . "s'=>\$model->showAllDroplist(),\n";
				}elseif(preg_match('/(region|cat_select|select|radio|checkbox)/i', $setting['type'][$name]) && preg_match('/^^(.+)_id$/i',$name)){
					$className = $this->getClassNameByVar($name);
					echo "\t\t\t'" . lcfirst($className) . "s'=>{$className}::model()->get{$className}s(),\n";
				}
			}
			?>
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model=new Category;
		$model->setScenario('search');
		$model->attributes=Yii::app()->request->getParam('<?php echo $this->modelClass; ?>');
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
		$model=<?php echo $this->modelClass; ?>::model()->findByPk($id);
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
					$this->batch_del('<?php echo $this->modelClass; ?>');
					break;
				default:
					break;
			}
		}
	}		
}
