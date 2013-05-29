<?php
Yii::import('system.gii.CCodeGenerator');

class ModelGenerator extends CCodeGenerator
{
	public $codeModel='gii.generators.model.ModelCode';
	/**
	 * Get table schema columns
	*/
	public function actionAjaxGetColumns()
	{
		$model=$this->prepare();
		if(isset($_POST['tableName']))
		{
			$columns = $model->getColumns(trim($_POST['tableName']));
			$postName = get_class($model) . '[setting]';
			$typeList = array(
				"default"=>'请选择类型',
				"char"=>'单行文本',
				"desc"=>'多行文本',
				"text"=>'图文并茂',
				'number'=>'纯数字文本',
				"time"=>'日期时间',
				"select"=>'普通下拉列表',
				"cat_select"=>'分类下拉列表',
				"region"=>'地区级联下拉',
				"radio"=>'单选框',
				"checkbox"=>'多选框',
				"file"=>'文件',
				"pic"=>'图片'
			);
			include(dirname(dirname(__FILE__)).'/params.php');
		}
	}
}