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
		$model=new <?php echo $this->modelClass; ?>;
 		$criteria=new CDbCriteria;
<?php
		foreach($setting['list_search'] as $name)
		{
			echo "\t\t\${$name} = Yii::app()->request->getQuery('{$name}');\n";
		}
		foreach($setting['list_search'] as $name)
		{
			if($setting['type'][$name]=='time'){
				echo "\t\tif(isset(\$_GET['{$name}_start']) && !empty(\$_GET['{$name}_start']))\$criteria->addCondition('{$name}>' . strtotime(\$_GET['{$name}_start']));\n";
				echo "\t\tif(isset(\$_GET['{$name}_end']) && !empty(\$_GET['{$name}_end']))\$criteria->addCondition('{$name}<' . strtotime(\$_GET['{$name}_end']));\n";
			}elseif($setting['type'][$name]=='select' || $setting['type'][$name]=='radio' || $setting['type'][$name]=='checkbox'){
				echo "\t\t\$criteria->compare('$name',\$this->$name);\n";
			}elseif($setting['type'][$name]=='checkbox'){
				echo "\t\t\$criteria->addCondition('$name & \$this->$name = \$this->$name');\n";
			}elseif($setting['type'][$name]=='cat_select' || $setting['type'][$name]=='region'){
				if($name == 'parent_id'){
					$var_name = '$ids';
					echo "\t\t{$var_name} = \$model->getAllChildrenIds(\${$name});\n";
				}else{
					$className = $this->getClassNameByVar($name);
					echo "\t\t\$" . lcfirst($className) . "_ids = {$className}::model()->getAllChildrenIds(\${$name});\n";
				}
				echo "\t\tif(isset(\$_GET['{$name}']))\$criteria->addInCondition('{$name}' . {$var_name});\n";
			}elseif($setting['type'][$name]=='char' || $setting['type'][$name]=='desc'){
				echo "\t\t\$criteria->compare('$name',\${$name}, true);\n";
			}else{
				echo "\t\t\$criteria->compare('$name',\${$name});\n";
			}
		}
		?>
		if(isset($_GET['search_fields']) && isset($_GET['keywords']) && !empty($_GET['keywords']))
			$criteria->addSearchCondition($_GET['search_fields'],$_GET['keywords']);						
		$criteria->order = 'id DESC';		  //按什么字段来排序
		// 分页
		$pages = new CPagination(<?php echo $this->modelClass; ?>::model()->count($criteria));
		$pages->pageSize = <?php echo $this->modelType == 'category' ? '1000': '20'; ?>;
		$pages->applylimit($criteria);

		$list = <?php echo $this->modelClass; ?>::model()->findAll($criteria);//查询所有的数据

		$this->render('admin',array(
			'list'=><?php echo $this->modelType == 'category' ? 'CCategory::showAllChildren($list)': '$list'; ?>,
			'pages'=>$pages,
<?php
			foreach($columns as $column)
			{
				if(in_array($column->name, $setting['list_search']) && $column->name == 'parent_id'){
					echo "\t\t\t'" . lcfirst($this->modelClass) . "s'=>\$model->showAllDroplist(),\n";
				}elseif(in_array($column->name, array_merge($setting['list_search'], $setting['list_show'])) && preg_match('/(region|cat_select|select|radio|checkbox)/i', $setting['type'][$column->name]) && preg_match('/^^(.+)_id$/i',$column->name)){
					$className = $this->getClassNameByVar($column->name);
					echo "\t\t\t'" . lcfirst($className) . "s'=>{$className}::model()->get{$className}s(),\n";
				}
			}
			?>
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
