<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->homeUrl; ?>css/pager.css" />
<script type='text/javascript' src="/js/jquery.js"></script>
<script type='text/javascript' src="/js/list.js"></script>
<?php
$this->breadcrumbs=array(
	'Admins'=>array('index'),
	'Manage',
);
?>
<div class="search_box ml20 clearfix">
    <form action="/admin/index" method="GET">
    <!--
    <select name="Admins" class="fl">
        <option>角色01</option>
        <option>角色02</option>
        <option>角色03</option>
        <option>角色04</option>
    </select>
    -->
    
    <input class="fr  int_sous" type="submit" value="" />
    <input type="text" name="keywords" class="fr int_c int_180" value="<?php if(isset($_GET['keywords'])) echo $_GET['keywords']; ?>" />
    <select name="search_fields" class="fr">
        <option value="id">id</option>
    
	<option value='adminname'>adminname</option>    </select>
</form>
</div>
<?php if($pages->itemCount > 0){ ?>
<form method='POST' action='/admin/batch'>
<table class="grid-view">
  <thead>
    <tr>
      <th width='15'><input type="checkbox" class="checkAll" /></th>
      
	<th><?php echo CHtml::encode($list[0]->getAttributeLabel('id')); ?>:</th>
	<th><?php echo CHtml::encode($list[0]->getAttributeLabel('adminname')); ?>:</th>
	<th><?php echo CHtml::encode($list[0]->getAttributeLabel('password')); ?>:</th>
	<th><?php echo CHtml::encode($list[0]->getAttributeLabel('salt')); ?>:</th>
	<th><?php echo CHtml::encode($list[0]->getAttributeLabel('email')); ?>:</th>
	<th><?php echo CHtml::encode($list[0]->getAttributeLabel('profile')); ?>:</th>
      <th>操作</th>
    </tr>
  </thead> 
  <tbody>
	<?php foreach($list as $row){ ?>
    <tr>
      <td><input type="checkbox" name="ids[]" value='<?php echo $row->id; ?>' /></td>
        
	<td><?php echo CHtml::encode($row->id); ?></td>
	<td><?php echo CHtml::encode($row->adminname); ?></td>
	<td><?php echo CHtml::encode($row->password); ?></td>
	<td><?php echo CHtml::encode($row->salt); ?></td>
	<td><?php echo CHtml::encode($row->email); ?></td>
	<td><?php echo CHtml::encode($row->profile); ?></td>
      <td>
        <a target="_blank" title="View" href="view/<?php echo $row->id; ?>"><img src="/images/view.png" alt="View" /></a>
        <a class="update" title="Update" href="update/<?php echo $row->id; ?>"><img src="/images/update.png" alt="Update" /></a>
        <a class="delete" title="Delete" href="delete/<?php echo $row->id; ?>"><img src="/images/delete.png" alt="Delete" /></a>
      </td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan ="8">
        <span class="fl">
          <select name="batch">
              <option value="">请选择批量操作</option>
              <option value="del">删除</option>
          </select>
        </span>
        <span class="fr">
            <?php $this->widget('Pager',array('pages' => $pages)); ?>        </span>
      </td>
    </tr>
  </tfoot>
</table>
</form
><?php }else{ ?><div class='no-data'><h2>暂无数据</h2></div>
<?php } ?>