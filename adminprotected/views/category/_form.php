<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'category-form',
	'type'=>'horizontal',
	'enableAjaxValidation'=>true,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),				
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('size'=>60,'maxlength'=>120)); ?>

	<?php echo $form->dropDownListRow($model,'parent_id',$categorys); ?>

	<?php echo $form->textAreaRow($model,'desc',array('rows'=>10, 'cols'=>80,'class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'sequence'); ?>

	<?php echo $form->radioButtonListInlineRow($model,'type',Category::allType()); ?>

	<?php echo $form->radioButtonListInlineRow($model,'status',Category::allStatus()); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
<!-- form -->