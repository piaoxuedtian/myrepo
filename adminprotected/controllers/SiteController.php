<?php

class SiteController extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
		
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array('login'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

		/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		if (Yii::app()->user->isGuest)
			$this->redirect(array('site/login'));
		//get the menu with id #2
		$items=$this->getMenus(0);

		$menu = array(
			'id'=>'nav',
			'activeCssClass'=>'active',
			//'linkLabelWrapper'=>'strong',
			'htmlOptions'=>array('class'=>'nav nav-list nav-tabs nav-stacked'),
			'items'=>$items
		);

		$this->renderPartial('index',array(
			'menu'=>$menu,
			'topMenu'=>$this->topMenus(),
		));
	}

	/*
	 * 从数据库获取菜单
	 */
	private function getMenus($id=0)
	{
		$results = Yii::app()->getDb()->createCommand();
		$results->select('id,title,url')->from('{{menu}}');
		$results->where('parent_id=:pid', array(':pid'=>$id));
		$results->order('sort_order ASC, title ASC');
		$results = $results->queryAll();

		$items = array();
		if(empty($results)) return $items;

		foreach($results AS $result)
		{
			$childItems=$this->getMenus($result['id']);
			if(empty($childItems)){
				$items[]=array(
					'label' => $result['title'],
					'url' => $result['url'],
					'linkLabelWrapper'=>'strong',
					'linkOptions'=>array('title'=>$result['title'],'target'=>'mainFrame'),
				);
			}else{
				$items[]=array(
					'label'=>$result['title'],
					'url' => 'javascript:;',
					'linkLabelWrapper'=>'strong',
					'linkOptions'=>array('class'=>'brand','title'=>$result['title']),
					'submenuOptions'=> array('class'=>'nav nav-tabs nav-stacked hide','style'=>'margin:0;'),
					'items'=>$childItems,
				);
			}
		}
		return $items;
	}

	/*
	 * 从数据库获取顶部菜单
	 */
	private function topMenus()
	{
		return array(
			'type'=>'inverse', // null or 'inverse'
			'brand'=>'Project name',
			'fixed'=>false,
			'brandUrl'=>'#',
			'collapse'=>true, // requires bootstrap-responsive.css
			'items'=>array(
				array(
					'class'=>'bootstrap.widgets.TbMenu',
					'items'=>array(
						array('label'=>'Home', 'url'=>'#', 'active'=>true),
						array('label'=>'Link', 'url'=>'#'),
						array('label'=>'Dropdown', 'url'=>'#', 'items'=>array(
							array('label'=>'Action', 'url'=>'#'),
							array('label'=>'Another action', 'url'=>'#'),
							array('label'=>'Something else here', 'url'=>'#'),
							'---',
							array('label'=>'NAV HEADER'),
							array('label'=>'Separated link', 'url'=>'#'),
							array('label'=>'One more separated link', 'url'=>'#'),
						)),
					),
				),
				'<form class="navbar-search pull-left" action=""><input type="text" class="search-query span2" placeholder="Search"></form>',
				array(
					'class'=>'bootstrap.widgets.TbMenu',
					'htmlOptions'=>array('class'=>'pull-right'),
					'items'=>array(
							array('label'=>date('Y年m月d日',time()), 'url'=>'#'),
							array('label'=>Yii::app()->user->name, 'url'=>'user/updatepassword'),
							array('label'=>'(Logout)', 'url'=>'site/logout'),
					),
				),
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionDefault()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('default');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}