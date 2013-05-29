<?php

/**
 * This is the model class for table "{{post}}".
 *
 * The followings are the available columns in table '{{post}}':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $code
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $author_id
 * @property integer $votes
 *
 * The followings are the available model relations:
 * @property Comment[] $comments
 * @property User $author
*/
class Post extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Post the static model class
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
		return '{{post}}';
	}

	/**
	 * @return array validation rules for model attributes.
	*/
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content, status, author_id', 'required'),
			array('status, create_time, update_time, author_id, votes', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>128),
			array('code', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, content, code, status, create_time, update_time, author_id, votes', 'safe', 'on'=>'search'),
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
			'comments' => array(self::HAS_MANY, 'Comment', 'post_id'),
			'author' => array(self::BELONGS_TO, 'User', 'author_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	*/
	public function attributeLabels()
	{
		return array(
			'id' => '',
			'title' => '名称',
			'content' => '简介',
			'code' => '视频代码',
			'status' => '状态',
			'create_time' => '创建时间',
			'update_time' => '修改时间',
			'author_id' => '作者id',
			'votes' => '票数',
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

		$criteria->compare('title',$this->title);
		$criteria->compare('content',$this->content);
		$criteria->compare('code',$this->code);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}