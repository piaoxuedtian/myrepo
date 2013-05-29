<script type="text/javascript" src="<?php echo Yii::app()->homeUrl; ?>plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->homeUrl; ?>plugins/calendar/calendar.php?lang=zh-cn"></script>
<link href="<?php echo Yii::app()->homeUrl; ?>plugins/calendar/calendar.css" rel="stylesheet" type="text/css" />
<div class="form">
<?php $form=$this->beginWidget('CMyForm', array(
	'id'=>'article-form',
	'type'=>'inline',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),				
)); ?>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>128)); ?>
	
<?php $this->endWidget(); ?>
</div><!-- form -->