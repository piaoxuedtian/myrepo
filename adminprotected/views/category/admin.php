<?php
$this->breadcrumbs=array(
	'Categories'=>array('index'),
	'Manage',
);
// 搜索框
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'inlineForm',
		'type'=>'inline',
		'method'=>'get',
		'action'=>Yii::app()->createUrl($this->route),
		'htmlOptions'=>array('class'=>'well'),
));

echo $form->textFieldRow($model,'id');

echo $form->textFieldRow($model,'name');
echo $form->dropDownList($model,'parent_id',Category::showAllDroplist());
$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'搜索'));
echo CHtml::link('新建', array('category/create'),array('class'=>'btn btn-small btn-primary pull-right'));
$this->endWidget();
// 列表
$this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'post-grid',
	'dataProvider'=>$model->search(),
	//'baseScriptUrl'=>Yii::app()->homeUrl.'js',
	'type'=>'striped bordered condensed',
	'pagerCssClass'=>'pagination pagination-centered',
	'pager'=> array('class'=>'bootstrap.widgets.TbPager','nextPageLabel'=>'»','prevPageLabel'=>'«'),
	'template'=>"{items}\n{pager}",
	'columns'=>array(
		array(
			'class'=>'CCheckBoxColumn',
			'footer'=>'<select id="batch-delete" class="input-small"><option value="0">请选择</option><option value="1">批量删除</option></select>',
			//'footerHtmlOptions'=>array('colspan'=>2),
			'value'=>'$data->id',
			'checked'=>'true',
			'selectableRows' => 2,
			'checkBoxHtmlOptions' => array('name'=>'id[]'),
		),

		'id',		array(
			'name'=>'name',
			'type'=>'raw',
			'htmlOptions'=>array('class'=>'text-left'),
			'value'=>'CHtml::image(Yii::app()->homeUrl .\'images/menu_minus.gif\',\'404 Not Found\',array(\'width\'=>\'9\',\'height\'=>\'9\',\'onclick\'=>\'rowClicked(this)\', \'id\'=>"{$data->level}_{$data->id}",\'style\'=>"margin-left:{$data->level}em")).CHtml::encode($data->name)',
		),
			array(
				'name'=>'parent_id',
				'value'=>'Category::getNameById($data->parent_id)'
			),

		'sequence',
		'amount',
		'type',
		'status',		array(
			'header'=>'操作',
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>