<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
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
		
	public function init()
	{
		Yii::app()->getClientScript()->setCoreScriptUrl(Yii::app()->homeUrl.'js');
		$this->_check_login();
		$this->_check_permit();
	}


	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
		
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			if($this->loadModel($id)->delete()){
				Yii::app()->user->setFlash('actionInfo',Yii::app()->params['actionInfo']['deleteSuccess']);
			}else {
				Yii::app()->user->setFlash('actionInfo',Yii::app()->params['actionInfo']['deleteFail']);
			}
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect($_POST['returnUrl']);
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/*
	 * @ 检查权限
	 * @params 
	 * @return 
	 */
	protected function _check_login()
	{
	}

	/*
	 * @ 检查权限
	 * @ params 
	 * @ return bool
	 */
	protected function _check_permit()
	{
	}

	/*
	* 批量删除
	* @param $mnane string 模型名称
	*
	*/
	protected function batch_del($mname)
	{
		if (Yii::app()->request->isPostRequest)
		{
			$criteria= new CDbCriteria;
			$criteria->addInCondition('id', $_POST['ids']);
			$mname::model()->deleteAll($criteria);

			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 * eq:if(isset($_POST['ajax']) && $_POST['ajax']==='article-form')
	 */

	protected function performAjaxValidation($model,$form)
	{
	if(isset($_POST['ajax']) && $_POST['ajax']===$form)
	{
		echo CActiveForm::validate($model);
		Yii::app()->end();
		}
	}
	protected function girdShowImg($data)
	{
		if(!empty($data->imgurl))
			return true;
		else
			return false;
	}
	protected function showViewUrl($type,$data){
		return str_replace('admin.php','index.php',Yii::app()->createUrl("$type/view",array('id'=>$data->id)));
	}		
}