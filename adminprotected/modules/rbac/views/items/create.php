<?php

$this->breadcrumbs = array(
    'Auth Items' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => RbacHelper::translate('Operation'), 'url' => array('index', 'type' => 'operation')),
    array('label' => RbacHelper::translate('Task'), 'url' => array('index', 'type' => 'task')),
    array('label' => RbacHelper::translate('Role'), 'url' => array('index', 'type' => 'role')),
    array('label' => RbacHelper::translate('New authItem'), 'url' => array('create', 'type' => Yii::app()->request->getQuery('type', 'operation'))),
);
?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>