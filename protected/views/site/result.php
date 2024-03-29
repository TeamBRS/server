<?php
/* @var $this SiteController */
/* @var $model FSAModel */

/*--- RESULT.PHP Script ---
 This script contains javascript sections for rendering of GMAP apiVersion
 and ajax calls as well as php retrieval from yii model framework. Currently
 quite messy but will be cleaned up
*/

$this->pageTitle=Yii::app()->name . ' - Results';
$this->breadcrumbs=array(
	'Results',
);

include('twitter.php');
include('recommender.php');
	
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
$(document).on("click", ".open-SocialFeed", function () {
     var mySocialId = $(this).data('id');
     $(".modal-body #socialid").val( mySocialId );
    $('#socialactivity').modal('show');
});

</script>

<script type="text/javascript">

	//-- JQuery Specific Scripts --

	function toggleSlider() {
	
    if ($("#panelslider").is(":visible")) {
        $("#contentslider").animate(
            {
                opacity: "0"
            },
            600,
            function(){
                $("#panelslider").slideUp();
            }
        );
    }
    else {
        $("#panelslider").slideDown(600, function(){
            $("#contentslider").animate(
                {
                    opacity: "1"
                },
                600
            );
        });
    }   
	}
</script>


<script type="text/javascript">
	
	//-- Google Maps Specific Scripts --

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
        
        map = new google.maps.Map(document.getElementById("map_canvas"),
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
      	var bounds = new google.maps.LatLngBounds();
      	service = new google.maps.places.PlacesService(map);
      	      	
		for (var i = 0; i < places.length; i++)
		{
			var a = JSON.stringify(bnames[i]).split('"');
			var loc = JSON.stringify(bnames[i])+'<br />'+JSON.stringify(baddr1[i])+'<br />'+JSON.stringify(btype[i]);
						
			tempArr = places[i].split(',');
			latArr[i] = tempArr[0];
			longArr[i] = tempArr[1]*10;
			
			Mk = new google.maps.LatLng(latArr[i], longArr[i]);
						
			bounds.extend(Mk);
			
        	addMarker(Mk,loc);
		}
		
		map.fitBounds(bounds);
		      
      }
      
</script>

<!--HTML Layout for the view-->

<div>
<div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Details of Search</li>
              <!--php customisation options go here -->
              <li>Rating: <?php echo $br; ?></li>
              <li>Cuisine: <?php echo $cco; ?></li>
		      <li>Venue: <?php echo $bt; ?></li>
              <li class="nav-header">Social Feeds</li>
              <?php $res = getTwitterData($loc, true, $bname); ?>
              <li><a href="#socialactivity" data-id="twitter" data-toggle="modal" class="open-SocialFeed">Twitter Activity 
              <?php $this->widget('bootstrap.widgets.TbBadge', array(
    			'type'=>'info', // 'success', 'warning', 'important', 'info' or 'inverse'
    			'label'=>count($res),
			  )); ?>
              </a></li>
              <li><a href="#">Facebook</a></li>
           </ul>
           <hr>
            <ul class="nav nav-list">
              <li class="nav-header"><?php echo Yii::app()->user->id." , why not try...?";?></li>
              <!--load previous queries from database here-->
              <li class="active"><a href="#">Based on previous searches</a></li>
			  <?php
			  
              for ($i = 0; $i < 5; $i++) {
              
              	$bn = $past[rand(0, count($past)-1)]['business_name'];
              
                echo '<li>'.CHtml::ajaxLink($bn, array('ajax'), array('update'=>'#summary', 'type'=> 'POST', 'data'=>array('mk'=>$loc,'name'=>$bn)), array('onclick'=>'toggleSlider();', 'href'=>'#map')).'</li>';	
             
              }
			  
			  ?>
			  <hr>
			  <li class="active"><a href="#">Based on Cuisine Type</a></li>
			  <?php
			  
			    for ($i = 0; $i < 5; $i++) {
              
              		$cats = $cuisine[rand(0, count($cuisine)-1)];
              		echo '<li><a href = "#">'.$cats.'</a></li>';
              	
              	}
			  ?>
           </ul>
          </div><!--/.well -->
        </div><!--/span-->
        
        <div class="span9">
          <div class="leaderboard">
          <div id='map_canvas' style='width:100%; height:360px'></div>
          </div>

