<?php
$this->breadcrumbs=array(
	'Articles'=>array('index'),
	'Create',
);
?>

<?php echo $this->renderPartial('_form', array(
    'model'=> $model,
	'categorys'=> $categorys,
)); 
?>
