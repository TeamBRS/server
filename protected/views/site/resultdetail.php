<?php
/* @var $this SiteController */
/* @var $model FSAModel */
	require_once(dirname(__FILE__)."/../../lib/OAuth.php");
	
	/***********GOOGLEPLACES API*************/
		
	//URL Connection Block
	try {
	
		$bsearch = preg_replace('/\s+/', '+',$name);
		
		$apikey = "AIzaSyDAV_5YynruTjKV72VGPuo8Jx2CMwFNlmo";
		
		//get place reference
		$google_place_url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$location[0].",".$location[1]."&type=bar|establishment|food&radius=5000&name=".$bsearch."&sensor=false&key=".$apikey;
		$placeresult = file_get_contents($google_place_url);
		
		if ($placeresult==null) {§
		 ?>
			<div class="alert alert-error">
  				<b>Insufficient Information</b> This place hasn't had enough visitors yet.
			</div>
		<?php
		}
		
	} catch (Exception $e) {
	
		?>
		<div class="alert alert-error">
  			<b>Check your connection:</b> We can't seem to connect to the internet at the moment.
		</div>
		<?php
		
	}	
	//JSON Decoding Block (place)
	
	try {
	
		$decodedresult = json_decode($placeresult, true);
		
		if ($decodedresult['status']=="ZERO_RESULTS") {
		?>
			<div class="alert alert-error">
  				<b>Insufficient Information</b> This place hasn't had enough visitors yet.
			</div>
		<?php
		} else {
		
		//JSON Decoding Block (detail)
		
			$placereference = $decodedresult['results'][0]['reference'];
	
			try {
	
				$google_place_detail = "https://maps.googleapis.com/maps/api/place/details/json?reference=".$placereference."&sensor=true&key=".$apikey;
				$detailresult = file_get_contents($google_place_detail);
				$decodeddetail = json_decode($detailresult, true);
	
			} catch (Exception $e) {
	
		 		?>
				<div class="alert alert-error">
  					<b>Incorrect format received:</b> please refresh your browser.
				</div>
				<?php

			}
		
		}
		
	} catch (Exception $e) {
	
		?>
		<div class="alert alert-error">
  			<b>Incorrect format received:</b> please refresh your browser.
		</div>
		<?php
	
	} 
	
	//JSON Review Decoding Block
			
	if ($decodedresult['status']=="OK") {
	
		$listingdetails = array();
		
		try {
		
			$listingdetails['name'] = $decodedresult['results'][0]['name'];
			$listingdetails['road'] = $decodeddetail['result']['address_components'][0]['long_name'];
			$listingdetails['formatted_address'] = $decodeddetail['result']['formatted_address'];
			$listingdetails['phone'] = $decodeddetail['result']['formatted_phone_number'];
			$listingdetails['icon'] = $decodeddetail['result']['icon'];
		
			echo '<h3>'.$listingdetails['name'].'<img src = "'.$listingdetails['icon'].'" width="25" /></h3>';
			echo $listingdetails['road'].'<br />';
			echo $listingdetails['formatted_address'].'<br />';
			echo '<i>'.$listingdetails['phone'].'</i><br />';
		
		} catch (Exception $e) {
	
			?>
				<br />
				<div class="alert alert-error">
  					<b>Warning:</b> This listing contains partial information
				</div>
			<?php
		
		}
		
	}
	
		//JSON Photo Decoding Block
		try {
	
		/*if(array_key_exists('photos', $decodeddetail['result'])) {
			
			$listingdetails['photos'] = $decodeddetail['result']['photos'];

			$ref = $listingdetails['photos'][0]['photo_reference'];
			$photourl = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=280&photoreference=".$ref."&sensor=true&key=".$apikey;*/
	
		} catch (Exception $e) {
	
	
	
		}
		
	if ($decodedresult['status']=="OK") {
	
		//JSON Rating and Result Decoding Block
		try {
		
			if(!array_key_exists('rating', $decodeddetail['result'])) {
			?>
				<br />
    			<a href="#" class="thumbnail" rel="tooltip" data-title="Tooltip">
        		<img src="http://placehold.it/280x180" alt="">
        		<b>Average Rating:</b> N/A
    			</a>
				<div class="alert alert-error">
  					<b>Warning:</b> This listing contains partial information
				</div>
			<?php
			} else {
					
				if($decodeddetail['status']=="OK"){
					
					$listingdetails['rating'] = $decodeddetail['result']['rating'];
					?>
    				<a href="#" class="thumbnail" rel="tooltip" data-title="Tooltip">
        			<img src="http://placehold.it/280x180" alt="">
        			<b>Average Rating: </b> <i> <?php echo $listingdetails['rating']; ?> </i>
    				</a>
					<?php
					
				}
			}
		
			if(array_key_exists('reviews',$decodeddetail['result'])) {
		
				$listingdetails['reviews'] = $decodeddetail['result']['reviews'];
				echo '<br />';
	
				echo '<h4>'.count($listingdetails['reviews']).' people have left their feedback.</h4>';

				//Review Iterator
				foreach($listingdetails['reviews'] as $review) {
		
					echo '<p><b>Review by: </b><i>'.$review['author_name'].'</i></p>';
		
					foreach($review['aspects'] as $aspect) {
			
						echo '<b>'.$aspect['type']."</b>: ".$aspect['rating'].'<br />';
			
					}
			
					echo '<p>"'.$review['text'].'"</p><hr>';
			
				}
	
			}
		
		} catch (Exception $e) {
		
			Yii::app()->user->setFlash('warning', '<strong>Warning:</strong> This listing is currently incomplete.');
	
		}
	
	}
	
	echo "<p><a href='#map' onclick='toggleSlider();'>Close</a></p>";
	
	?>