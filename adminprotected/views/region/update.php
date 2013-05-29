<?php
$this->breadcrumbs=array(
	'Regions'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);
?>

<h1>Update Region <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array(
    'model'=> $model,
	'regions'=> $regions,
)); 
?>