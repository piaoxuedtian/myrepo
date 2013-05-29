<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);

// 搜索框
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'searchForm',
	'type'=>'search',
	'method'=>'get',
	'htmlOptions'=>array('class'=>'well'),
));
echo $form->textFieldRow($model, 'id', array('class'=>'input-small'));
echo $form->textFieldRow($model, 'username', array('class'=>'input-small'));
$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'搜索'));
$this->endWidget();

// 列表
$this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'post-grid',
	'dataProvider'=>$model->search(),
	//'baseScriptUrl'=>Yii::app()->homeUrl.'js',
	'type'=>'striped bordered',
	'template'=>"{items}\n{pager}",
	'pagerCssClass'=>'pagination pagination-centered',
	'pager'=> array('class'=>'bootstrap.widgets.TbPager','nextPageLabel'=>'>','prevPageLabel'=>'<'),
	'columns'=>array(
		array(
			'class'=>'CCheckBoxColumn',
			'value'=>'$data->id',
			'selectableRows'=>2,
			'checkBoxHtmlOptions'=>array('name'=>'id[]'),
		),
		'id',
		'username',
		'email',
		'gender',
		array(
			'header'=>'操作',
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'htmlOptions'=>array('style'=>'width:150px'),
		),
	),
)); ?>