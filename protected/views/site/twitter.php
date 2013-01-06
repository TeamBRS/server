<?php
 
function getTwitterData() {

	$sentiment = true;
	$restaurant = "Pizza%20Hut";
	$geocode_lat = "52.3813";
	$geocode_long = "-1.5617";
	$geocode_radius = "100mi";
	$positive = '"I%20love"%20OR%20"I%20like"%20OR%20"I%20need"%20OR%20"is%20good"%20OR%20"is%20really%20good"%20OR%20"is%20really%20cool"%20OR%20"is%20amazing"%20OR%20"is%20awesome"%20OR%20"is%20the%20best"';
	$negative = '"don\'t%20like"%20OR%20"is%20horrible"%20OR%20"is%20stupid"%20OR%20"is%20crap"%20OR%20"is%20bad"%20OR%20"is%20awful"%20OR%20"is%20shit"';
	$choice = $positive;
	if (!$sentiment) {
		$choice = $negative;
	}

	$data = 'http://search.twitter.com/search.json?q="' . $restaurant . '"%20' . $choice . '&rpp=10&geocode=' . $geocode_lat . ',' . $geocode_long . ',' . $geocode_radius . '&include_entities=true';
	$feed = file_get_contents($data); //Getting the JSON data.
	 
	$tweets = array();

	$valid_data = json_decode($feed); // Converting the JSON data to PHP format.
	
	$valid_data = $valid_data->results; // Valid data now with just the tweet result.
	// Printing out the feed's data in our required format

	foreach ($valid_data as $key=>$value) {

	  array_push($tweets, '<div id="twitter-data-container">
						  <blockquote class="twitter-tweet"><p>'.$value->text.'</p>&mdash; @'.$value->from_user.' <a href="https://twitter.com/twitterapi/status/'.strval($value->id).'" data-datetime="'.$value->created_at.'">'.$value->created_at.'</a></blockquote>
						  </div>');
	  
	}
?><script src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
<?php

	return $tweets;

}
?>
