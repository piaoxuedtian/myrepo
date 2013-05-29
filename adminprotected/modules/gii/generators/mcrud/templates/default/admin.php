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
	'Manage',
);\n";
?>
// 搜索框
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'inlineForm',
		'type'=>'inline',
		'method'=>'get',
		'action'=>Yii::app()->createUrl($this->route),
		'htmlOptions'=>array('class'=>'well'),
));
<?php
	foreach($setting['list_search'] as $name)
	{
		if($name == 'parent_id')
		{
			$varName = '$' . lcfirst($this->modelClass) . 's';
			echo "echo \$form->dropDownList(\$model,'{$name}',{$this->modelClass}::showAllDroplist());\n";
		}elseif(preg_match('/(select|radio|checkbox|cat_select|region)/i', $setting['type'][$name])){
			if(preg_match('/^(.+)_id$/i',$name)){
				$varName = '$' . lcfirst($this->getClassNameByVar($name)) . 's';
			}else{
				$varName = "array()";
			}
			echo "\necho \$form->dropDownList(\$model,'{$name}',{$varName});\n";
		}else{
			echo "\necho \$form->textFieldRow(\$model,'{$name}');\n";
		}
	}
?>
$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'搜索'));
echo CHtml::link('新建', array('<?php echo lcfirst($this->modelClass); ?>/create'),array('class'=>'btn btn-small btn-primary pull-right'));
$this->endWidget();
// 列表
$this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'post-grid',
	'dataProvider'=>$model->search(),
	//'baseScriptUrl'=>Yii::app()->homeUrl.'js',
	'type'=>'striped bordered condensed',
	'pagerCssClass'=>'pagination pagination-centered',
	'pager'=> array('class'=>'bootstrap.widgets.TbPager','nextPageLabel'=>'»','prevPageLabel'=>'«'),
	'template'=>"{items}\n{pager}",
	'columns'=>array(
		array(
			'class'=>'CCheckBoxColumn',
			'footer'=>'<select id="batch-delete" class="input-small"><option value="0">请选择</option><option value="1">批量删除</option></select>',
			//'footerHtmlOptions'=>array('colspan'=>2),
			'value'=>'$data->id',
			'checked'=>'true',
			'selectableRows' => 2,
			'checkBoxHtmlOptions' => array('name'=>'id[]'),
		),
<?php
		foreach($setting['list_show'] as $name){
			if($modelType == 'category' && preg_match('/(name|title)$/i',$name))
			{
?>
		array(
			'name'=>'<?php echo $name; ?>',
			'type'=>'raw',
			'htmlOptions'=>array('class'=>'text-left'),
			'value'=>'CHtml::image(Yii::app()->homeUrl .\'images/menu_minus.gif\',\'404 Not Found\',array(\'width\'=>\'9\',\'height\'=>\'9\',\'onclick\'=>\'rowClicked(this)\', \'id\'=>"{$data->level}_{$data->id}",\'style\'=>"margin-left:{$data->level}em")).CHtml::encode($data->name)',
		),
<?php
			}elseif($name=='parent_id'){
?>
			array(
				'name'=>'parent_id',
				'value'=>'<?php echo $modelClass; ?>::getNameById($data->parent_id)'
			),
<?php
			}elseif(preg_match('/_id$/i',$name)){
?>
			array(
				'name'=>'<?php echo $name; ?>',
				'value'=>'<?php echo ucfirst($this->getClassNameByVar($name)); ?>::getNameById($data-><?php echo $name; ?>)'
			),
<?php
			}else{
				echo "\n\t\t'{$name}',";
			}
		}
?>
		array(
			'header'=>'操作',
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>