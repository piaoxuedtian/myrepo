<?php
Yii::import('system.gii.generators.model.ModelCode');
class McrudCode extends ModelCode
{
	public $controller;
	public $baseControllerClass='Controller';
	public $modelType='normal';
	public $setting=array();

	/**
	 * @var array list of candidate relation code. The array are indexed by AR class names and relation names.
	 * Each element represents the code of the one relation in one AR class.
	 */
	protected $relations;

	public function rules()
	{
		return array_merge(parent::rules(), array(
			array('tablePrefix, baseClass, tableName, modelClass, modelPath', 'filter', 'filter'=>'trim'),
			array('tableName, modelPath, modelType,modelClass, setting, baseClass,controller,baseControllerClass', 'required'),
			array('tablePrefix, tableName, modelPath', 'match', 'pattern'=>'/^(\w+[\w\.]*|\*?|\w+\.\*)$/', 'message'=>'{attribute} should only contain word characters, dots, and an optional ending asterisk.'),
			array('tableName', 'validateTableName', 'skipOnError'=>true),
			array('tablePrefix, modelClass, baseClass', 'match', 'pattern'=>'/^[a-zA-Z_]\w*$/', 'message'=>'{attribute} should only contain word characters.'),
			array('modelPath', 'validateModelPath', 'skipOnError'=>true),
			array('baseClass, modelClass', 'validateReservedWord', 'skipOnError'=>true),
			array('baseClass', 'validateBaseClass', 'skipOnError'=>true),
			array('tablePrefix, modelPath, baseClass, buildRelations', 'sticky'),
		));
	}

	public function attributeLabels()
	{
		return array(
			'modelType'=>'模型类型',
			'setting'=>'设置',
		);
	}

	public function prepare()
	{
		if(($pos=strrpos($this->tableName,'.'))!==false)
		{
			$schema=substr($this->tableName,0,$pos);
			$tableName=substr($this->tableName,$pos+1);
		}
		else
		{
			$schema='';
			$tableName=$this->tableName;
		}
		if($tableName[strlen($tableName)-1]==='*')
		{
			$tables=Yii::app()->db->schema->getTables($schema);
			if($this->tablePrefix!='')
			{
				foreach($tables as $i=>$table)
				{
					if(strpos($table->name,$this->tablePrefix)!==0)
						unset($tables[$i]);
				}
			}
		}
		else
			$tables=array($this->getTableSchema1($this->tableName));

		$this->files=array();
		$templatePath=$this->templatePath;
		$this->relations=$this->generateRelations();

		foreach($tables as $table)
		{
			$tableName=$this->removePrefix($table->name);
			$className=$this->generateClassName($table->name);
			$params=array(
				'tableName'=>$schema==='' ? $tableName : $schema.'.'.$tableName,
				'modelClass'=>$this->modelClass,
				'columns'=>$table->columns,
				'labels'=>$this->generateLabels($table),
				'rules'=>$this->generateRules($table),
				'relations'=>isset($this->relations[$className]) ? $this->relations[$className] : array(),
				'modelType'=>$this->modelType,
				'setting'=>$this->setting,
			);
			$this->files=array();
			$templatePath=$this->templatePath;
			$controllerTemplateFile=$templatePath.DIRECTORY_SEPARATOR.'controller.php';

			$this->files[]=new CCodeFile(
				Yii::getPathOfAlias($this->modelPath).'/'.$className.'.php',
				$this->render($templatePath.'/model.php', $params)
			);
			$this->files[]=new CCodeFile(
				$this->controllerFile,
				$this->render($controllerTemplateFile, $params)
			);

			$files=scandir($templatePath);
			foreach($files as $file)
			{
				if(is_file($templatePath.'/'.$file) && CFileHelper::getExtension($file)==='php' && $file!=='controller.php' && $file!=='model.php')
				{
					$this->files[]=new CCodeFile(
						$this->viewPath.DIRECTORY_SEPARATOR.$file,
						$this->render($templatePath.'/'.$file, $params)
					);
				}
			}
		}
	}
	/*
	 * Check that all database field names conform to PHP variable naming rules
	 * For example mysql allows field name like "2011aa", but PHP does not allow variable like "$model->2011aa"
	 * @param CDbTableSchema $table the table schema object
	 * @return string the invalid table column name. Null if no error.
	 */
	public function checkColumns($table)
	{
		foreach($table->columns as $column)
		{
			if(!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/',$column->name))
				return $table->name.'.'.$column->name;
		}
	}

	public function validateModelPath($attribute,$params)
	{
		if(Yii::getPathOfAlias($this->modelPath)===false)
			$this->addError('modelPath','Model Path must be a valid path alias.');
	}

	public function validateBaseClass($attribute,$params)
	{
		$class=@Yii::import($this->baseClass,true);
		if(!is_string($class) || !$this->classExists($class))
			$this->addError('baseClass', "Class '{$this->baseClass}' does not exist or has syntax error.");
		else if($class!=='CActiveRecord' && !is_subclass_of($class,'CActiveRecord'))
			$this->addError('baseClass', "'{$this->model}' must extend from CActiveRecord.");
	}

	public function getTableSchema1($tableName)
	{
		return Yii::app()->db->getSchema()->getTable($tableName);
	}

	public function generateLabels($table)
	{
		$columns = $this->getColumns($table->name);
		$labels=array();
		foreach($table->columns as $column)
		{
			$label=ucwords(trim(strtolower(str_replace(array('-','_'),' ',preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $column->name)))));
			$label=preg_replace('/\s+/',' ',$label);
			if(strcasecmp(substr($label,-3),' id')===0)
				$label=substr($label,0,-3);
			if($label==='Id')
				$label='ID';
			$labels[$column->name]=$columns[$column->name]['Comment'];
		}
		return $labels;
	}

