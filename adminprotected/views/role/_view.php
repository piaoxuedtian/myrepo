<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rname')); ?>:</b>
	<?php echo CHtml::encode($data->rname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('privs')); ?>:</b>
	<?php echo CHtml::encode($data->privs); ?>
	<br />


</div>