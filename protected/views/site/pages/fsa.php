<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - FSA';
$this->breadcrumbs=array(
	'FSA',
);
?>
<h2>Look for what you're after!</h2>
<h4>Let us know what you want and we'll show you where to go and where to avoid!<h4>

<!--FSA Search Form, will eventually be based on UM options -->
<div class="form">
	<div class="row">
          <label for="inputid">Location</label>
          <input name="locationid" id="locationid" type="text" />
          <p class="hint" onclick="getLocation()">Get Current Location</p>
    </div>
    <div class="row">
          <label for="inputid">Minimum Rating</label>
          <input name="inputid" id="inputid" type="text" />
          <p class="hint">Don't get caught out by poor restaurant standards!</p>
    </div>
    <div class="row buttons">
          <label for="inputid">Cuisine</label>
          <input name="inputid" id="inputid" type="text" />
          <p class="hint">What do you fancy?</p>
    </div>
    <div class="row buttons">
          <label for="inputid">Want to know the buzz?</label>
          <input name="inputid" id="inputid" type="text" />
          <p class="hint">Find out what others are saying.</p>
    </div>
</div>

<script type="text/javascript">
var tbox=document.getElementById("locationid");

function getLocation()
{
	if (navigator.geolocation)
    {
    	navigator.geolocation.getCurrentPosition(showPosition);
    }
  	else
  	{
  		tbox.value = "undefined";
  	}
}

function showPosition(position)
{
	tbox.value =  position.coords.latitude + ", " + position.coords.longitude; 
}

</script>
