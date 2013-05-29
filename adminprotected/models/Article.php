<?php

/**
 * This is the model class for table "{{article}}".
 *
 * The followings are the available columns in table '{{article}}':
 * @property string $id
 * @property string $title
 * @property string $content
 * @property string $author
 * @property string $add_time
 * @property string $last_update
 * @property string $file
 * @property integer $category_id
*/
class Article extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Article the static model class
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
		return '{{article}}';
	}

	/**
	 * @return array validation rules for model attributes.
	*/
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content, add_time, category_id', 'required'),
			array('category_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>120),
			array('author', 'length', 'max'=>60),
			array('add_time, last_update', 'length', 'max'=>10),
			array('file', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, content, author, add_time, last_update, file, category_id', 'safe', 'on'=>'search'),
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
			'category'=>array(
				self::BELONGS_TO,'Category','category_id'
			),
			'author'=>array(
				self::BELONGS_TO,'User','author_id'
			),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	*/
	public function attributeLabels()
	{
		return array(
			'id' => '自增id',
			'title' => '标题',
			'content' => '内容',
			'author_id' => '作者',
			'add_time' => '添加时间',
			'last_update' => '最后更新时间',
			'file' => '附件',
			'category_id' => '分类id',
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
		$criteria->order = ' id DESC';  

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title);
		if(isset($_GET['search_fields']) && isset($_GET['keywords']) && !empty($_GET['keywords']))
		{
			$criteria->compare($_GET['search_fields'],$_GET['keywords'],true);
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}