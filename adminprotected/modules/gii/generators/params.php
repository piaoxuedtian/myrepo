<?php
$editables = isset($model->setting['editable']) ? $model->setting['editable'] : array();
$requireds = isset($model->setting['required']) ? $model->setting['required'] : array();
$list_shows = isset($model->setting['list_show']) ? $model->setting['list_show'] : array();
$list_searchs = isset($model->setting['list_search']) ? $model->setting['list_search'] : array();
?>
<table>
  <tr><th>字段名称</th><th>类型</th><th>可编辑</th><th>必填</th><th>列表显示</th><th>列表搜索</th></tr>
  <?php foreach($columns as $key=>$column){
  ?>
  <tr>
    <td><?php echo $key; ?></td>
    <td>
    <?php
    $name = "{$postName}[type][{$key}]";
	if(isset($model->setting['type'][$key]) && !empty($model->setting['type'][$key])){
        $select=$model->setting['type'][$key];
	}elseif(strpos($column['Type'], 'char') !== false || strpos($column['Type'], 'varchar') !== false){
        $size = intval(preg_replace('/^(var)?char\((\d+)\)/i','${2}',$column['Type']));
        if(strpos($key, 'file') !== false){
            $select = 'file';
        }elseif(strpos($key, 'img') !== false){
            $select = 'pic';
        }elseif($size >= 255){
            $select = 'desc';
        }else{
            $select = 'char';
        }
    }elseif(strpos($column['Type'], 'text') !== false){
        $select = 'text';
    }elseif(strpos($column['Type'], 'enum') !== false){
        $select = 'enum';
    }elseif(strpos($column['Type'], 'time') !== false){
        $select = 'time';
    }elseif(strpos($column['Field'], 'desc') !== false){
        $select = 'desc';
    }elseif(strpos($column['Field'], 'cat') !== false || strpos($key, 'parent_id') !== false){
        $select = 'cat_select';
    }elseif(strpos($column['Type'], 'int') !== false){
        $select = 'number';
    }else{
        $select='default';
    }
    echo CHtml::dropDownList($name, $select, $typeList);
    ?>
    </td>
    <td><input type="checkbox" name="<?php echo "{$postName}[editable][]"; ?>" value="<?php echo $key; ?>"<?php if(in_array($key,$editables))echo ' checked="checked"'; ?> /></td>
    <td><input type="checkbox" name="<?php echo "{$postName}[required][]"; ?>" value="<?php echo $key; ?>"<?php if(in_array($key,$requireds))echo ' checked="checked"'; ?> /></td>
    <td><input type="checkbox" name="<?php echo "{$postName}[list_show][]"; ?>" value="<?php echo $key; ?>"<?php if(in_array($key,$list_shows))echo ' checked="checked"'; ?> /></td>
    <td><input type="checkbox" name="<?php echo "{$postName}[list_search][]"; ?>" value="<?php echo $key; ?>"<?php if(in_array($key,$list_searchs))echo ' checked="checked"'; ?> /></td>
  </tr>
  <?php } ?>
</table>