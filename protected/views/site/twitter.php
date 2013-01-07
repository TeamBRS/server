<?php
 
function getTwitterData($location, $sentiment, $bnames) {

	$sentiment = true;
	$restaurant = "Pizza%20Hut";
	$geocode_lat = $location[0];
	$geocode_long = $location[1];
	$geocode_radius = "50mi";
	$positive = '"I%20love"%20OR%20"I%20like"%20OR%20"I%20need"%20OR%20"is%20good"%20OR%20"is%20really%20good"%20OR%20"is%20really%20cool"%20OR%20"is%20amazing"%20OR%20"is%20awesome"%20OR%20"is%20the%20best"';
	$negative = '"don\'t%20like"%20OR%20"is%20horrible"%20OR%20"is%20stupid"%20OR%20"is%20crap"%20OR%20"is%20bad"%20OR%20"is%20awful"%20OR%20"is%20shit"';
	$choice = $positive;
	if (!$sentiment) {
		$choice = $negative;
	}

	$tweets = array();

	for ($i=0;$i<count($bnames);$i++) {

		$bnames[$i] = preg_replace('/\s+/', '%20',$bnames[$i]);
		$data = 'http://search.twitter.com/search.json?q="' . $bnames[$i] . '"%20AND%20' . $choice . '&rpp=5&geocode=' . $geocode_lat . ',' . $geocode_long . ',' . $geocode_radius . '&include_entities=true';


		try {
		
			$feed = @file_get_contents($data); //Getting the JSON data.
	 	
	 		if($feed === FALSE) { 

				//do nothing
	 		
	 		} else {
	 	
				$valid_data = json_decode($feed); // Converting the JSON data to PHP format.
				$valid_data = $valid_data->results; // Valid data now with just the tweet result.
				// Printing out the feed's data in our required format

				foreach ($valid_data as $key=>$value) {
			
	  				array_push($tweets, '<div id="twitter-data-container">
						  	<blockquote class="twitter-tweet"><p>'.$value->text.'</p>&mdash; @'.$value->from_user.' <a href="https://twitter.com/twitterapi/status/'.strval($value->id).'" data-datetime="'.$value->created_at.'">'.$value->created_at.'</a></blockquote>
						  	</div>');
	  	
	  			}
			}
			
		} catch (Exception $e) {
		
			//do nothing
		
		}
	
	}
	
?><script src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
<?php

	return $tweets;

}
?>
