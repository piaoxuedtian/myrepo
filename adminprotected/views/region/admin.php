<?php Yii::app()->getClientScript()->registerCoreScript('CGridView') ?>
<?php
$this->breadcrumbs=array(
	'区域管理',
);
?>
<?php $this->widget('CGridView', array(
	'dataProvider'=>$model->search(),
	'pager'=>array(
		'class'=>'CLinkPager',
		'cssFile'=>'page1'
	),
	'template'=>"{items}\n{pager}",
	//'filter'=>$model,
	'columns'=>array(
		'id',
		array(
			'name'=>'name',
			'type'=>'raw',//这里是原型输出
			'value'=>'CHtml::image(Yii::app()->homeUrl .\'images/menu_minus.gif\',\'404 Not Found\',array(\'width\'=>\'9\',\'height\'=>\'9\',\'onclick\'=>\'rowClicked(this)\', \'id\'=>"{$data->level}_{$data->id}",\'style\'=>"margin-left:{$data->level}em")).CHtml::encode($data->name)',
			'htmlOptions'=>array(
				'id'=>'11',
				'style'=>'text-align:left;'
			)
		),
		'parent_id',
		'sequence',
		'amount',
		'type',
		'status',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>