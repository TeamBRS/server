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
	public $queryhistory=array();

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('location', 'safe'),
			array('minrating','safe'),
			array('cuisine','safe'),
			array('venue','safe'),
			array('socialfeeds', 'safe'),
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
	
	public function ReverseGeocode($location, $venue) 
	{
	
		$latlong = explode(", ", $location);
		$querystring = "http://maps.googleapis.com/maps/api/geocode/xml?latlng=".$latlong[0].",".$latlong[1]."&sensor=true";

		//Get xml data using CURL
		
		$xml = simplexml_load_file($querystring);
		
		//Get FSA Listing
		
		switch($venue) {
		
			case 0: $fsastring = "http://ratings.food.gov.uk/enhanced-search/en-GB/^/^/DISTANCE/1/^/".$latlong[1]."/".$latlong[0]."/1/10/xml"; break;
			case 1: $fsastring = "http://ratings.food.gov.uk/enhanced-search/en-GB/^/^/DISTANCE/8/^/".$latlong[1]."/".$latlong[0]."/1/10/xml"; break;
			case 2: $fsastring = "http://ratings.food.gov.uk/enhanced-search/en-GB/^/^/DISTANCE/1/^/".$latlong[1]."/".$latlong[0]."/1/10/xml"; break;
			case 3: $fsastring = "http://ratings.food.gov.uk/enhanced-search/en-GB/^/^/DISTANCE/1/^/".$latlong[1]."/".$latlong[0]."/1/10/xml"; break;
			case 4: $fsastring = "http://ratings.food.gov.uk/enhanced-search/en-GB/^/^/DISTANCE/1/^/".$latlong[1]."/".$latlong[0]."/1/10/xml"; break;
			case 5: $fsastring = "http://ratings.food.gov.uk/enhanced-search/en-GB/^/^/DISTANCE/10/^/".$latlong[1]."/".$latlong[0]."/1/10/xml"; break;
			default: $fsastring = "null";break;
		}
		
		$xmlestab = simplexml_load_file($fsastring);
		
		//Construct FSA Listings
	
		$xmlrest = $xmlestab->EstablishmentCollection;
				
		foreach($xmlrest->EstablishmentDetail as $child)
		{
			//have to remove exponent and change to gmap format
			$latArr = explode('e', $child->Geocode->Latitude);
			$longArr = explode('e', $child->Geocode->Longitude);
						
			//convert string coords to longs
						
			$lat = 10*$latArr[0];
			$long = $longArr[0]/10;
			$latArr = array();
			$longArr = array();
		
			//gather rest of establishment details
			$this->locmarkers[] = $lat . "," . $long;
			$this->businessname[] = $child->BusinessName;
			$this->businesstype[] = $child->BusinessType;
			$this->businessaddr1[] = $child->AddressLine1;
			$this->businessrating[] = $child->RatingValue;
			
			$this->CommitDB(2, array($child->BusinessName, $child->BusinessType, $child->AddressLine1, $child->RatingValue));

		}
		
	}
	
	public function RefineResults() 
	{
		//Temporary while we are not storing in db.
	
		$min = (int) $this->minrating;
		
		foreach ($this->businessrating as $key => $value) {
    		if ($value < $min) {
        		unset($this->businessrating[$key]);
        		$this->businessrating = array_values($this->businessrating);
			}
		}
		
		echo count($this->businessrating);
	
		for($i=0; $i < count($this->businessrating); $i++) {
			//refine according to each search criteria
			$br = (int) $this->businessrating[$i];
				if($br < $min) {
					//remove record
					unset($this->locmarkers[$i]);
					unset($this->businessname[$i]);
					unset($this->businesstype[$i]);
					unset($this->businessaddr1[$i]);
					unset($this->businessrating[$i]);
					
					//normalize array indices
					$this->locmarkers = array_values($this->locmarkers);
					$this->businessname = array_values($this->businessname);
					$this->businesstype = array_values($this->businesstype);
					$this->businessaddr1 = array_values($this->businessaddr1);
					$this->businessrating = array_values($this->businessrating);
				}
											
		}
		$this->CommitDB(1, null);
	
	}
	
	public function CommitDB($mode, $arr) {
		
		//get current user id, this state should only occur when a user has logged in.
		$user_id = Yii::app()->user->id;
	
		if($mode==1) {

			//fix location delimiter 
			$loc = explode(",", $this->location);
			$loc = implode("@", $loc);
			
			//define sql query for committing information
			$sql1="INSERT INTO tbl_query_history (userid, timestamp, location, minrating, cuisinepref, socialpref) VALUES ";
			$sql2="('".$user_id."','".date("Y-m-d H:i:s")."','".$loc."','".$this->minrating."','".$this->cuisine."','".$this->socialfeeds."');";
		
			$conn=Yii::app()->db;
			$comm=$conn->createCommand($sql1.$sql2);
			$rowCount=$comm->execute();
		
		} else if($mode==2) {
		
			$sql1="INSERT INTO tbl_query_results (user_id, business_name, business_cuisine, business_address, business_type, business_rating) VALUES ";
			$sql2="('".$user_id."','".$arr[0]."', default,'".$arr[2]."','".$arr[1]."','".$arr[3]."');";
			
			$conn=Yii::app()->db;
			$comm=$conn->createCommand($sql1.$sql2);
			
			try {
				$rowCount=$comm->execute();
			} catch (Exception $e) {
			  //do nothing
			}
				
		}
		
	}
	
	public function GetHistory() {
	
		$user_id = Yii::app()->user->id;
		$sql = "SELECT * FROM tbl_query_results WHERE user_id='".$user_id."';";
		
		$conn=Yii::app()->db;
		$comm=$conn->createCommand($sql);
		$this->queryhistory = $comm->queryAll();

    	return $this->queryhistory;
	}
	
}
