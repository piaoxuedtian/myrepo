<?php
/**
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 */
?>
<?php echo "<?php\n"; ?>

/**
 * This is the model class for table "<?php echo $tableName; ?>".
 *
 * The followings are the available columns in table '<?php echo $tableName; ?>':
<?php foreach($columns as $column): ?>
 * @property <?php echo $column->type.' $'.$column->name."\n"; ?>
<?php endforeach; ?>
<?php if(!empty($relations)): ?>
 *
 * The followings are the available model relations:
<?php foreach($relations as $name=>$relation): ?>
 * @property <?php
	if (preg_match("~^array\(self::([^,]+), '([^']+)', '([^']+)'\)$~", $relation, $matches))
	{
		$relationType = $matches[1];
		$relationModel = $matches[2];

		switch($relationType){
			case 'HAS_ONE':
				echo $relationModel.' $'.$name."\n";
			break;
			case 'BELONGS_TO':
				echo $relationModel.' $'.$name."\n";
			break;
			case 'HAS_MANY':
				echo $relationModel.'[] $'.$name."\n";
			break;
			case 'MANY_MANY':
				echo $relationModel.'[] $'.$name."\n";
			break;
			default:
				echo 'mixed $'.$name."\n";
		}
	}
	?>
<?php endforeach; ?>
<?php endif; ?>
*/
class <?php echo $modelClass; ?> extends CActiveRecord
{
<?php if($modelType == 'category'){ ?>
		public $level = 0; // 当前分类的等级
		public $selectName = ''; // 下拉选择时有层级展示的名称,如 --分类1
		public static $_kvp=array(); //键值对数组(id为键,name为值)
<?php } ?>
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return <?php echo $modelClass; ?> the static model class
	*/
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	*/
	public function tableName()
	{
		return '<?php echo $tableName; ?>';
	}

	/**
	 * @return array validation rules for model attributes.
	*/
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
<?php foreach($rules as $rule): ?>
			<?php echo $rule.",\n"; ?>
<?php endforeach; ?>
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('<?php echo implode(', ', array_keys($columns)); ?>', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	*/
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
<?php foreach($relations as $name=>$relation): ?>
			<?php echo "'$name' => $relation,\n"; ?>
<?php endforeach; ?>
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	*/
	public function attributeLabels()
	{
		return array(
<?php foreach($labels as $name=>$label): ?>
			<?php echo "'$name' => '$label',\n"; ?>
<?php endforeach; ?>
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	*/
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		//$criteria->order = 'id DESC';

<?php
foreach($setting['type'] as $name=>$type)
{
	if(in_array($name, $setting['list_search']))
	{
		if($name=='parent_id'){
			echo "\t\tif(!empty(\$this->parent_id))\$criteria->addInCondition('id',\$this->getAllChildrenIds(\$this->parent_id));\n";
		}elseif($type=='string'){
			echo "\t\t\$criteria->compare('$name',\$this->$name,true);\n";
		}elseif($type=='time'){
			echo "\t\t\if(isset(\$_GET['{$name}_start']))\$criteria->addCondition('{$name}>' . \$_GET['{$name}_start']);\n";
			echo "\t\t\if(isset(\$_GET['{$name}_end']))\$criteria->addCondition('{$name}<' . \$_GET['{$name}_end']);\n";
		}elseif($type=='select' || $type=='radio' || $type=='checkbox'){
			echo "\t\t\$criteria->compare('$name',\$this->$name);\n";
		}elseif($type=='cat_select' || $type=='region'){
			echo "\t\t\if(isset(\$_GET['{$name}_start']))\$criteria->addInCondition('{$name}' . \${$name}s);\n";
		}else{
			echo "\t\t\$criteria->compare('$name',\$this->$name);\n";
		}
	}
}
?>

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
<?php if($modelType == 'category'){ ?>

	/**
	 * 树形列表展示
	 * Retrieves a tree list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	*/
	public function treeList()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$dataProvider=$this->search();
		$dataProvider->setData(CCategory::getAllChildren($dataProvider->getData()));
		return $dataProvider;
	}
	/**
	 * 获取所有记录列表，以分类id为键名，以名称为键值的数组
	 * @author mateng
	 * @return array
	 */
	public static function getKeyValuePair()
	{
		if(self::$_kvp === null)
			self::$_kvp = Yii::app()->cache->get('<?php echo lcfirst($modelClass); ?>_kvp');
		if(empty(self::$_kvp)){
			self::$_kvp=array();
			foreach(self::model()->findAll() as $row){
				self::$_kvp[]=$row->attributes;
			}
			Yii::app()->cache->set('<?php echo lcfirst($modelClass); ?>_kvp',self::$_kvp,24*3600);
		}
		return self::$_kvp;
	}
	/**
	 * 获取所有记录，以分类id为键名，以名称为键值的数组
	 * @author mateng
	 * @return array
	 */
	public static function getNameById($id)
	{
		$name='';
		$list = self::getKeyValuePair();
		if(isset($list[$id]['name']))$name=$list[$id]['name'];
		return $name;
	}
	/*
	 * 所有子类的id组成的数组
	 */
	static public function getAllChildrenIds($parent_id=0){
		return CCategory::getAllChildrenIds(self::model()->findAll(),$parent_id);
	}
	/*
	 * 所有子类组成的数组
	 */
	static public function getAllChildren($criteria=array(), $parent_id=0){
		return CCategory::getAllChildren(self::model()->findAll($criteria),$parent_id);
	}
	/**
	 * 分类目录下拉列表
	 */	   
	static public function showAllDroplist($criteria=array(), $parent_id=0,$level=0,$selectText='')
	{
		return CCategory::showAllDroplist(self::model()->findAll($criteria),$parent_id, $level,$selectText);
	}
<?php }
foreach($setting['type'] as $name=>$type)
{
	if($type=='radio'||$type=='checkbox')
	{
?>
	static public function all<?php echo ucfirst($this->getClassNameByVar($name)); ?>(){
		return array(
			1=>1,
			2=>2,
		);
	}
<?php }} ?>
}