	public function generateRules($table)
	{
		$rules=array();
		$required=array();
		$integers=array();
		$numerical=array();
		$length=array();
		$safe=array();
		$imgs=array(); // 图片
		$files=array(); // 文件
		// 增加自定义判断
		$types=$this->setting['type'];			   
		foreach($table->columns as $column)
		{
			if($column->autoIncrement)
				continue;
			$r=!$column->allowNull && $column->defaultValue===null;
			if($r || in_array($column->name, $this->setting['required']))
				$required[]=$column->name;
			if($column->type==='integer')
				$integers[]=$column->name;
			else if($column->type==='double')
				$numerical[]=$column->name;
			else if($column->type==='string' && $column->size>0)
				$length[$column->size][]=$column->name;
			else if(!$column->isPrimaryKey && !$r)
				$safe[]=$column->name;
			// 增加
			else if(strpos('_file',$types[$column->name]) !== false)
				$files[]=$column->name;
			else if(strpos('_img',$types[$column->name]) !== false || strpos('photo',$types[$column->name]) !== false)
				$imgs[]=$column->name;
		}
		if($required!==array())
			$rules[]="array('".implode(', ',$required)."', 'required')";
		if($integers!==array())
			$rules[]="array('".implode(', ',$integers)."', 'numerical', 'integerOnly'=>true)";
		if($numerical!==array())
			$rules[]="array('".implode(', ',$numerical)."', 'numerical')";
		if($length!==array())
		{
			foreach($length as $len=>$cols)
				$rules[]="array('".implode(', ',$cols)."', 'length', 'max'=>$len)";
		}
		if($safe!==array())
			$rules[]="array('".implode(', ',$safe)."', 'safe')";
		if($files!==array())
			$rules[]="array('".implode(', ',$files)."', 'file','types'=>'rar,doc,swf,mp3', 'allowEmpty' => true, 'maxSize'=>1024 * 1024 * 1, 'tooLarge'=> 'The file was larger than 1MB. Please upload a smaller file')";
		if($imgs!==array())
			$rules[]="array('".implode(', ',$files)."','file','types'=>'jpg, gif, png', 'allowEmpty' => true, 'maxSize'=>1024 * 1024 * 1, 'tooLarge'=> 'The image was larger than 1MB. Please upload a smaller image')";

		return $rules;
	}

