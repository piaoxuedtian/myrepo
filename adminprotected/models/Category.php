<?php

/**
 * This is the model class for table "{{category}}".
 *
 * The followings are the available columns in table '{{category}}':
 * @property string $id
 * @property string $name
 * @property integer $parent_id
 * @property string $desc
 * @property integer $sequence
 * @property string $amount
 * @property integer $type
 * @property integer $status
 * @property integer $level
*/
class Category extends CActiveRecord
{
		public $level = 0; // 当前分类的等级
		public $selectName = ''; // 下拉选择时有层级展示的名称,如 --分类1
		public static $_kvp=array(); //键值对数组(id为键,name为值)
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Category the static model class
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
		return '{{category}}';
	}

	/**
	 * @return array validation rules for model attributes.
	*/
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, parent_id, type, status, level', 'required'),
			array('parent_id, sequence, type, status, level', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>120),
			array('desc', 'length', 'max'=>255),
			array('amount', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, parent_id, desc, sequence, amount, type, status, level', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	*/
	public function attributeLabels()
	{
		return array(
			'id' => '自增id',
			'name' => '标题',
			'parent_id' => '分类id',
			'desc' => '分类描述',
			'sequence' => '排序序号',
			'amount' => '记录数量',
			'type' => '分类类型',
			'status' => '状态',
			'level' => '等级',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name);
		if(!empty($this->parent_id))$criteria->addInCondition('id',$this->getAllChildrenIds($this->parent_id));

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

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
			self::$_kvp = Yii::app()->cache->get('category_kvp');
		if(empty(self::$_kvp)){
			self::$_kvp=array();
			foreach(self::model()->findAll() as $row){
				self::$_kvp[]=$row->attributes;
			}
			Yii::app()->cache->set('category_kvp',self::$_kvp,24*3600);
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
	static public function allType(){
		return array(
			1=>1,
			2=>2,
		);
	}
	static public function allStatus(){
		return array(
			1=>1,
			2=>2,
		);
	}
}