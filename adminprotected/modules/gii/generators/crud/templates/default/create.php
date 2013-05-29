<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n";
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Create',
);\n";
?>
?>

<?php echo "<?php echo \$this->renderPartial('_form', array(
    'model'=>\$model,";
    foreach($this->tableSchema->columns as $column)
    {
        if(preg_match('/^(\w+_)?(cat_id|cat|parent_id)$/i',$column->name,$matches))
        {
            if(preg_match('/^parent_id$/i',$column->name))
            {
                $prefix = preg_replace('/^(\w+)?category$/i', '${1}',$this->modelClass);
            }
            else
            {
                $prefix = isset($matches[1]) && !empty($matches[1]) ? preg_replace('/_$/i','',$matches[1]): '';
            }
            echo "'{$prefix}Categorys'=>\${$prefix}Categorys,";
        }
    }
    ?>)); 
?>
