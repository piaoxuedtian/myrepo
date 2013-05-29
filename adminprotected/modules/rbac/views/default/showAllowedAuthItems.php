<?php
$this->menu = array(
    array('label' => RbacHelper::translate('Settings'), 'url' => array('settings')),
    array('label' => RbacHelper::translate('Show allowed auth items'), 'url' => array('showAllowedAuthItems'), 'htmlOptions' => array('class' => 'active')),
);

$this->widget('rbac.extensions.CGridViewExt', array(
    'id' => 'auth-item-grid',
    'afterAjaxUpdate' => 'function(){beautifyCheckbox();}',
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',
            'value' => '$data["name"]',
            'selectableRows' => 2,
            'checked' => '(1 == 1) ? true : false',
            'headerTemplate' => '',
        ),
        array(
            'name' => 'name',
            'header' => RbacHelper::translate('Item name'),
            'value' => 'in_array(Yii::app()->request->getQuery("type"), array("task", "role")) ? CHtml::link($data["name"], array("items/child", "type" => (Yii::app()->request->getQuery("type") == "task") ? "operation" : "task", "parent" => $data["name"]), array("class" => "ajax-loading")) : $data["name"]',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'name'),
        ),
        array(
            'header' => RbacHelper::translate('Description'),
            'name' => 'description',
            'htmlOptions' => array('class' => 'description'),
        ),
    ),
));
?>

<script type="text/javascript">
    $(function() {
        beautifyCheckbox();
        $('input:checkbox').live('click', function() {
            var t = $(this);
            $.ajax({
                type: 'POST',
                url: '<?php echo $this->createUrl('items/setAllowedAuthItem'); ?>',
                data: 'name=' + t.val() + '&action=' + (t.attr('checked') == 'checked' ? 'remove' : 'add'),
                beforeSend: function() {
                    t.parent().next().addClass('rbac-waiting rbac-waiting-left');
                },
                success: function() {
                    t.parent().next().removeClass('rbac-waiting rbac-waiting-left');
                    t.parent().parent().fadeOut('fast').remove('slow');
                },
                error: function(data) {
                    t.parent().next().removeClass('rbac-waiting rbac-waiting-left');
                    alert(data);
                }
            });
        });
    });
</script>