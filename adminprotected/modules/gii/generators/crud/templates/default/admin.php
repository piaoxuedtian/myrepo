<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<link rel="stylesheet" type="text/css" href="<?php echo "<?php echo Yii::app()->homeUrl; ?>"; ?>css/pager.css" />
<script type='text/javascript' src="<?php echo "<?php echo Yii::app()->homeUrl; ?>"; ?>js/jquery.js"></script>
<script type='text/javascript' src="<?php echo "<?php echo Yii::app()->homeUrl; ?>"; ?>js/list.js"></script>
<?php
echo "<?php\n";
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Manage',
);\n";
?>
?>
<div class="search_box ml20 clearfix">
    <form action="/<?php echo strtolower($this->modelClass); ?>/index" method="GET">
    <!--
    <select name="<?php echo $label; ?>" class="fl">
        <option>角色01</option>
        <option>角色02</option>
        <option>角色03</option>
        <option>角色04</option>
    </select>
    -->
    
    <input class="fr  int_sous" type="submit" value="" />
    <input type="text" name="keywords" class="fr int_c int_180" value="<?php echo "<?php if(isset(\$_GET['keywords'])) echo \$_GET['keywords']; ?>"; ?>" />
    <select name="search_fields" class="fr">
        <option value="id">id</option>
    <?php
      $count = 0;
      foreach($this->tableSchema->columns as $column)
      {
          if(preg_match('/name$/i', $column->name) || preg_match('/title$/i', $column->name))
          {
                echo "\n\t<option value='{$column->name}'>{$column->name}</option>";
          }
      }
     ?>
    </select>
</form>
</div>
<?php
echo "<?php if(\$pages->itemCount > 0){ ?>\n";
?>
<form method='POST' action='/<?php echo strtolower($this->modelClass); ?>/batch'>
<table class="grid-view">
  <thead>
    <tr>
      <th width='15'><input type="checkbox" class="checkAll" /></th>
      <?php
      $count = 0;
      foreach($this->tableSchema->columns as $column)
      {
           $count++;
           echo "\n\t<th><?php echo CHtml::encode(\$list[0]->getAttributeLabel('{$column->name}')); ?>:</th>";
      }
      echo "\n";
      ?>
      <th>操作</th>
    </tr>
  </thead> 
  <tbody<?php if(preg_match('/category$/i',$this->modelClass)) echo " id='listDiv'"; ?>>
	<?php echo "<?php"; ?> foreach($list as $row){ ?>
    <tr<?php if(preg_match('/category$/i',$this->modelClass)) echo " id='<?php echo \"{\$row->level}_{\$row->id}\"; ?>' class='<?php echo \"{\$row->level}\"; ?>'"; ?>>
      <td><input type="checkbox" name="ids[]" value='<?php echo '<?php echo $row->id; ?>'; ?>' /></td>
        <?php
        foreach($this->tableSchema->columns as $column)
        {
            echo "\n\t<td";
            if(preg_match('/category$/i',$this->modelClass) && preg_match('/(name|title)$/i',$column->name))
            {
                echo " style='text-align:left;'><img src='<?php echo Yii::app()->homeUrl; ?>images/menu_minus.gif' id='<?php echo \"{\$row->level}_{\$row->id}\"; ?>' width='9' height='9' border='0' style='margin-left:<?php echo \"{\$row->level}\"; ?>em' onclick='rowClicked(this)' />";
            }
            else
            {
                echo '>';
            }
            echo "<?php echo CHtml::encode(\$row->" . $column->name . "); ?></td>";
        }
        echo "\n";
        ?>
      <td>
        <a target="_blank" title="View" href="<?php echo "<?php echo Yii::app()->homeUrl; ?>" . strtolower($this->modelClass); ?>/view/<?php echo '<?php echo $row->id; ?>'; ?>"><img src="<?php echo "<?php echo Yii::app()->homeUrl; ?>"; ?>images/view.png" alt="View" /></a>
        <a class="update" title="Update" href="<?php echo "<?php echo Yii::app()->homeUrl; ?>" . strtolower($this->modelClass); ?>/update/<?php echo '<?php echo $row->id; ?>'; ?>"><img src="<?php echo "<?php echo Yii::app()->homeUrl; ?>"; ?>images/update.png" alt="Update" /></a>
        <a class="delete" title="Delete" href="<?php echo "<?php echo Yii::app()->homeUrl; ?>" . strtolower($this->modelClass); ?>/delete/<?php echo '<?php echo $row->id; ?>'; ?>"><img src="<?php echo "<?php echo Yii::app()->homeUrl; ?>"; ?>images/delete.png" alt="Delete" /></a>
      </td>
    </tr>
    <?php echo "<?php"; ?> } ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan ="<?php echo $count + 2; ?>">
        <span class="fl">
          <select name="batch">
              <option value="">请选择批量操作</option>
              <option value="del">删除</option>
          </select>
        </span>
        <span class="fr">
            <?php echo '<?php $this->widget(\'Pager\',array(\'pages\' => $pages)); ?>'; ?>
        </span>
      </td>
    </tr>
  </tfoot>
</table>
</form
><?php echo "<?php }else{ ?>"; ?>
<div class='no-data'><h2>暂无数据</h2></div>
<?php echo "<?php } ?>"; ?>