<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'post-form',
	'type'=>'horizontal',
	'enableAjaxValidation'=>true,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),				
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'title',array('size'=>60,'maxlength'=>128)); ?>

	<?php echo $form->textAreaRow($model,'content',array('class'=>'ckeditor', 'id'=>'editor1', 'rows'=>10, 'cols'=>80)); ?>

	<?php echo $form->textFieldRow($model,'code',array('size'=>0,'maxlength'=>20)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
<!-- form -->