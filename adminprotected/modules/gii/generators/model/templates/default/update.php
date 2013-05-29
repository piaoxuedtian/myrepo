<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n";
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$nameColumn}=>array('view','id'=>\$model->{$this->tableSchema->primaryKey}),
	'Update',
);\n";
?>
?>

<h1>Update <?php echo $this->modelClass." <?php echo \$model->{$this->tableSchema->primaryKey}; ?>"; ?></h1>

<?php echo "<?php"; ?> echo $this->renderPartial('_form', array(
    'model'=>$model,
<?php
    foreach($setting['editable'] as $name)
    {
        if($name == 'parent_id')
        {
            $varName = lcfirst($this->modelClass);
            echo "\t'{$varName}s'=> \${$varName}s,\n";
        }elseif(preg_match('/(select|radio|checkbox|region|cat_select)/i', $setting['type'][$name]) && preg_match('/^^(.+)_id$/i',$name,$matches)){                
            $varName = lcfirst($this->getClassNameByVar($name));
            echo "\t'{$varName}s'=> \${$varName}s,\n";
        }
    }
    ?>
)); 
?>