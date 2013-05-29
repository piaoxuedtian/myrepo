<?php
$class=get_class($model);
Yii::app()->clientScript->registerScript('gii.model',"
$('#{$class}_modelClass').change(function(){
	$(this).data('changed',$(this).val()!='');
});
$('#{$class}_tableName').bind('keyup change', function(){
	var model=$('#{$class}_modelClass');
	var tableName=$(this).val();
	if(tableName.substring(tableName.length-1)!='*') {
		$('.form .row.model-class').show();
	}
	else {
		$('#{$class}_modelClass').val('');
		$('.form .row.model-class').hide();
	}
	if(!model.data('changed')) {
		var i=tableName.lastIndexOf('.');
		if(i>=0)
			tableName=tableName.substring(i+1);
		var tablePrefix=$('#{$class}_tablePrefix').val();
		if(tablePrefix!='' && tableName.indexOf(tablePrefix)==0)
			tableName=tableName.substring(tablePrefix.length);
		var modelClass='';
		$.each(tableName.split('_'), function() {
			if(this.length>0)
				modelClass+=this.substring(0,1).toUpperCase()+this.substring(1);
		});
		model.val(modelClass);
	}
});
$('.form .row.model-class').toggle($('#{$class}_tableName').val().substring($('#{$class}_tableName').val().length-1)!='*');

$('#{$class}_tableName').change(function(){
	var tableName=$(this).val();
	// 模块生成选项
	$.post('model/AjaxGetColumns', {tableName:tableName,YII_CSRF_TOKEN:'".Yii::app()->request->csrfToken."'}, function(data){
		$('div#columns-setting').html(data);
	});
	$('#{$class}_controller').val(tableName);
});
");
?>
<h1>Model Generator</h1>

<p>This generator generates a model class for the specified database table.</p>

<?php $form=$this->beginWidget('CCodeForm', array('model'=>$model)); ?>

	<div class="row sticky">
		<?php echo $form->labelEx($model,'tablePrefix'); ?>
		<?php echo $form->textField($model,'tablePrefix', array('size'=>65)); ?>
		<div class="tooltip">
		This refers to the prefix name that is shared by all database tables.
		Setting this property mainly affects how model classes are named based on
		the table names. For example, a table prefix <code>tbl_</code> with a table name <code>tbl_post</code>
		will generate a model class named <code>Post</code>.
		<br/>
		Leave this field empty if your database tables do not use common prefix.
		</div>
		<?php echo $form->error($model,'tablePrefix'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'tableName'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
			'model'=>$model,
			'attribute'=>'tableName',
			'name'=>'tableName',
			'source'=>array_keys(Yii::app()->db->schema->getTables()),
			'options'=>array(
				'minLength'=>'0',
			),
			'htmlOptions'=>array(
				'id'=>'ModelCode_tableName',
				'size'=>'65'
			),
		)); ?>
		<div class="tooltip">
		This refers to the table name that a new model class should be generated for
		(e.g. <code>tbl_user</code>). It can contain schema name, if needed (e.g. <code>public.tbl_post</code>).
		You may also enter <code>*</code> (or <code>schemaName.*</code> for a particular DB schema)
		to generate a model class for EVERY table.
		</div>
		<?php echo $form->error($model,'tableName'); ?>
	</div>
	<div class="row model-class">
		<?php echo $form->label($model,'modelClass',array('required'=>true)); ?>
		<?php echo $form->textField($model,'modelClass', array('size'=>65)); ?>
		<div class="tooltip">
		This is the name of the model class to be generated (e.g. <code>Post</code>, <code>Comment</code>).
		It is case-sensitive.
		</div>
		<?php echo $form->error($model,'modelClass'); ?>
	</div>
	<div class="row">
            <?php echo $form->label($model,'modelType',array('required'=>true)); ?>
            <?php echo $form->dropDownList($model,'modelType',array('normal'=>'常规','category'=>'分类'),array()); ?>
            <div class="tooltip">
            This is the setting of the model class to be generated (e.g. <code>Post</code>, <code>Comment</code>).
            It is case-sensitive.
            </div>
            <?php echo $form->error($model,'modelType'); ?>
        </div>
	<div class="row">
            <?php echo $form->label($model,'setting',array('required'=>true)); ?>
            <div id="columns-setting">
            <?php
            if(isset($model->tableName) && !empty($model->tableName))
            {
                $columns = $model->getColumns(trim($model->tableName));
				if(!empty($columns)){
					$postName = get_class($model) . '[setting]';
					$typeList = array(
						"default"=>'请选择类型',
						"char"=>'单行文本',
						"desc"=>'多行文本',
						"text"=>'图文并茂',
						'number'=>'纯数字文本',
						"time"=>'日期时间',
						"select"=>'普通下拉列表',
						"cat_select"=>'分类下拉列表',
						"region"=>'地区级联下拉',
						"radio"=>'单选框',
						"checkbox"=>'多选框',
						"file"=>'文件',
						"pic"=>'图片'
					);

                    include('params.php');
				}
            }
            ?>
            </div>
            <div>
            This is the setting of the model class to be generated (e.g. <code>Post</code>, <code>Comment</code>).
            It is case-sensitive.<br />
            1. 类型---添加时,把该字段当什么类型来处理<br />
            2. 可编辑---添加时,把该字段是否需要手动填写或者选择<br />
            3. 必填---添加时,该字段是否是必填项<br />
            4. 列表显示---是否在列表展示该字段<br />
            5. 列表搜索---是否可以通过该字段搜索列表
            </div>
            <?php echo $form->error($model,'setting'); ?>
        </div>
	<div class="row sticky">
		<?php echo $form->labelEx($model,'baseClass'); ?>
		<?php echo $form->textField($model,'baseClass',array('size'=>65)); ?>
		<div class="tooltip">
			This is the class that the new model class will extend from.
			Please make sure the class exists and can be autoloaded.
		</div>
		<?php echo $form->error($model,'baseClass'); ?>
	</div>
	<div class="row sticky">
		<?php echo $form->labelEx($model,'modelPath'); ?>
		<?php echo $form->textField($model,'modelPath', array('size'=>65)); ?>
		<div class="tooltip">
			This refers to the directory that the new model class file should be generated under.
			It should be specified in the form of a path alias, for example, <code>application.models</code>.
		</div>
		<?php echo $form->error($model,'modelPath'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'controller'); ?>
		<?php echo $form->textField($model,'controller',array('size'=>65)); ?>
		<div class="tooltip">
			Controller ID is case-sensitive. CRUD controllers are often named after
			the model class name that they are dealing with. Below are some examples:
			<ul>
				<li><code>post</code> generates <code>PostController.php</code></li>
				<li><code>postTag</code> generates <code>PostTagController.php</code></li>
				<li><code>admin/user</code> generates <code>admin/UserController.php</code>.
					If the application has an <code>admin</code> module enabled,
					it will generate <code>UserController</code> (and other CRUD code)
					within the module instead.
				</li>
			</ul>
		</div>
		<?php echo $form->error($model,'controller'); ?>
	</div>

	<div class="row sticky">
		<?php echo $form->labelEx($model,'baseControllerClass'); ?>
		<?php echo $form->textField($model,'baseControllerClass',array('size'=>65)); ?>
		<div class="tooltip">
			This is the class that the new CRUD controller class will extend from.
			Please make sure the class exists and can be autoloaded.
		</div>
		<?php echo $form->error($model,'baseControllerClass'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'buildRelations'); ?>
		<?php echo $form->checkBox($model,'buildRelations'); ?>
		<div class="tooltip">
			Whether relations should be generated for the model class.
			In order to generate relations, full scan of the whole database is needed.
			You should disable this option if your database contains too many tables.
		</div>
		<?php echo $form->error($model,'buildRelations'); ?>
	</div>

<?php $this->endWidget(); ?>