	public function getRelations($className)
	{
		$columns = $this->getColumns($table->name);
		if(!isset($this->relations[$className])){
			$this->relations[$className] = array();
		}
		foreach($columns as $col){
			if(preg_match('/_id^/i',$col['Filed'])){
				$name = preg_replace('/([\w_]+)_id/i', "${1}", $col['Filed']);
				$this->relations[$className][$name] = "array(self::BELONGS_TO, '" . $this->generateClassName($name) . "', '" . $col['Filed'] . "')";
			}
			if(preg_match('/_count^/i',$col['Filed'])){
				$name = preg_replace('/([\w_]+)_count/i', "${1}", $col['Filed']);
				$this->relations[$className][$name . 's'] = "array(self::HAS_MANY, '" . $this->generateClassName($name) . "', '" . $col['Filed'] . "')";
			}
		}
		return $this->relations[$className];
	}

	protected function removePrefix($tableName,$addBrackets=true)
	{
		if($addBrackets && Yii::app()->db->tablePrefix=='')
			return $tableName;
		$prefix=$this->tablePrefix!='' ? $this->tablePrefix : Yii::app()->db->tablePrefix;
		if($prefix!='')
		{
			if($addBrackets && Yii::app()->db->tablePrefix!='')
			{
				$prefix=Yii::app()->db->tablePrefix;
				$lb='{{';
				$rb='}}';
			}
			else
				$lb=$rb='';
			if(($pos=strrpos($tableName,'.'))!==false)
			{
				$schema=substr($tableName,0,$pos);
				$name=substr($tableName,$pos+1);
				if(strpos($name,$prefix)===0)
					return $schema.'.'.$lb.substr($name,strlen($prefix)).$rb;
			}
			else if(strpos($tableName,$prefix)===0)
				return $lb.substr($tableName,strlen($prefix)).$rb;
		}
		return $tableName;
	}

	protected function generateRelations()
	{
		$relations=array();
		if($this->buildRelations)
		{
			$relations=$this->generateRelations();
		}else{
			foreach($this->getColumns as $key=>$column){
				if($column->name!='parent_id' && preg_match('/_id$/i', $key)){
					$name = preg_replace('/^([a-z_]+)_id$/i', '${1}', $key);
					$className=$this->generateClassName($name);
									// Add relation for this table
					//$relationName=$this->generateRelationName($tableName, $fkName, false);
					$relations[$name][$relationName]="array(self::BELONGS_TO, '$className', '$key')";
				}
				elseif(preg_match('/_count$/i', $key) && $key != 'record_count'){
					if($this->modelType != 'category'){
						$relations[$name . 's'][$relationName]="array(self::HAS_MANY, '$className', '$key')";								
					}
				}
			}
		}
		return $relations;
	}