<!--place twitter activity into a modal-->
<div class="modal hide" id="socialactivity">
 <div class="modal-header">
    <button class="close" data-dismiss="modal">×</button>
    <h3>See what hungry tweeters are saying..</h3>
  </div>
    <div class="modal-body">
        <?php 
        
        if (count($res)!=0) {
        	for($i=0;$i<count($res);$i++) {
        		echo $res[$i];
        	}
        } else {
        	?><div class="alert alert-error">
  				<b>No tweets</b> found for any establishments in this area.
			</div><?php
        }
        
        ?>
    </div>
</div>

<!--Process results array from model-->
<?php
		
	echo $results;
	
			//<a href='#profile' data-toggle='pill'>Social Buzz</a>
			echo "<div id='panelslider' style='display:none;background:#eee;padding:10px;'>
    		  <div id='contentslider' style='opacity:0;filter:alpha(opacity=0);'>
			  <ul class='nav nav-tabs' id='infotabs'>
  				<li><a href='#summary' data-toggle='pill'>Summary</a></li>
  				<li><a href='#profile' data-toggle='pill'>Facebook</a></li>
  				<li><a href='#messages' data-toggle='pill'>Recommendation</a></li>
			  </ul>
			  <div class='tab-content'>
  			  	<div class='tab-pane active' id='summary'></div>
  				<div class='tab-pane' id='profile'></div>
  				<div class='tab-pane' id='messages'>".recommenderData($loc, $res)."</div>
			  </div>
			  </div>
			  </div>"; 
	
	for ($i = 0; $i < count($brate); $i++) {
	
	    echo "<div class='row-fluid'>";
	    echo "<div class='span4'>";
	    $bs = strval($bname[$i]);
	    echo CHtml::ajaxLink('<h4>'.$bname[$i].'</h4>', array('ajax'), array('update'=>'#summary', 'type'=> 'POST', 'data'=>array('mk'=>$loc,'name'=>$bs)), array('onclick'=>'toggleSlider();', 'href'=>'#map'));
		echo $btype[$i]."</br>";
		echo $baddr1[$i]."</br>";
		echo "Rating: " .$brate[$i]."</br>";
		
		//FSA Image rating loader
		?><p><?php
		switch($brate[$i]) {
		
			case "0": ?><a href="#" rel="tooltip" title="Rating 0: Its probably too dangerous to eat here"><img src="././fsaimages/fhrs_0_en-gb.jpg" alt="0"/></a><?php break;
			case "1": ?><a href="#" rel="tooltip" title="Rating 1: In need of lots of improvement. Best avoid here."><img src="././fsaimages/fhrs_1_en-gb.jpg" alt="1"/></a><?php break;
			case "2": ?><a href="#" rel="tooltip" title="Rating 2: Needs improvement. Ok to visit on the one off."><img src="././fsaimages/fhrs_2_en-gb.jpg" alt="2"/></a><?php break;
			case "3": ?><a href="#" rel="tooltip" title="Rating 3: Generally ok. Could be improved."><img src="././fsaimages/fhrs_3_en-gb.jpg" alt="3"/></a><?php break;
			case "4": ?><a href="#" rel="tooltip" title="Rating 4: A safe and clean place to eat."><img src="././fsaimages/fhrs_4_en-gb.jpg" alt="4"/></a><?php break;
			case "5": ?><a href="#" rel="tooltip" title="Rating 5: Very safe and clean."><img src="././fsaimages/fhrs_5_en-gb.jpg" alt="5" /></a><?php break;
			case "exempt": ?><img src="././fsaimages/fhrs_exempt_en-gb.jpg" alt="ex" /><?php break;
			case "awaitingpublication": ?><img src="././fsaimages/fhrs_awaitingpublication_en-gb.jpg" alt="ap" /><?php break;
			case "awaitinginspection": ?><img src="././fsaimages/fhrs_awaitinginspection_en-gb.jpg" alt="ai" /><?php break;
			
		}

		?></p>
		
		</div>
				
		<script type = "text/javascript">		
			places[<?php echo json_encode($i); ?>] = <?php echo json_encode($markers[$i]); ?>;
			bnames[<?php echo json_encode($i); ?>] = <?php echo json_encode($bname[$i]); ?>;
			baddr1[<?php echo json_encode($i); ?>] = <?php echo json_encode($baddr1[$i]); ?>;
			btype[<?php echo json_encode($i); ?>] = <?php echo json_encode($btype[$i]); ?>;
		</script>
		<?php
		
	}
	
	
?>
</div>
<script type='text/javascript'>initialize();</script>
</div>

