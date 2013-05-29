<?php
$this->breadcrumbs=array(
	'Categories'=>array('index'),
	'Create',
);
?>

<?php echo $this->renderPartial('_form', array(
	'model'=> $model,
	'categorys'=> $categorys,
)); 
?>
