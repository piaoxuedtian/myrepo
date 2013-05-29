<div class="con_div clearfix">
<form action="" method="get">  	
<table class="table_list" border="0" cellspacing="0">
  <tr>
    <th><input type="checkbox" class="checkAll" /></th>
  <?php
    foreach($columns as $item)
    {
  ?>
    <th><?php echo CHtml::encode($item); ?></th>
  <?php
    }
  ?>
    <th>操作</th>
  </tr>
  <?php
  foreach($data as $row)
  {
  ?>
  <tr class="gray"  OnMouseOver="player('any1');" onMouseOut="clocer('any1');">
    <td width="20"><input type="checkbox" name="checkBox" value="<?php echo CHtml::encode($row->id); ?>" /></td>
  <?php
    foreach($columns as $k => $item)
    {
  ?>
    <td><?php echo CHtml::encode($row->$k); ?></td>
  <?php
    }
  ?>
    <td>
  <?php foreach($operates as $ope){ ?>
    <a href="<?php echo $ope; ?>/<?php echo CHtml::encode($row->id); ?>"><img src="../../images/<?php echo $ope; ?>.png" /></a>
  <?php } ?>
    </td>
  </tr>
  <?php
  }
  ?>
  <tr>
    <td colspan='<?php echo count($columns) + 2; ?>'>
      <div class="pager">
        <div id="yw1" class="yiiPager">
          <span class="fl"><select name="batch_ope">
            <option value="0">请选择批量操作</option>
          <?php if(!empty($batch_opes)){ foreach($batch_opes as $k => $operate){ ?>
            <option value="<?php echo $k; ?>"><?php echo $operate; ?></option>
          <?php }} ?>
          </select>
          </span>
          <?php if(isset($pager)){$this->widget('CLinkPager',$pager);} ?>
          <span class="fr">1<a class="page" href="/index.php?r=adminuser/admin&amp;AdminUser_page=2">2</a> <a class="next" href="/index.php?r=adminuser/admin&amp;AdminUser_page=2">下一页</a> <a class="last" href="/index.php?r=adminuser/admin&amp;AdminUser_page=2">末页</a> 跳转到
            <input type="text" size="10" value="" id="jump_input">
            页
            <input type="button" value="跳转" class="turn" id="jump_button">
            每页显示
            <select id="perpage" name="per_page">
              <option value="10" selected="selected">10</option>
              <option value="20">20</option>
              <option value="40">10</option>
            </select>
            <input type="hidden" id="current_controller" value="Adminuser">
          </span>
          <span class="fr">共<?php echo $pages['pages'] . '页' . $pages['count']; ?>条记录&nbsp;&nbsp;</span>
        </div>
      </div>
    </td>
  </tr>
</table>
</form>
</div>