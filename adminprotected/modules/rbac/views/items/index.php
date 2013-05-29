<?php
$this->menu = array(
    array('label' => RbacHelper::translate('Operation'), 'url' => array('index', 'type' => 'operation'), 'htmlOptions' => array('class' => ($type == 'operation') ? 'active' : null)),
    array('label' => RbacHelper::translate('Task'), 'url' => array('index', 'type' => 'task'), 'htmlOptions' => array('class' => ($type == 'task') ? 'active' : null)),
    array('label' => RbacHelper::translate('Role'), 'url' => array('index', 'type' => 'role'), 'htmlOptions' => array('class' => ($type == 'role') ? 'active' : null)),
    array('label' => RbacHelper::translate('New authItem'), 'url' => array('create', 'type' => $type)),
);

$this->widget('rbac.extensions.CGridViewExt', array(
    'id' => 'auth-item-grid',
    'afterAjaxUpdate' => 'function(){beautifyCheckbox();}',
    'dataProvider' => $dataProvider,
    'filter' => $filterForm,
    'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',
            'value' => '$data["name"]',
            'selectableRows' => 2,
            'checked' => '(isset($data["allowed"]) && $data["allowed"]) ? true : false',
            'visible' => ($type == 'operation') ? true : false,
            'htmlOptions' => array('title' => RbacHelper::translate('Set allowed auth item')),
            'headerTemplate' => '',
        ),
        array(
            'name' => 'name',
            'header' => RbacHelper::translate('Item name'),
            'value' => 'in_array(Yii::app()->request->getQuery("type"), array("task", "role")) ? CHtml::link($data["name"], array("items/child", "type" => RbacHelper::typeIdToName($data["type"]), "parent" => $data["name"]), array("class" => "ajax-loading")) : $data["name"]',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'name'),
        ),
        array(
            'header' => RbacHelper::translate('Description'),
            'name' => 'description',
            'htmlOptions' => array('class' => 'description'),
        ),
        array(
            'name' => 'bizrule',
            'header' => RbacHelper::translate('Bizrule'),
            'filter' => false,
        ),
        array(
            'name' => 'data',
            'header' => RbacHelper::translate('Data'),
            'filter' => false,
        ),
        array(
            'class' => 'rbac.extensions.CButtonColumnExt',
            'template' => '{update} {delete}',
            'headerHtmlOptions' => array('class' => "actions btn-2}"),
            'htmlOptions' => array('class' => "actions btn-2"),
        ),
    ),
));
?>

<script type="text/javascript">
    $(function() {
        beautifyCheckbox();
        $('input:checkbox').die().live('click', function() {
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
                },
                error: function(data) {
                    t.parent().next().removeClass('rbac-waiting rbac-waiting-left');
                    alert(data);
                }
            });
        });

        $('.ajax-loading').die().live('click', function() {
            var t = $(this);
            $.ajax({
                type: 'GET',
                cache: 'false',
                url: t.attr('href'),
                data: "&timestamp=" + (new Date()).getTime(),
                dataType: 'html',
                beforeSend: function() {
                    t.parent().addClass('reloading');
                    t.popover('destroy');
                    $('.popover').remove();
                },
                success: function(data) {
                    $('.ajax-loading').popover({placement: 'abutt-right', title: t.text() + ' 可分配项目列表' + '<a href="javascript:void(0);" class="close" onClick="$(\'.popover\').remove();return false;">X</a>', content: data}).parent().removeClass('reloading');
                    t.popover('show');
                    beautifyCheckbox();
                    <?php foreach ($this->children(RbacHelper::typeNameToId($type)) as $child): ?>
                        jQuery("#auth-item-child-grid-<?php echo $child; ?>").yiiGridView({'ajaxUpdate':['auth-item-child-grid-<?php echo $child; ?>'],'ajaxVar':'ajax','pagerClass':'pager','loadingClass':'rbac-grid-view-loading','filterClass':'filters','tableClass':'items striped','selectableRows':1,'enableHistory':false,'updateSelector':'{page}, {sort}','pageVar':'page','afterAjaxUpdate':function(){beautifyCheckbox();}});
                    <?php endforeach; ?>
                },
                error: function(data) {
                    t.parent().removeClass('reloading');
                    alert(data);
                }
            });

            return false;
        });
    });
</script>