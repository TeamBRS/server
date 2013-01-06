<?php
require_once(dirname(__FILE__)."/../lib/OAuth.php");
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
	public $businesscuisine=array();
	public $queryhistory=array();

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('location', 'required'),
			array('location', 'checkLoc'),
			array('minrating','required'),
			array('cuisine','required'),
			array('venue','required'),
			array('socialfeeds', 'required'),
		);
	}
	
	public function checkLoc($attribute,$params) {
		if($this->location=='') {
			$this->addError('location','No location specified');
		}
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
			
			$this->CommitDB(2, array($child->BusinessName, $child->BusinessType, $child->AddressLine1, $child->RatingValue,  $this->GetYelpData($lat, $long, $child->BusinessName), $lat, $long
));

		}
		
	}
	
	public function GetYelpData($lat, $long, $bname) {
		
		$consumer_key = "U9oEb5Wb917RLdeHip-7bA";
		$consumer_secret = "IiBJE1TehegxK3Dh7NVtz5VR9_s";
		$token = "p_PNhBC0Xp9aNq-glTxMp_hWZuqR6mMS";
		$token_secret = "ZSjU3YERnlzGWGA_XDjpWFzEwkM";
		$ywsid="A-rID23Te9CBi-DuAZ6nQQ";

		// Token object built using the OAuth library
		$token = new OAuthToken($token, $token_secret);

		// Consumer object built using the OAuth library
		$consumer = new OAuthConsumer($consumer_key, $consumer_secret);

		// Yelp uses HMAC SHA1 encoding
		$signature_method = new OAuthSignatureMethod_HMAC_SHA1();
		
		$general_url = "http://api.yelp.com/v2/search?term=".$bname."&ll=".$lat.",".$long*10;
		
		$oauthrequest = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $general_url);
		$oauthrequest->sign_request($signature_method, $consumer, $token);
		$signed_url = $oauthrequest->to_url();

		// Send Yelp API Call
		$ch = curl_init($signed_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$data = curl_exec($ch); // Yelp response
		curl_close($ch);

		// Handle Yelp response data
		$response = json_decode($data);
		$response = $response->businesses;
		$category= "";
		
		foreach($response as $restaur) {
	    	if(array_key_exists('categories',$restaur)) {
	    		$category = $restaur->categories[0][0];
	    		$this->businesscuisine[] = $category;
	    	}
		}
	
		return $category;	
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
		
			$loc = explode(",", $this->location);
		
			$sql1="INSERT INTO tbl_query_results (user_id, business_name, business_cuisine, business_address, business_type, business_rating, longtitude, latitude) VALUES ";
			$sql2="('".$user_id."','".$arr[0]."','".$arr[4]."','".$arr[2]."','".$arr[1]."','".$arr[3]."','".$arr[5]."','".$arr[6]."');";
			
			$conn=Yii::app()->db;
			$comm=$conn->createCommand($sql1.$sql2);

			$rowCount=$comm->execute();
				
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
