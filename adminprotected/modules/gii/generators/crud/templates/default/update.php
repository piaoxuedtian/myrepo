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

<?php echo "<?php echo \$this->renderPartial('_form', array(
    'model'=>\$model,";
    foreach($this->tableSchema->columns as $column)
    {
        if(preg_match('/^(\w+_)?(cat_id|cat|parent_id)$/i',$column->name,$matches))
        {
            $prefix = isset($matches[1]) && !empty($matches[1]) ? preg_replace('/_$/i','',$matches[1]): '';
            echo "'{$prefix}Categorys'=>\${$prefix}Categorys,";
        }
    }
    ?>)); 
?>