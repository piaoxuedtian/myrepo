<?php
$this->widget('rbac.extensions.CGridViewExt', array(
    'id' => 'scaner-grid',
    'afterAjaxUpdate' => 'function(){beautifyCheckbox();}',
    'dataProvider' => $dataProvider,
    'filter' => $filterForm,
    'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',
            'value' => '"{$data["type"]}|{$data["name"]}"',
            'selectableRows' => 2,
            'checked' => '($data["active"]) ? true : false',
            'headerTemplate' => '',
        ),
        array(
            'name' => 'name',
            'header' => RbacHelper::translate('Item name'),
            'headerHtmlOptions' => array('class' => 'name'),
            'htmlOptions' => array('class' => 'name'),
        ),
        array(
            'name' => 'type',
            'header' => RbacHelper::translate('Item type'),
            'value' => 'RbacHelper::formatType($data["type"])',
            'headerHtmlOptions' => array('class' => 'type'),
            'htmlOptions' => array('class' => 'type'),
            'filter' => array(
                CAuthItem::TYPE_OPERATION => RbacHelper::translate('Operation'),
                CAuthItem::TYPE_TASK => RbacHelper::translate('Task'),
            ),
        ),
        array(
            'name' => 'description',
            'header' => RbacHelper::translate('Description'),
            'htmlOptions' => array('class' => 'description'),
        ),
    ),
));
?>

<script type="text/javascript">
    $(function () {
        beautifyCheckbox();
        $('input:checkbox').live('click', function() {
            var t = $(this);
            if (t.attr('checked') == 'checked') {
                return false;
            }
            var text = t.val();
            var type = name = '';
            if (text.length > 3) {
                var type = text.substr(0, 1);
                var name = text.substr(2, text.length);
            }
            var description = t.parent().next().next().next().html();
            if (description == '&nbsp;') {
                description = '';
            }
            $.ajax({
                type: 'POST',
                url: '<?php echo $this->createUrl('createAuthItem'); ?>',
                data: 'name=' + name + '&type=' + type + '&description=' + description,
                beforeSend: function() {
                    t.parent().next().addClass('rbac-waiting rbac-waiting-left');
                },
                success: function() {
                    t.parent().next().removeClass('rbac-waiting rbac-waiting-left');
                },
                error: function(data) {
                    t.parent().next().removeClass('rbac-waiting rbac-waiting-left');
                    alert(data);
                }
            });
            return true;
        })
    })
</script>