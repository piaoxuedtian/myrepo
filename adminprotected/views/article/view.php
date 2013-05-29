<?php
$this->breadcrumbs=array(
	'Articles'=>array('index'),
	$model->title,
);
?>
<h1>View Article #<?php echo $model->id; ?></h1>

<?php $this->widget('CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'content',
		'author',
		'add_time',
		'last_update',
		'file',
		'category_id',
	),
)); ?>
