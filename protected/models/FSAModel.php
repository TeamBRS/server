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
	
	public $locmarkers=array();
	public $businessname=array();
	public $businesstype=array();
	public $businessaddr1=array();
	public $businessrating=array();

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
	
	public function ReverseGeocode($location) 
	{
	
		$latlong = explode(", ", $location);
		$querystring = "http://maps.googleapis.com/maps/api/geocode/xml?latlng=".$latlong[0].",".$latlong[1]."&sensor=true";
		$fsastringr = "http://ratings.food.gov.uk/enhanced-search/en-GB/^/^/DISTANCE/1/^/".$latlong[1]."/".$latlong[0]."/1/5/xml";
		$fsastringp = "http://ratings.food.gov.uk/enhanced-search/en-GB/^/^/DISTANCE/8/^/".$latlong[1]."/".$latlong[0]."/1/5/xml";
		$fsastringt = "http://ratings.food.gov.uk/enhanced-search/en-GB/^/^/DISTANCE/10/^/".$latlong[1]."/".$latlong[0]."/1/5/xml";
	

		//Get xml data using CURL
		
		$xml = simplexml_load_file($querystring);
		
		$locality = $xml->result->address_component[2]->long_name;
		$adminarea = $xml->result->address_component[3]->long_name;
		
		$xmlstring = "<h3>You are in ".$locality.", ".$adminarea."</h3>";
		
		//Get FSA Listings
		
		$xmlrests = simplexml_load_file($fsastringr);
		$xmlpubs = simplexml_load_file($fsastringp);
		$xmltakes = simplexml_load_file($fsastringt);
		
		//Construct FSA Listings
	
		$xmlrest = $xmlrests->EstablishmentCollection;
		
		foreach($xmlrest->EstablishmentDetail as $child)
		{
			//have to remove exponent and change to gmap format
			$latArr = explode('e', $child->Geocode->Latitude);
			$longArr = explode('e', $child->Geocode->Longitude);
						
			//convert string coords to longs
			$lat = 10*$latArr[0];
			$long = $longArr[0]/10;
		
			//gather rest of establishment details
			$this->locmarkers[] = $lat . "," . $long;
			$this->businessname[] = $child->BusinessName;
			$this->businesstype[] = $child->BusinessType;
			$this->businessaddr1[] = $child->AddressLine1;
			$this->businessrating[] = $child->RatingValue;
							
		}
		
		return $xmlstring;
	}
	
}
