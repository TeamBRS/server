<?php
/* @var $this SiteController */
/* @var $model FSAModel */
	require_once(dirname(__FILE__)."/../../lib/OAuth.php");
	
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
	
	//test url
	$general_url = "http://api.yelp.com/v2/search?term=".$name."&ll=".$location[0].",".$location[1];
	$specific_url = "http://api.yelp.com/business_review_search?term=The Cowdray&lat=".$location[0]."&long=".$location[1]."&limit=5&ywsid=".$ywsid;
	
	$oauthrequest = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $general_url);
	$oauthrequest->sign_request($signature_method, $consumer, $token);
	$signed_url = $oauthrequest->to_url();

	// Send Yelp API Call
	$ch = curl_init($specific_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$data = curl_exec($ch); // Yelp response
	curl_close($ch);

	// Handle Yelp response data
	$response = json_decode($data, true);

	if ($response==null) {
	?>
		<div class="alert alert-error">
  			<b>API Calls exceeded!</b> Noooo....no information from Yelp.com received.
		</div>
	<?
	}
	else {
	
		echo '<h3>'.$response['businesses'][0]['name'].'</h3>';
		//	echo '<img src="'.$response['businesses'][0]['rating_img_url'].'" />'; 
		//	echo '<i> '.$response['businesses'][0]['avg_rating'].' stars (on average)</i>';
		//	echo '<br /><b> '.$response['businesses'][0]['address1'].' </b>';
		//	echo '<br /><b> '.$response['businesses'][0]['city'].'</b>';
		//	echo '<br /><b> '.$response['businesses'][0]['phone'].'</b>';
	
		//	echo '<h4>Review by '.$response['businesses'][0]['reviews'][0]['user_name'].'<img src="'.$response['businesses'][0]['reviews'][0]['rating_img_url_small'].'" /></h4>'; 
		//	echo '<p><i>'.$response['businesses'][0]['reviews'][0]['text_excerpt'].'</i></p>';
		//	echo '<p><i>'.$response['businesses'][0]['reviews'][0]['date'].'</i></p>';
	
	}
	
	echo "<p><a href='#map' onclick='toggleSlider();'>Close</a></p>";

?>