<div class="con_div clearfix">
  <div class="search_box clearfix">
    <form action="" method="get">
      &nbsp;&nbsp;<?php foreach($droplists as $k => $droplist){ ?>
      <select name="<?php echo $k; ?>" class="mr10 fl">
        <?php foreach($droplist as $key => $val){ ?> 
        <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
        <?php } ?>
      </select>
      <?php } ?>
      <input class="fr int_sous" type="submit" value="" />
      <input name="keywords" class="fr int_c int_180" type="text" value="请输入关键字搜索" />
      <div class="clear"></div>
      <div class="line2"></div>
    </form>
  </div>
</div>