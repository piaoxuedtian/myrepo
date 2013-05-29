<?php
$this->breadcrumbs=array(
	'Privs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Priv', 'url'=>array('index')),
	array('label'=>'Create Priv', 'url'=>array('create')),
	array('label'=>'View Priv', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Priv', 'url'=>array('admin')),
);
?>

<h1>Update Priv <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>