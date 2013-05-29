<?php
$this->breadcrumbs=array(
	'Privs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Priv', 'url'=>array('index')),
	array('label'=>'Manage Priv', 'url'=>array('admin')),
);
?>

<h1>Create Priv</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>