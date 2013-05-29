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

<?php echo "<?php"; ?> echo $this->renderPartial('_form', array(
	'model'=> $model,
<?php
	foreach($setting['editable'] as $name)
	{
		if(preg_match('/^parent_id$/i',$name))
		{
			$varName = lcfirst($this->modelClass);
			echo "\t'{$varName}s'=> \${$varName}s,\n";
		}elseif(preg_match('/(select|radio|checkbox|cat_select|region)/i', $setting['type'][$name]) && preg_match('/^^(.+)_id$/i',$name)){
			$varName = lcfirst($this->getClassNameByVar($name));
			echo "\t'{$varName}s'=> \${$varName}s,\n";
		}
	}
	?>)); 
?>
