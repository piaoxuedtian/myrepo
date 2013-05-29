<?php
$this->breadcrumbs=array(
	'Privs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Priv', 'url'=>array('index')),
	array('label'=>'Create Priv', 'url'=>array('create')),
	array('label'=>'Update Priv', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Priv', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Priv', 'url'=>array('admin')),
);
?>

<h1>View Priv #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'pname',
		'pid',
	),
)); ?>
