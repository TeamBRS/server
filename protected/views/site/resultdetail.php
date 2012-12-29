<?php
/* @var $this SiteController */
/* @var $model FSAModel */
	require_once(dirname(__FILE__)."/../../lib/OAuth.php");
	
	//print_r($data);
	
	$consumer_key = "U9oEb5Wb917RLdeHip-7bA";
	$consumer_secret = "IiBJE1TehegxK3Dh7NVtz5VR9_s";
	$token = "p_PNhBC0Xp9aNq-glTxMp_hWZuqR6mMS";
	$token_secret = "ZSjU3YERnlzGWGA_XDjpWFzEwkM";

	// Token object built using the OAuth library
	$token = new OAuthToken($token, $token_secret);

	// Consumer object built using the OAuth library
	$consumer = new OAuthConsumer($consumer_key, $consumer_secret);

	// Yelp uses HMAC SHA1 encoding
	$signature_method = new OAuthSignatureMethod_HMAC_SHA1();
	
	//test url
	$general_url="http://api.yelp.com/v2/search?term=food&location=Southwater";
	//$general_url = "http://api.yelp.com/v2/search?term=food&ll=".$lat.",".$lng;
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

	// Print it for debugging
	print_r($response);

?>