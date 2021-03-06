<?php

class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
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

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
                        		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;

		if(isset($_GET['id'])) $criteria->compare('id',$this->id);
		if(isset($_GET['username'])) $criteria->compare('username',$_GET['username'],true);
		if(isset($_GET['password'])) $criteria->compare('password',$_GET['password'],true);
		if(isset($_GET['salt'])) $criteria->compare('salt',$_GET['salt'],true);
		if(isset($_GET['email'])) $criteria->compare('email',$_GET['email'],true);
		if(isset($_GET['profile'])) $criteria->compare('profile',$_GET['profile'],true);
		if(isset($_GET['gender'])) $criteria->compare('gender',$_GET['gender'],true);
		if(isset($_GET['search_fields']) && isset($_GET['keywords']) && !empty($_GET['keywords']))
			$criteria->addSearchCondition($_GET['search_fields'],$_GET['keywords']);                        
                $criteria->order = 'id DESC';          //按什么字段来排序
                // 分页
                $pages = new CPagination(User::model()->count($criteria));
                $pages->pageSize = 1;
                $pages->applylimit($criteria);
                
                $list = User::model()->findAll($criteria);//查询所有的数据
                
		$this->render('admin',array(
			'list'=>$list,
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
		$model=User::model()->findByPk($id);
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
                            $this->batch_del('User');
                            break;
                        default:
                            break;
                    }
		}
	}        
}
