<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id,
);
?>
<h1>View User #<?php echo $model->id; ?></h1>

<?php $this->widget('CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'password',
		'salt',
		'email',
		'profile',
		'gender',
	),
)); ?>
