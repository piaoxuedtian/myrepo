<?php
$this->breadcrumbs=array(
	'Posts'=>array('index'),
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

echo $form->textFieldRow($model,'title');
echo $form->textFieldRow($model,'code');
$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'搜索'));
echo CHtml::link('新建', array('post/create'),array('class'=>'btn btn-small btn-primary pull-right'));
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

		'title',
		'content',
		'code',
		'status',
		'votes',		array(
			'header'=>'操作',
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>