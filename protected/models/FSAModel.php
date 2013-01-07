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
		
		//retrieve facebook user using their username
		$fb_user = FacebookUser::model()->find('user_id=:user_id', array(':user_id'=>Yii::app()->user->getId()));	
		
		//only do facebook stuff if this user is actually logged in
		if($fb_user) 
		{
			$fql = " SELECT page_id, name, is_unclaimed, type, checkin_count, latitude, longitude,pic_large FROM place WHERE (distance(latitude, longitude, '52.29189249999999', '-1.5322422') < 25000)  LIMIT 100";
			//pull out data from the facebook opengraph
			$fb_url = "https://graph.facebook.com/fql?q=" .urlencode($fql) ."&access_token=" .$fb_user->auth_key;
			
			$fb_places = json_decode(file_get_contents($fb_url),false, 512, JSON_BIGINT_AS_STRING);
			
			//create a list of place ids - for use in querying likes
			$place_ids = array();
		}
		
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
			
			$isds = $child->query_id;
			
			//match up with facebook info (again, only if the user is logged in)
			if($fb_user) {
				
				//shortest has not been found yet
				$shortest = -1;

				//loop through each business name to find the closest match
				foreach($fb_places->data as $fb_place) {
					
					//neasure the distance between the two words
					$lev = levenshtein($child->BusinessName, $fb_place->name);
					
					//we have found an exact match, good!
					if($lev == 0) {
						$closest = $fb_place;
						$shortest = 0;
						break;
					}
					
					// if this distance is less than the next found shortest
					// distance, OR if a next shortest word has not yet been found
					if ($lev <= $shortest || $shortest < 0) {
						// set the closest match, and shortest distance
						$closest  = $fb_place;
						$shortest = $lev;
					}					
					
				}
				
				//we've found it - now save it to the database - but first check for duplicates (this becomes NULL)
				$existing_place = FacebookPlace::model()->find('page_id=:page_id', array(':page_id'=>$closest->page_id));

				if(!$existing_place) {
					$new_place = new FacebookPlace;
					
					$new_place->page_id = strval($closest->page_id); //dirty string hack
					$new_place->pic_large = $closest->pic_large;
					$new_place->type = $closest->type;
					$new_place->name = $closest->name;
					$new_place->is_unclaimed = $closest->is_unclaimed;
					$new_place->latitude = $closest->longitude;
					$new_place->longitude = $closest->latitude;
					
					$new_place->insert();
				}
				
				//add placeID to the list
				$place_ids[] = strval($closest->page_id);
				
			}
			
			$this->CommitDB(2, array($child->BusinessName, $child->BusinessType, $child->AddressLine1, $child->RatingValue,  $this->GetYelpData($lat, $long, $child->BusinessName), $lat, $long
));

		}
		
		//turn our array of placeIDs into a comma separated list
		$place_id_list = implode(",", $place_ids);
		
		//query the open graph for the places we have just discovered
		$fql = "SELECT tagged_uids, checkin_id, page_id FROM checkin WHERE  checkin_id IN (SELECT checkin_id  FROM checkin WHERE page_id IN (" .$place_id_list ."))";
		$fb_url = "https://graph.facebook.com/fql?q=" .urlencode($fql) ."&access_token=" .$fb_user->auth_key;
		$fb_checkins = json_decode(file_get_contents($fb_url),false, 512, JSON_BIGINT_AS_STRING);
		
		//the number of checkins by friends
		$no_checkins = count($fb_checkins->data);
		$no_people = 0;
		
		//iterate over checkins and add them into the database
		foreach ($fb_checkins->data as $fb_checkin) {
			//count people
			foreach($fb_checkin->tagged_uids as $tu) {
				$no_people += count($tu);
			}
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
		$cuisine = $this->cuisine;	
		
		//Cuisine preference filtering
		
		for($i=0;$i<count($this->businesscuisine);$i++) {
			
			switch($cuisine) {
			
				case 1: 
					if ($this->businesscuisine[$i]!="English") {
						
						$this->UnsetElements($i);
					} else {
						$this->cuisine="English";						
					}
					break;
				case 2: 
					if ($this->businesscuisine[$i]!="Italian") {
						$this->cuisine="Italian";
						$this->UnsetElements($i);
					} else {
						
					}
					break;
				case 3:					
					if ($this->businesscuisine[$i]!="Chinese") {
						$this->cuisine="Chinese";
						$this->UnsetElements($i);
					} else {
						
					}
					break; 
				case 4: 
					if ($this->businesscuisine[$i]!="Coffee & Tea") {
						$this->cuisine="Coffee & Tea";						
						$this->UnsetElements($i);
					} else {
						
					}
				break;
				case 5:					
					if ($this->businesscuisine[$i]!="Indian") {
						$this->cuisine="Indian";						
						$this->UnsetElements($i);
					} else {
						
					} 
				break;
				case 6:
					if ($this->businesscuisine[$i]!="Fast Food") {
						$this->cuisine="Fast Food";						
						$this->UnsetElements($i);
					} else {
						
					} 
				break;
				case 7:
					if ($this->businesscuisine[$i]!="Pubs") {
						$this->cuisine="Pubs";						
						$this->UnsetElements($i);
					} else {
						
					} 
				break;
				case 8: 
					if ($this->businesscuisine[$i]!="Pizza") {
						$this->cuisine="Pizza";						
						$this->UnsetElements($i);
					} else {
						
					} 
				break;
				case 9:
					if ($this->businesscuisine[$i]!="Spanish") {
						$this->cuisine="Spanish";						
						$this->UnsetElements($i);
					} else {
						
					} 
				break;
				case 10:
					if ($this->businesscuisine[$i]!="Japanese") {
						$this->cuisine="Japanese";						
						$this->UnsetElements($i);
					} else {
						
					} 
				break;

				default: $this->cuisine="Multiple Categories"; break;
			}
			
		}
				
		//Minimum rating filtering
		
		/*foreach ($this->businessrating as $key => $value) {
    		if ($value < $min) {
        		unset($this->businessrating[$key]);
        		$this->businessrating = array_values($this->businessrating);
			}
		}*/

				
	
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
	
	public function UnsetElements($index) {
		
		unset($this->businesscuisine[$index]);
		unset($this->locmarkers[$index]);
		unset($this->businessname[$index]);
		unset($this->businesstype[$index]);
		unset($this->businessaddr1[$index]);
		unset($this->businessrating[$index]);
					
		//normalize array indices
		$this->locmarkers = array_values($this->locmarkers);
		$this->businessname = array_values($this->businessname);
		$this->businesstype = array_values($this->businesstype);
		$this->businessaddr1 = array_values($this->businessaddr1);
		$this->businessrating = array_values($this->businessrating);
		$this->businesscuisine = array_values($this->businesscuisine);
		
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
			$sql2="('".$user_id."','".$arr[0]."','".$arr[4]."','".$arr[2]."','".$arr[1]."','".$arr[3]."','".$arr[5]."','".($arr[6]*10)."');";
			
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
