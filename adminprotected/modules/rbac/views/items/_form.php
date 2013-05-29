<div class="form-outside">
    <div class="form">

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'auth-item-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'validateOnChange' => true,
            ),
            'focus' => 'input:text[value=""]:first',
                ));
        ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="entry">
            <?php echo $form->labelEx($model, 'name'); ?>
            <div class="wrap">
                <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 64, 'class' => 'g-text')); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>
        </div>

        <div class="entry">
            <?php echo $form->labelEx($model, 'type'); ?>
            <div class="wrap remove-effect">
                <?php echo $form->dropDownList($model, 'type', $model->typeOptions()); ?>
                <?php echo $form->error($model, 'type'); ?>
            </div>
        </div>

        <div class="entry">
            <?php echo $form->labelEx($model, 'description'); ?>
            <div class="wrap">
                <?php echo $form->textArea($model, 'description', array('entrys' => 6, 'cols' => 50, 'class' => 'g-text-area')); ?>
                <?php echo $form->error($model, 'description'); ?>
            </div>
        </div>

        <div class="entry">
            <?php echo $form->labelEx($model, 'bizrule'); ?>
            <div class="wrap">
                <?php echo $form->textArea($model, 'bizrule', array('entrys' => 6, 'cols' => 50, 'class' => 'g-text-area')); ?>
                <?php echo $form->error($model, 'bizrule'); ?>
            </div>
        </div>

        <div class="entry">
            <?php echo $form->labelEx($model, 'data'); ?>
            <div class="wrap">
                <?php echo $form->textArea($model, 'data', array('entrys' => 6, 'cols' => 50, 'class' => 'g-text-area')); ?>
                <?php echo $form->error($model, 'data'); ?>
            </div>
        </div>

        <div class="entry buttons">
            <span class="button label-indent"><?php echo CHtml::submitButton($model->isNewRecord ? RbacHelper::translate('Create') : RbacHelper::translate('Save')); ?></span>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- form -->
</div>