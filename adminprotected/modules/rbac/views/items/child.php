<ul class="rbac-tabs-simple clearfix">
    <?php
    foreach ($children as $i => $child):
        $cssClass = $i == 0 ? ' class="active"' : '';
        ?>
        <li<?php echo $cssClass; ?>><a href="javascript:void(0);" rel="panel-<?php echo $i; ?>"><?php echo RbacHelper::translate(ucfirst(RbacHelper::typeIdToName($child))); ?></a></li>
    <?php endforeach; ?>
</ul>
<div class="panels clearfix">
    <?php
    foreach ($dataProviders as $i => $dataProvider):
        $style = $i != 0 ? ' style="display: none"' : '';
        ?>
        <div id="panel-<?php echo $i; ?>" class="panel"<?php echo $style; ?>>
            <?php
            $this->widget('rbac.extensions.CGridViewExt', array(
                'id' => "auth-item-child-grid-{$i}",
                'afterAjaxUpdate' => 'function(){beautifyCheckbox();}',
                'dataProvider' => $dataProvider,
                'itemsCssClass' => 'items striped',
                'template' => '{items}{pager}',
                'filter' => $filterForm,
                'columns' => array(
                    array(
                        'class' => 'CCheckBoxColumn',
                        'value' => '$data["name"]',
                        'selectableRows' => 2,
                        'checked' => '(isset($data["active"]) && $data["active"]) ? true : false',
                        'headerTemplate' => '',
                    ),
                    array(
                        'name' => 'name',
                        'header' => RbacHelper::translate('Item name'),
                        'htmlOptions' => array('class' => 'name'),
                    ),
                    array(
                        'name' => 'description',
                        'header' => RbacHelper::translate('Description'),
                        'htmlOptions' => array('class' => 'description'),
                    ),
                ),
            ));
            ?>
        </div>
    <?php endforeach;
    ?>
</div>

<script type="text/javascript">
    $(function () {
        $('.rbac-tabs-simple li a').live('click', function() {
            var t = $(this);
            t.parent().parent().children().removeClass('active');
            t.parent().addClass('active');
            $('div.panel').css('display', 'none');
            $('#' + t.attr('rel')).css('display', '');
        });

        $('input:checkbox').die().live('click', function() {
            var t = $(this);
            $.ajax({
                type: 'POST',
                url: '<?php echo $this->createUrl('child', array('type' => $type, 'parent' => $parent)); ?>',
                data: 'child=' + t.val() + '&action=' + (t.attr('checked') == 'checked' ? 'remove' : 'add'),
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
    })
</script>