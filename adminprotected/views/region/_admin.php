<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->homeUrl; ?>css/pager.css" />
<script type='text/javascript' src="<?php echo Yii::app()->homeUrl; ?>js/jquery.js"></script>
<script type='text/javascript' src="<?php echo Yii::app()->homeUrl; ?>js/list.js"></script>
<?php
$this->breadcrumbs=array(
	'Regions'=>array('index'),
	'Manage',
);
?>
<div class="search_box ml20 clearfix">
    <form action="../region/index" method="GET">
    
    <input class="fr  int_sous" type="submit" value="" />
    <input type="text" name="keywords" class="fr int_c int_180" value="<?php if(isset($_GET['keywords'])) echo $_GET['keywords']; ?>" />
    <select name="search_fields" class="fr">
    <option value="id">id</option>
    
	<option value='name'>名称</option>    </select>
</form>
</div>
<?php if($pages->itemCount > 0){ ?>
<form method='POST' action='/region/batch'>
<table class="grid-view">
  <thead>
    <tr>
      <th width='15'><input type="checkbox" class="checkAll" /></th>
      	<th>ID</th>	<th>名称</th>	<th>上级区域</th>	<th>排序序号</th>	<th>区域类型</th>	<th>状态</th>
      <th>操作</th>
    </tr>
  </thead> 
  <tbody id='listDiv'>
	<?php foreach($list as $row){ ?>
    <tr id='<?php echo "{$row->level}_{$row->id}"; ?>' class='<?php echo "{$row->level}"; ?>'>
      <td><input type="checkbox" name="ids[]" value='<?php echo $row->id; ?>' /></td>
        	<td><?php echo CHtml::encode($row->id); ?></td>	<td style='text-align:left;'><img src='<?php echo Yii::app()->homeUrl; ?>images/menu_minus.gif' id='<?php echo "{$row->level}_{$row->id}"; ?>' width='9' height='9' border='0' style='margin-left:<?php echo "{$row->level}"; ?>em' onclick='rowClicked(this)' /><?php echo CHtml::encode($row->name); ?></td>	<td><?php echo CHtml::encode($row->parent_id); ?></td>	<td><?php echo CHtml::encode($row->sequence); ?></td>	<td><?php echo CHtml::encode($row->type); ?></td>	<td><?php echo CHtml::encode($row->status); ?></td>
      <td>
        <a target="_blank" title="View" href="<?php echo Yii::app()->homeUrl; ?>region/view/<?php echo $row->id; ?>"><img src="<?php echo Yii::app()->homeUrl; ?>images/view.png" alt="View" /></a>
        <a class="update" title="Update" href="<?php echo Yii::app()->homeUrl; ?>region/update/<?php echo $row->id; ?>"><img src="<?php echo Yii::app()->homeUrl; ?>images/update.png" alt="Update" /></a>
        <a class="delete" title="Delete" href="<?php echo Yii::app()->homeUrl; ?>region/delete/<?php echo $row->id; ?>"><img src="<?php echo Yii::app()->homeUrl; ?>images/delete.png" alt="Delete" /></a>
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
          <?php $this->widget('Pager',array('pages' => $pages)); ?>\n        </span>
      </td>
    </tr>
  </tfoot>
</table>
</form>
<?php }else{ ?><div class='no-data'><h2>暂无数据</h2></div>
<?php } ?>