<?php $this->beginContent('//layouts/main'); ?>
<div id="content">
<?php 
if (Yii::app()->user->hasFlash('success'))
{
	$this->widget('bootstrap.widgets.TbAlert', array(
		'alerts'=>array( // configurations per alert type
			'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
		),
		'htmlOptions'=>array('id'=>'message-success')
	));
}

if (Yii::app()->user->hasFlash('info'))
{
	$this->widget('bootstrap.widgets.TbAlert', array(
		'alerts'=>array( // configurations per alert type
			'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
		),
		'htmlOptions'=>array('id'=>'message-info')
	));
}

if (Yii::app()->user->hasFlash('warning'))
{
	$this->widget('bootstrap.widgets.TbAlert', array(
		'alerts'=>array( // configurations per alert type
			'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
		),
		'htmlOptions'=>array('id'=>'message-warning')
	));
}

if (Yii::app()->user->hasFlash('error'))
{
	$this->widget('bootstrap.widgets.TbAlert', array(
		'alerts'=>array( // configurations per alert type
			'error'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
		),
		'htmlOptions'=>array('id'=>'message-error')
	));
}
?>
<?php echo $content; ?>
</div><!-- content -->
<?php $this->endContent(); ?>