<?php

/**
 * This is the model class for table "tbl_query_facebook_results".
 *
 * The followings are the available columns in table 'tbl_query_facebook_results':
 * @property integer $query_id
 * @property string $page_id
 * @property integer $visitors
 * @property integer $likes
 */
class QueryFacebookResults extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return QueryFacebookResults the static model class
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
		return 'tbl_query_facebook_results';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('query_id', 'required'),
			array('query_id, visitors, likes', 'numerical', 'integerOnly'=>true),
			array('page_id', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('query_id, page_id, visitors, likes', 'safe', 'on'=>'search'),
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
			'query_id' => 'Query',
			'page_id' => 'Page',
			'visitors' => 'Visitors',
			'likes' => 'Likes',
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

		$criteria->compare('query_id',$this->query_id);
		$criteria->compare('page_id',$this->page_id,true);
		$criteria->compare('visitors',$this->visitors);
		$criteria->compare('likes',$this->likes);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}