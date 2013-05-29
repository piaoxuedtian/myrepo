<?php
$this->menu = array(
    array('label' => RbacHelper::translate('Settings'), 'url' => array('settings'), 'htmlOptions' => array('class' => 'active')),
    array('label' => RbacHelper::translate('Show allowed auth items'), 'url' => array('showAllowedAuthItems')),
);
?>
<ul class = "rbac-data-list">
    <?php foreach ($data as $key => $value): ?>
        <li><?php echo RbacHelper::translate(ucfirst($key)); ?>: <label><?php echo $value; ?></label></li>
    <?php endforeach; ?>
</ul>