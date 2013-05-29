<script type="text/javascript" src="<?php echo Yii::app()->homeUrl; ?>plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->homeUrl; ?>plugins/calendar/calendar.php?lang=zh-cn"></script>
<link href="<?php echo Yii::app()->homeUrl; ?>plugins/calendar/calendar.css" rel="stylesheet" type="text/css" />
<div class="form">

<?php $form=$this->beginWidget('CMyForm', array(
	'id'=>'region-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),				
)); ?>

	<table>
		<tr>
			<th><?php echo $form->labelEx($model,'name'); ?></th>
		<td><?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>120)); ?>
			<?php echo $form->error($model,'name'); ?></td>
		</tr>
		<tr>
			<th><?php echo $form->labelEx($model,'parent_id'); ?></th>
		<td><?php echo $form->dropDownList($model,'parent_id',$regions); ?>
&nbsp;&nbsp;
			<?php echo $form->error($model,'parent_id'); ?></td>
		</tr>
		<tr>
			<th><?php echo $form->labelEx($model,'desc'); ?></th>
		<td><?php echo $form->textArea($model,'desc',array( 'rows'=>10, 'cols'=>80, 'class'=>'desc')); ?>
&nbsp;&nbsp;
			<?php echo $form->error($model,'desc'); ?></td>
		</tr>
		<tr>
			<th><?php echo $form->labelEx($model,'sequence'); ?></th>
		<td><?php echo $form->textField($model,'sequence'); ?>
&nbsp;&nbsp;
			<?php echo $form->error($model,'sequence'); ?></td>
		</tr>
		<tr>
			<th><?php echo $form->labelEx($model,'type'); ?></th>
		<td><?php echo $form->radioButtonList($model,'type',array()); ?>
&nbsp;&nbsp;
			<?php echo $form->error($model,'type'); ?></td>
		</tr>
		<tr>
			<th><?php echo $form->labelEx($model,'status'); ?></th>
		<td><?php echo $form->radioButtonList($model,'status',array()); ?>
&nbsp;&nbsp;
			<?php echo $form->error($model,'status'); ?></td>
		</tr>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->