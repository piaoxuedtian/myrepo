<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'role-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'rname'); ?>
		<?php echo $form->textField($model,'rname',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'rname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'privs'); ?>
		<?php echo $form->textField($model,'privs',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'privs'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->