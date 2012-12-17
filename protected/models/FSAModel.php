<?php

/**
 * FSAForm class.
 */
class FSAModel extends CFormModel
{
	public $location;
	public $minrating;
	public $cuisine;
	public $venue;
	public $socialfeeds;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('location', 'required'),
			// rememberMe needs to be a boolean
			array('socialfeeds', 'boolean'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'location' => 'Location',
			'minrating' => 'Minimum Rating',
			'cuisine' => 'Cuisine',
			'venue' => 'Venue',
			'socialfeeds' => 'Social Feeds',
		);
	}
	
}
