<?php
/* @var $this SiteController */
/* @var $model FSAModel */

$this->pageTitle=Yii::app()->name . ' - Results';
$this->breadcrumbs=array(
	'Results',
);
require_once(dirname(__FILE__)."/../../lib/OAuth.php");
	
?>
<!--Do not change!-->
<script type="text/javascript"
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDAV_5YynruTjKV72VGPuo8Jx2CMwFNlmo&sensor=true">
</script>
<script type="text/javascript" 
	src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=true">
</script>
<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js">
</script>


<script type="text/javascript">
    var lat = <?php echo json_encode($loc[0]); ?>;
    var lng = <?php echo json_encode($loc[1]); ?>;
    var local;
    var map;
    var refstring;
    var results = new Array();
    var places = new Array();
    var bnames = new Array();
    var baddr1 = new Array();
    var btype = new Array();
    
      function initialize() {
      
      	local = new google.maps.LatLng(lat, lng);
      
        var mapOptions = {
          center: local,
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
  			
  			//Google maps place request
  			var request1 = {
    			bounds: map.getBounds(),
    			name: 'Haldi'
  			};
  			
  			service = new google.maps.places.PlacesService(map);
 		    service.radarSearch(request1, callback);
		});
      }
      
      function plotFSAMarkers() {
      	var latArr = new Array();
      	var longArr = new Array();
      	var tempArr = new Array();
      	service = new google.maps.places.PlacesService(map);
      	      	
		for (var i = 0; i < places.length; i++)
		{
			var a = JSON.stringify(bnames[i]).split('"');
			var loc = JSON.stringify(bnames[i])+'<br />'+JSON.stringify(baddr1[i])+'<br />'+JSON.stringify(btype[i]);
				
			tempArr = places[i].split(',');
			latArr[i] = tempArr[0];
			longArr[i] = tempArr[1];
			
			Mk = new google.maps.LatLng(latArr[i], longArr[i]);
			
        	addMarker(Mk,loc);
		}
		      
      }
      
      function callback(results, status) {
      	if(status ==  google.maps.places.PlacesServiceStatus.OK) {
      		for (var i = 0; i < results.length; i++) {
      			refstring = results[i].reference;
      		}
      	} else {
      		alert(status);
      	}
      	  			  			
  			//Google maps place detail request
  			var request2 = {
  				reference: refstring
  			};
  			  			
  			service = new google.maps.places.PlacesService(map);
  			  			
  			service.getDetails(request2, function(details, status) {
        		fp = details.photos;
        		alert(fp.length);
    		})
      }
      
      
</script>

<!--Do not change-->
<div>
<div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Your History</li>
              <li class="active"><a href="#">Current Query</a></li>
              <li><a href="#">Chinese & Indian</a></li>
              <li><a href="#">Greek Pubs</a></li>
              <li class="active"><a href="#">Recommendations</a></li>
			  <li><a href="#">Haldi Southwater</a></li>
			  <li><a href="#">London Road, Horsham</a></li>
              <li class="nav-header">Range</li>
              <li><a href="#">1-10 miles</a></li>
              <li><a href="#">10-20 miles</a></li>
              <li><a href="#">30+ miles</a></li>
           </ul>
           <hr>
            <ul class="nav nav-list">
              <li class="nav-header">Your History</li>
              <li class="active"><a href="#">Current Query</a></li>
              <li><a href="#">Chinese & Indian</a></li>
              <li><a href="#">Greek Pubs</a></li>
              <li class="active"><a href="#">Recommendations</a></li>
			  <li><a href="#">Haldi Southwater</a></li>
			  <li><a href="#">London Road, Horsham</a></li>
              <li class="nav-header">Range</li>
              <li><a href="#">1-10 miles</a></li>
              <li><a href="#">10-20 miles</a></li>
              <li><a href="#">30+ miles</a></li>
           </ul>
          </div><!--/.well -->
        </div><!--/span-->
        
        <div class="span9">
          <div class="leaderboard">
          <div id='map' style='width:100%; height:360px'></div>
          </div>

<!--Process results array from model-->
<?php
		
	echo $results;
	
	for ($i = 0; $i < count($bname); $i++) {
	
	    echo "<div class='row-fluid'>";
	    echo "<div class='span4'>";
		echo "<h4><a href = '#myModal' data-toggle='modal'>".$bname[$i]."</a></h4>";
		echo $btype[$i]."</br>";
		echo $baddr1[$i]."</br>";
		echo "Rating: " .$brate[$i]."</br>";
		
		//FSA Image rating loader
		?><p><?
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

		?></p>
		
		</div>
				
		<script type = "text/javascript">		
			places[<?php echo json_encode($i); ?>] = <?php echo json_encode($markers[$i]); ?>;
			bnames[<?php echo json_encode($i); ?>] = <?php echo json_encode($bname[$i]); ?>;
			baddr1[<?php echo json_encode($i); ?>] = <?php echo json_encode($baddr1[$i]); ?>;
			btype[<?php echo json_encode($i); ?>] = <?php echo json_encode($btype[$i]); ?>;
		</script>
		<?
		
	}
	
	
?>
</div>
<script type='text/javascript'>initialize();</script>
</div>

<div id="forAjaxRefresh"></div>
 
<?php echo CHtml::ajaxLink('clickMe', array('ajax'), array('update'=>'#forAjaxRefresh', 'data'=>array('mk'=>'1')));?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'myModal')); ?>
 
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Should I eat at </h4>
</div>
 
<div class="modal-body">
    <p>One fine body...</p>
</div>
 
<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'type'=>'primary',
        'label'=>'Save changes',
        'url'=>'#',
        'htmlOptions'=>array('data-dismiss'=>'modal'),
    )); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Close',
        'url'=>'#',
        'htmlOptions'=>array('data-dismiss'=>'modal'),
    )); ?>
</div>
 
<?php $this->endWidget(); ?>

<?php

//Populate sidebar 2 with relevant information.

?>



