<?php
$this->breadcrumbs=array(
	'Posts'=>array('index'),
	'Create',
);
?>

<?php echo $this->renderPartial('_form', array(
	'model'=> $model,
)); 
?>
