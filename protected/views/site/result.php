<?php
/* @var $this SiteController */
/* @var $model FSAModel */

$this->pageTitle=Yii::app()->name . ' - Results';
$this->breadcrumbs=array(
	'Results',
);
?>
<!--Do not change!-->
<script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDAV_5YynruTjKV72VGPuo8Jx2CMwFNlmo&sensor=true">
</script>


<script type="text/javascript">
    var lat = <?php echo json_encode($loc[0]); ?>;
    var lng = <?php echo json_encode($loc[1]); ?>;
    var map;
    var places = new Array();
    var bnames = new Array();
    var baddr1 = new Array();
    var btype = new Array();
    
      function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(lat, lng),
          zoom: 13,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("map"),
            mapOptions);
        plotFSAMarkers();
      }
      
      function addMarker(location,bname) {
            
      	var marker = new google.maps.Marker({
      		position: location,
      		map: map
      	});
      	
      	marker.info = new google.maps.InfoWindow({
  			content: String(bname)
		});
		
		google.maps.event.addListener(marker, 'click', function() {
  			marker.info.open(map, marker);
		});
      }
      
      function plotFSAMarkers() {
      	var latArr = new Array();
      	var longArr = new Array();
      	var tempArr = new Array();
      	      	
		for (var i = 0; i < places.length; i++)
		{
		
			var loc = JSON.stringify(bnames[i])+'<br />'+JSON.stringify(baddr1[i])+'<br />'+JSON.stringify(btype[i]);
			
			tempArr = places[i].split(',');
			latArr[i] = tempArr[0];
			longArr[i] = tempArr[1];
			
			Mk = new google.maps.LatLng(latArr[i], longArr[i]);
			
        	addMarker(Mk,loc);
		}
      
      }
      
      
</script>
<!--Do not change-->
<div>
<!--Process results array from model-->
<?php
		
	echo $results;
	
	for ($i = 0; $i < count($bname); $i++) {
	
		echo "</br><h4><a href = '#' onclick='getReviews()'>".$bname[$i]."</a></h4>";
		echo $btype[$i]."</br>";
		echo $baddr1[$i]."</br>";
		echo "Rating: " .$brate[$i]."</br>";
		
		//FSA Image rating loader
		switch($brate[$i]) {
		
			case "0": ?><img src="././fsaimages/fhrs_0_en-gb.jpg" alt="0"/><? break;
			case "1": ?><img src="././fsaimages/fhrs_1_en-gb.jpg" alt="1"/><? break;
			case "2": ?><img src="././fsaimages/fhrs_2_en-gb.jpg" alt="2"/><? break;
			case "3": ?><img src="././fsaimages/fhrs_3_en-gb.jpg" alt="3"/><? break;
			case "4": ?><img src="././fsaimages/fhrs_4_en-gb.jpg" alt="4"/><? break;
			case "5": ?><img src="././fsaimages/fhrs_5_en-gb.jpg" alt="5" /><? break;
			case "exempt": ?><img src="././fsaimages/fhrs_exempt_en-gb.jpg" alt="ex" /><? break;
			case "awaitingpublication": ?><img src="././fsaimages/fhrs_awaitingpublication_en-gb.jpg" alt="ap" /><? break;
			case "awaitinginspection": ?><img src="././fsaimages/fhrs_awaitinginspection_en-gb.jpg" alt="ai" /><? break;
			
		}

		?>
		
		<div id="reviewsinfo"></div>
		
		<br />
		<script type = "text/javascript">		
			places[<?php echo json_encode($i); ?>] = <?php echo json_encode($markers[$i]); ?>;
			bnames[<?php echo json_encode($i); ?>] = <?php echo json_encode($bname[$i]); ?>;
			baddr1[<?php echo json_encode($i); ?>] = <?php echo json_encode($baddr1[$i]); ?>;
			btype[<?php echo json_encode($i); ?>] = <?php echo json_encode($btype[$i]); ?>;
		</script>
		<?
	
	}
	
	
?>
<div id="map" style="width:480px; height:360px"></div>
<script type="text/javascript">initialize();</script>

</div>