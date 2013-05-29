<?php
Yii::import('system.gii.CCodeModel');

class ModelCode extends CCodeModel
{
	public $tablePrefix;
	public $tableName;
	public $modelClass;
	public $modelPath='application.models';
	public $baseClass='CActiveRecord';
	public $buildRelations=true;

	public $controller;
	public $baseControllerClass='Controller';

	private $_modelClass;
	private $_table;

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
			array('tableName, modelPath, modelType, setting, baseClass,controller,baseControllerClass', 'required'),
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
		return array_merge(parent::attributeLabels(), array(
			'tablePrefix'=>'Table Prefix',
			'tableName'=>'Table Name',
			'modelPath'=>'Model Path',
			'modelClass'=>'Model Class',
			'baseClass'=>'Base Class',
			'buildRelations'=>'Build Relations',
			'modelPath'=>'Model Path',
			'modelClass'=>'Model Class',
			'baseClass'=>'Base Class',
		));
	}

	public function requiredTemplates()
	{
		return array(
			'model.php',
		);
	}

	public function init()
	{
		if(Yii::app()->db===null)
			throw new CHttpException(500,'An active "db" connection is required to run this generator.');
		$this->tablePrefix=Yii::app()->db->tablePrefix;
		parent::init();
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

	public function validateTableName($attribute,$params)
	{
		$invalidTables=array();
		$invalidColumns=array();

		if($this->tableName[strlen($this->tableName)-1]==='*')
		{
			if(($pos=strrpos($this->tableName,'.'))!==false)
				$schema=substr($this->tableName,0,$pos);
			else
				$schema='';

			$this->modelClass='';
			$tables=Yii::app()->db->schema->getTables($schema);
			foreach($tables as $table)
			{
				if($this->tablePrefix=='' || strpos($table->name,$this->tablePrefix)===0)
				{
					if(in_array(strtolower($table->name),self::$keywords))
						$invalidTables[]=$table->name;
					if(($invalidColumn=$this->checkColumns($table))!==null)
						$invalidColumns[]=$invalidColumn;
				}
			}
		}
		else
		{
			if(($table=$this->getTableSchema1($this->tableName))===null)
				$this->addError('tableName',"Table '{$this->tableName}' does not exist.");
			if($this->modelClass==='')
				$this->addError('modelClass','Model Class cannot be blank.');

			if(!$this->hasErrors($attribute) && ($invalidColumn=$this->checkColumns($table))!==null)
					$invalidColumns[]=$invalidColumn;
		}

		if($invalidTables!=array())
			$this->addError('tableName', 'Model class cannot take a reserved PHP keyword! Table name: '.implode(', ', $invalidTables).".");
		if($invalidColumns!=array())
			$this->addError('tableName', 'Column names that does not follow PHP variable naming convention: '.implode(', ', $invalidColumns).".");

		$table=$this->getTableSchema1($this->tableName);
		$this->_modelClass = $this->modelClass;
		$this->_table = $table;
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

	public function getTableSchema()
	{
		return $this->_table;
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
			foreach(Yii::app()->db->schema->getTables() as $table)
			{
				if($this->tablePrefix!='' && strpos($table->name,$this->tablePrefix)!==0)
						continue;
				$tableName=$table->name;

				if ($this->isRelationTable($table))
				{
					$pks=$table->primaryKey;
					$fks=$table->foreignKeys;

					$table0=$fks[$pks[0]][0];
					$table1=$fks[$pks[1]][0];
					$className0=$this->generateClassName($table0);
					$className1=$this->generateClassName($table1);

					$unprefixedTableName=$this->removePrefix($tableName);

					$relationName=$this->generateRelationName($table0, $table1, true);
					$relations[$className0][$relationName]="array(self::MANY_MANY, '$className1', '$unprefixedTableName($pks[0], $pks[1])')";

					$relationName=$this->generateRelationName($table1, $table0, true);
					$relations[$className1][$relationName]="array(self::MANY_MANY, '$className0', '$unprefixedTableName($pks[1], $pks[0])')";
				}
				else
				{
					$className=$this->generateClassName($tableName);
					foreach ($table->foreignKeys as $fkName => $fkEntry)
					{
						// Put table and key name in variables for easier reading
						$refTable=$fkEntry[0]; // Table name that current fk references to
						$refKey=$fkEntry[1];   // Key in that table being referenced
						$refClassName=$this->generateClassName($refTable);

						// Add relation for this table
						$relationName=$this->generateRelationName($tableName, $fkName, false);
						$relations[$className][$relationName]="array(self::BELONGS_TO, '$refClassName', '$fkName')";

						// Add relation for the referenced table
						$relationType=$table->primaryKey === $fkName ? 'HAS_ONE' : 'HAS_MANY';
						$relationName=$this->generateRelationName($refTable, $this->removePrefix($tableName,false), $relationType==='HAS_MANY');
						$i=1;
						$rawName=$relationName;
						while(isset($relations[$refClassName][$relationName]))
								$relationName=$rawName.($i++);
						$relations[$refClassName][$relationName]="array(self::$relationType, '$className', '$fkName')";
					}
				}
			}
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

	/**
	 * Checks if the given table is a "many to many" pivot table.
	 * Their PK has 2 fields, and both of those fields are also FK to other separate tables.
	 * @param CDbTableSchema table to inspect
	 * @return boolean true if table matches description of helpter table.
	 */
	protected function isRelationTable($table)
	{
		$pk=$table->primaryKey;
		return (count($pk) === 2 // we want 2 columns
			&& isset($table->foreignKeys[$pk[0]]) // pk column 1 is also a foreign key
			&& isset($table->foreignKeys[$pk[1]]) // pk column 2 is also a foriegn key
			&& $table->foreignKeys[$pk[0]][0] !== $table->foreignKeys[$pk[1]][0]); // and the foreign keys point different tables
	}

	protected function generateClassName($tableName)
	{
		if($this->tableName===$tableName || ($pos=strrpos($this->tableName,'.'))!==false && substr($this->tableName,$pos+1)===$tableName)
			return $this->modelClass;

		$tableName=$this->removePrefix($tableName,false);
		$className='';
		foreach(explode('_',$tableName) as $name)
		{
			if($name!=='')
				$className.=ucfirst($name);
		}
		return $className;
	}

	/**
	 * Generate a name for use as a relation name (inside relations() function in a model).
	 * @param string the name of the table to hold the relation
	 * @param string the foreign key name
	 * @param boolean whether the relation would contain multiple objects
	 * @return string the relation name
	 */
	protected function generateRelationName($tableName, $fkName, $multiple)
	{
		if(strcasecmp(substr($fkName,-2),'id')===0 && strcasecmp($fkName,'id'))
			$relationName=rtrim(substr($fkName, 0, -2),'_');
		else
			$relationName=$fkName;
		$relationName[0]=strtolower($relationName);

		if($multiple)
			$relationName=$this->pluralize($relationName);

		$names=preg_split('/_+/',$relationName,-1,PREG_SPLIT_NO_EMPTY);
		if(empty($names)) return $relationName;  // unlikely
		for($name=$names[0], $i=1;$i<count($names);++$i)
			$name.=ucfirst($names[$i]);

		$rawName=$name;
		$table=Yii::app()->db->schema->getTable($tableName);
		$i=0;
		while(isset($table->columns[$name]))
			$name=$rawName.($i++);

		return $name;
	}

	
	public function getModelClass()
	{
		return $this->_modelClass;
	}

	public function getControllerClass()
	{
		if(($pos=strrpos($this->controller,'/'))!==false)
			return ucfirst(substr($this->controller,$pos+1)).'Controller';
		else
			return ucfirst($this->controller).'Controller';
	}

	public function getModule()
	{
		if(($pos=strpos($this->controller,'/'))!==false)
		{
			$id=substr($this->controller,0,$pos);
			if(($module=Yii::app()->getModule($id))!==null)
				return $module;
		}
		return Yii::app();
	}

	public function getControllerID()
	{
		if($this->getModule()!==Yii::app())
			$id=substr($this->controller,strpos($this->controller,'/')+1);
		else
			$id=$this->controller;
		if(($pos=strrpos($id,'/'))!==false)
			$id[$pos+1]=strtolower($id[$pos+1]);
		else
			$id[0]=strtolower($id[0]);
		return $id;
	}

	public function getUniqueControllerID()
	{
		$id=$this->controller;
		if(($pos=strrpos($id,'/'))!==false)
			$id[$pos+1]=strtolower($id[$pos+1]);
		else
			$id[0]=strtolower($id[0]);
		return $id;
	}

	public function getControllerFile()
	{
		$module=$this->getModule();
		$id=$this->getControllerID();
		if(($pos=strrpos($id,'/'))!==false)
			$id[$pos+1]=strtoupper($id[$pos+1]);
		else
			$id[0]=strtoupper($id[0]);
		return $module->getControllerPath().'/'.$id.'Controller.php';
	}

	public function getViewPath()
	{
		return $this->getModule()->getViewPath().'/'.$this->getControllerID();
	}

	public function generateInputLabel($modelClass,$column)
	{
		return "CHtml::activeLabelEx(\$model,'{$column->name}')";
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

	public function generateActiveLabel($modelClass,$column)
	{
		return "\$form->labelEx(\$model,'{$column->name}')";
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

	public function guessNameColumn($columns)
	{
		foreach($columns as $column)
		{
			if(!strcasecmp($column->name,'name'))
				return $column->name;
		}
		foreach($columns as $column)
		{
			if(!strcasecmp($column->name,'title'))
				return $column->name;
		}
		foreach($columns as $column)
		{
			if($column->isPrimaryKey)
				return $column->name;
		}
		return 'id';
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