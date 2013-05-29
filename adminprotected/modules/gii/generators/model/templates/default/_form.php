<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<script type="text/javascript" src="<?php echo "<?php echo Yii::app()->homeUrl; ?>"; ?>plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo "<?php echo Yii::app()->homeUrl; ?>"; ?>plugins/calendar/calendar.php?lang=zh-cn"></script>
<link href="<?php echo "<?php echo Yii::app()->homeUrl; ?>"; ?>plugins/calendar/calendar.css" rel="stylesheet" type="text/css" />
<div class="form">
<?php echo "<?php \$form=\$this->beginWidget('CMyForm', array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),        
)); ?>\n"; ?>
  <table>
<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->autoIncrement)
		continue;
    if(in_array($column->name, $setting['editable'])){
?>
    <tr>
      <th><?php echo "<?php echo ".$this->generateActiveLabel($this->modelClass,$column)."; ?>"; ?></th>
	  <td><?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column,$setting['type'])."; ?>"; ?>&nbsp;&nbsp;
      <?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>"; ?></td>
    </tr>
<?php
    }
}
?>
    <tr class="buttons">
	  <td colspan="2"><?php echo "<?php echo CHtml::submitButton(\$model->isNewRecord ? 'Create' : 'Save'); ?>"; ?></td>
    </tr>
  </table>
<?php echo "<?php \$this->endWidget(); ?>\n"; ?>
</div><!-- form -->