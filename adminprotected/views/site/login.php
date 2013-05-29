<div class='span5'>
<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - 登陆';
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'verticalForm',
	'type'=>'horizontal',
	'htmlOptions' => array('class'=>'well'),
)); ?>
<?php echo CHtml::tag('h4',array('class'=>'text-center'),$this->pageTitle = Yii::app()->name . ' - 登陆');?>
<?php echo $form->textFieldRow($model, 'username', array('class'=>'span2')); ?>
<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'span2')); ?>
<?php echo $form->checkboxRow($model, 'rememberMe'); ?>
<div class="controls">
<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Login')); ?>
</div>
<?php $this->endWidget();?>
</div>