<?php
echo CHtml::dropDownList('users_list', null, $users, array('data-placeholder' => RbacHelper::translate('Please select user'), 'prompt' => ''));
$this->clientScript->registerScriptFile($this->assetsUrl . '/chosen/chosen.jquery.min.js');
$this->clientScript->registerCssFile($this->assetsUrl . '/chosen/chosen.css');
$this->clientScript->registerScript($this->id, 'beautifyCheckbox();');
?>

<div id="ajax-update">
    <?php $this->renderPartial('_ajaxUserRoles', array('roles' => $roles)); ?>
    <div class="user-auth-assignment-pointer"></div>
</div>

<script type="text/javascript">
    $(function() {
        $("#users_list").chosen({
            no_results_text: "找不到匹配数据"
        }).change(function() {
            var t = $(this);
            $.ajax({
                type: 'GET',
                url: '<?php echo $this->createUrl('assignments/userRoles'); ?>',
                data: 'userId=' + t.val() + '&timestamp=' + (new Date()).getTime(),
                beforeSend: function() {
                    $('#ajax-update').addClass('rbac-waiting rbac-waiting-center');
                },
                success: function(data) {
                    $('#ajax-update').html(data).removeClass('rbac-waiting rbac-waiting-center');
                    $('#username').html(t.find("option:selected").text());
                    beautifyCheckbox();
                },
                error: function(data) {
                    $('#ajax-update').removeClass('rbac-waiting rbac-waiting-center');
                    alert(data);
                }
            });
        });
        
        $('.role-name').die().live('click', function() {
            var t = $(this);
            $.ajax({
                type: 'POST',
                url: '<?php echo $this->createUrl('assignments/assign'); ?>',
                data: 'name=' + t.val() + '&userId=' + t.attr('rel') + '&action=' + (t.attr('checked') == 'checked' ? 'revoke' : 'add') + '&timestamp=' + (new Date()).getTime(),
                beforeSend: function() {
                    t.parent().next().addClass('rbac-waiting rbac-waiting-left');
                },
                success: function(data) {
                    t.parent().next().removeClass('rbac-waiting rbac-waiting-left');
                },
                error: function(data) {
                    t.parent().next().removeClass('rbac-waiting rbac-waiting-left');
                    alert(data);
                }
            });
        });
    });
</script>