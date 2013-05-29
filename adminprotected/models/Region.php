<?php

/**
 * This is the model class for table "{{region}}".
 *
 * The followings are the available columns in table '{{region}}':
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
class Region extends CActiveRecord
{
		public $level = 0;	   // 当前分类的等级
		public $selectName = ''; // 下拉选择时有层级展示的名称,如 --分类1
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Region the static model class
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
		return '{{region}}';
	}

	/**
	 * @return array validation rules for model attributes.
	*/
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, parent_id, type, status', 'required'),
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
			'id' => 'ID',
			'name' => '名称',
			'parent_id' => '上级区域',
			'desc' => '分类描述',
			'sequence' => '排序序号',
			'amount' => '记录总数',
			'type' => '区域类型',
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
		$criteria->order = 'id DESC';
		/*
		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name);
		if(isset($_POST['search_fields']) && isset($_POST['keywords']) && !empty($_POST['keywords']))
		{
			$criteria->compare($_POST['search_fields'],$_POST['keywords'],true);
		}
		 */
		return new CArrayDataProvider(self::model()->getAllChildren($criteria,$this->parent_id),array(
			'id'=>'id',
			'pagination'=>array(
				'pageSize'=>10,
			)
		));
	}
	/*
	 * 所有子类的id组成的数组
	 */
	public function getAllChildrenIds($parent_id=0){
		return CCategory::getAllChildrenIds(self::model()->findAll(),$parent_id);
	}
	/*
	 * 所有子类组成的数组
	 */
	public function getAllChildren($criteria=array(), $parent_id=0){
		return CCategory::getAllChildren(self::model()->findAll($criteria),$parent_id);
	}
	/**
	 * 分类目录下拉列表
	 */	   
	public function showAllDroplist($criteria=array(), $parent_id=0,$level=0,$selectText='')
	{
		return CCategory::showAllDroplist(self::model()->findAll($criteria),$parent_id, $level,$selectText);
	}
}