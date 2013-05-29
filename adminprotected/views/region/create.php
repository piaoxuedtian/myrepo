<?php
$this->breadcrumbs=array(
	'Regions'=>array('index'),
	'Create',
);
?>

<?php echo $this->renderPartial('_form', array(
    'model'=> $model,
	'regions'=> $regions,
)); 
?>
