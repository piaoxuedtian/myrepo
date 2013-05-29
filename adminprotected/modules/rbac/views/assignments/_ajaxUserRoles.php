<?php if ($roles): ?>
    <div class="rbac-grid-view">
        <table class="items">
            <thead>
                <tr>
                    <th class="icon">&nbsp;</th>
                    <th><?php echo RbacHelper::translate('Item name'); ?></th>
                    <th><?php echo RbacHelper::translate('Description'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr class="user">
                    <td class="icon">
                        <a href="javascript:void(0);" class="btn-toggle"><?php echo CHtml::image($this->assetsUrl . '/images/user-avatar.jpg'); ?></a>
                    </td>
                    <td class="name"><em id="username" class="username"></em></td>
                    <td>&nbsp;</td>
                </tr>
                <?php foreach ($roles as $role): ?>
                    <tr class="role">
                        <td class="checkbox">
                            <?php echo CHtml::checkBox('name', $role['active'], array('class' => 'role-name', 'value' => $role['name'], 'rel' => $userId)); ?>
                        </td>
                        <td class="name">
                            <?php echo $role['name']; ?>
                        </td>
                        <td class="description">
                            <?php echo $role['description']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
