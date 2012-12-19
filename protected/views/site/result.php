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
			
			tempArr = places[i].split(',');
			latArr[i] = tempArr[0];
			longArr[i] = tempArr[1];
			
			Mk = new google.maps.LatLng(latArr[i], longArr[i]);
        	addMarker(Mk,JSON.stringify(bnames[i]));
		}
      
      }
      
      
</script>
<!--Do not change-->
<div>
<!--Process results array from model-->
<?php
		
	echo $results;
	
	for ($i = 0; $i < 5; $i++) {
	
		echo "</br><h4>".$bname[$i]."</h4>";
		echo $btype[$i]."</br>";
		echo $baddr1[$i]."</br>";
		echo "Rating: " .$brate[$i]."</br>";

		?>
		<script type = "text/javascript">		
			places[<?php echo json_encode($i); ?>] = <?php echo json_encode($markers[$i]); ?>;
			bnames[<?php echo json_encode($i); ?>] = <?php echo json_encode($bname[$i]); ?>;
		</script>
		<?
	
	}
	
	
?>
<div id="map" style="width:480px; height:360px"></div>
<script type="text/javascript">initialize();</script>

</div>