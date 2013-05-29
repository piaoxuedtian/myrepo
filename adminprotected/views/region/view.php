<?php
$this->breadcrumbs=array(
	'Regions'=>array('index'),
	$model->name,
);
?>
<h1>View Region #<?php echo $model->id; ?></h1>

<?php $this->widget('CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'parent_id',
		'desc',
		'sequence',
		'amount',
		'type',
		'status',
		'level',
	),
)); ?>
