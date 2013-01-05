<?php

require_once("OAuth.php");

	//Horrible OAUTH Data - Ignore

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

	//#########################
	
	$restaurant = "zizzi's";
	$geocode_lat = "52.3813";
	$geocode_long = "-1.5617";
	
	//Where the URL's are formed.
	$general_url = "http://api.yelp.com/v2/search?term=".$restaurant."&ll=".$geocode_lat.",".$geocode_long;
	//$general_url = "http://api.yelp.com/business_review_search?category=bars&lat=".$latitude."&long=".$longitude."&radius=25&num_biz_requested=100&ywsid=<your yelp account id>"; 

	
	//#########################

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
		
	foreach($response as $restaur):
	    echo $restaur->categories[0][0]."<br/>";
	endforeach;
	
	echo '<pre>';
	print_r($response);
	echo '</pre>';

?>