	public function generateInputField($modelClass,$column)
	{
		if($column->type==='boolean')
			return "CHtml::activeCheckBox(\$model,'{$column->name}')";
		else if(stripos($column->dbType,'text')!==false)
			return "CHtml::activeTextArea(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50))";
		else if(stripos($column->dbType,'text')!==false)
			return "CHtml::activeTextArea(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50))";
		else if(stripos($column->dbType,'text')!==false)
			return "CHtml::activeTextArea(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50))";
		else
		{
			if(preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
				$inputField='activePasswordField';
			elseif(preg_match('/(_img|photo|_file)$/i',$column->name)){
				$inputField='activeFileField';
			 }else{
				$inputField='activeTextField';
			}

			if($column->type!=='string' || $column->size===null)
				return "CHtml::{$inputField}(\$model,'{$column->name}')";
			else
			{
				if(($size=$maxLength=$column->size)>60)
					$size=60;
				return "CHtml::{$inputField}(\$model,'{$column->name}',array('size'=>$size,'maxlength'=>$maxLength))";
			}
		}
	}

	public function generateActiveField($modelClass,$column,$types)
	{
		if($column->type==='boolean')
			return "\$form->checkBox(\$model,'{$column->name}')";
		elseif(stripos($column->dbType,'text')!==false)
			return "\$form->textArea(\$model,'{$column->name}',array('class'=>'ckeditor', 'id'=>'editor1', 'rows'=>10, 'cols'=>80))";
		elseif(stripos($column->dbType,'time')!==false || stripos($column->dbType,'data')!==false || $types[$column->name] == 'time')
			return "\$form->textField(\$model,'{$column->name}',array('id'=>'{$column->name}', 'readonly'=>'readonly', 'onclick'=>\"return showCalendar('{$column->name}','%Y-%m-%d',false,false,'{$column->name}');\"))";
		elseif($types[$column->name] == 'pic')
			return "\$form->fileField(\$model,'{$column->name}',array('onclick'=>\"return uploadImage('{$column->name}'), 'class'=>''))";
		elseif($types[$column->name] == 'desc')
			return "\$form->textArea(\$model,'{$column->name}',array( 'rows'=>10, 'cols'=>80, 'class'=>'desc'))";
		elseif($types[$column->name] == 'select'){
			$varName = preg_match('/^^(.+)_id$/i',$column->name) ? '$' . lcfirst($this->getClassNameByVar($column->name)) . 's' : 'array()';
			return "\$form->dropDownList(\$model,'{$column->name}',{$varName})";
		}elseif($types[$column->name] == 'cat_select'){
			if($column->name == 'parent_id')
			{
				$varName = '$' . lcfirst($this->modelClass) . 's';
			}elseif(preg_match('/^^(.+)_id$/i',$column->name)){
				$varName = '$' . lcfirst($this->getClassNameByVar($column->name)) . 's';
			}else{
				$varName = "array()";
			}
			return "\$form->dropDownList(\$model,'{$column->name}',{$varName})";
		}elseif($types[$column->name] == 'region'){
			$varName = preg_match('/^^(.+)_id$/i',$column->name) ? '$' . lcfirst($this->getClassNameByVar($column->name)) . 's' : 'array()';
			return "\$form->dropDownList(\$model,'{$column->name}',{$varName})";
		}elseif($types[$column->name] == 'radio'){
			$varName = preg_match('/^^(.+)_id$/i',$column->name) ? '$' . lcfirst($this->getClassNameByVar($column->name)) . 's' : 'array()';
			return "\$form->radioButtonList(\$model,'{$column->name}',{$varName})";
		}elseif($types[$column->name] == 'checkbox'){
			$varName = preg_match('/^^(.+)_id$/i',$column->name) ? '$' . lcfirst($this->getClassNameByVar($column->name)) . 's' : 'array()';
			return "\$form->checkBoxList(\$model,'{$column->name}',{$varName})";
		}elseif($types[$column->name] == 'file')
			return "\$form->fileField(\$model,'{$column->name}',array('onclick'=>'checkFile()'))";
		else if(stripos($column->dbType,'enum')!==false)
		{
			preg_match_all('/\'([\w_]+)\'/i', $column->dbType, $matchesEnum);
			$data = 'array(';
			foreach($matchesEnum[1] as $val)
			{
				$data .= "'$val'=>'" . $val . "',";
			}
			$data .= ")";

			return "\$form->radioButtonList(\$model,'{$column->name}', $data)";
		}
		else
		{
			if(preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
			{
				$inputField='passwordField';
			}
			else
				$inputField='textField';
						
			if($column->size >= 255)
			{
				return "\$form->textArea(\$model,'{$column->name}')";
			}
			elseif($column->type!=='string' || $column->size===null)
				return "\$form->{$inputField}(\$model,'{$column->name}')";
			else
			{
				if(($size=$maxLength=$column->size)>60) $size=60;
				return "\$form->{$inputField}(\$model,'{$column->name}',array('size'=>$size,'maxlength'=>$maxLength))";
			}
		}
	}
	/**
	 * Get table schema columns
	*/
	public function getColumns($tableName)
	{
		$tableName = strpos($tableName, Yii::app()->db->tablePrefix) ===false ? Yii::app()->db->tablePrefix . $tableName : $tableName;
		$sql="SHOW FULL COLUMNS FROM `{$tableName}`;";
		$columns =array();
		try
		{
				$rows= Yii::app()->db->createCommand($sql)->queryAll();
				foreach($rows as $row)
				{
					$columns[$row['Field']]=$row;
				}
		}
		catch(Exception $e)
		{
				return false;
		}
		return $columns;
	}
	/**
	 * Get table schema columns
	*/
	public function getClassNameByVar($varName)
	{
		$className = '';
		$arr = explode('_', str_replace('_id', '', $varName));
		foreach($arr as $str){
			$className .= ucfirst($str);
		}
		return $className;
	}
}