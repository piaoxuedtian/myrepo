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
    if(preg_match('/(file|img|image|photo)$/i',$column->name))
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
                        foreach($this->tableSchema->columns as $name=>$column)
                        {
                            if(preg_match('/^(\w+_)?(cat_id|cat|parent_id)$/i',$column->name,$matches))
                            {
                                if(preg_match('/^parent_id$/i',$column->name))
                                {
                                    $prefix = preg_replace('/^(\w+)?category$/i', '${1}',$this->modelClass);
                                }
                                else
                                {
                                    $prefix = isset($matches[1]) && !empty($matches[1]) ? preg_replace('/_$/i','',$matches[1]): '';
                                }
                                echo "'{$prefix}Categorys'=>CCategory::showAllSelectCategory('','{$prefix}Category'),\n\t";
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
    if(preg_match('/(file|img|image|photo)$/i',$column->name))
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
                        foreach($this->tableSchema->columns as $name=>$column)
                        {
                            if(preg_match('/^(\w+_)?(cat_id|cat|parent_id)$/i',$column->name,$matches))
                            {
                                if(preg_match('/^parent_id$/i',$column->name))
                                {
                                    $prefix = preg_replace('/^(\w+)?category$/i', '${1}',$this->modelClass);
                                }
                                else
                                {
                                    $prefix = isset($matches[1]) && !empty($matches[1]) ? preg_replace('/_$/i','',$matches[1]): '';
                                }
                                echo "'{$prefix}Categorys'=>CCategory::showAllSelectCategory('','{$prefix}Category'),";
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
		$criteria=new CDbCriteria;

<?php
foreach($this->tableSchema->columns as $name=>$column)
{
        echo "\t\tif(isset(\$_GET['$name'])) ";
	if($column->type==='string')
	{
		echo "\$criteria->compare('$name',\$_GET['$name'],true);\n";
	}
	else
	{
		echo "\$criteria->compare('$name',\$this->$name);\n";
	}
}
?>
		if(isset($_GET['search_fields']) && isset($_GET['keywords']) && !empty($_GET['keywords']))
			$criteria->addSearchCondition($_GET['search_fields'],$_GET['keywords']);                        
                $criteria->order = 'id DESC';          //按什么字段来排序
                // 分页
                $pages = new CPagination(<?php echo $this->modelClass; ?>::model()->count($criteria));
                $pages->pageSize = <?php echo preg_match('/category$/i',$this->modelClass) ? '1000': '1'; ?>;
                $pages->applylimit($criteria);
                
                $list = <?php echo $this->modelClass; ?>::model()->findAll($criteria);//查询所有的数据

                <?php if(preg_match('/category$/i',$this->modelClass)){ ?>
                $categoryList = array();
                CCategory::showAllCategory($categoryList, $list);
                <?php } ?>
		$this->render('admin',array(
			'list'=><?php echo preg_match('/category$/i',$this->modelClass) ? '$categoryList': '$list'; ?>,
                        'pages'=>$pages,
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
