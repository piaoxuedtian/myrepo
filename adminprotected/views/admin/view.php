<?php
$this->breadcrumbs=array(
	'Admins'=>array('index'),
	$model->id,
);
?>
<h1>View Admin #<?php echo $model->id; ?></h1>

<?php $this->widget('CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'adminname',
		'password',
		'salt',
		'email',
		'profile',
	),
)); ?>
