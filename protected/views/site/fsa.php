<?php
/* @var $this SiteController */
/* @var $model FSAModel */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - FSA';
$this->breadcrumbs=array(
	'FSA',
);
?>

<?php if(Yii::app()->user->hasFlash('fsa')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('fsa'); ?>
</div>

<?php else: ?>

<h2>Look for what you're after!</h2>
<h4>Let us know what you want and we'll show you where to go and where to avoid!</h4>

<div class="alert alert-info">
<b>Instructions</b> Fill out the form below to begin developing the preferences for your profile!
</div>

<!--FSA Search Form, will eventually be based on UM options -->
<!--Only has access to business types with following ID:

1 - restaurant/cafe
8 - pub/bar/nightclub
10 - takeaway/sandwich shop

XML query string attributes
/enhanced-search/
en-GB/ (language)
^ (means no name)/
^ (means no address)/
DISTANCE (ordered nearest/furthest)
1 (business type)
^ (means all local authorities)/
-0.34726219999999997 (long)/
51.0150669 (lat) /
1 (page) /
100 (number of results) /xml

-->

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'fsa-form',
	'enableClientValidation'=>true,
	'htmlOptions'=>array('class'=>'well'),
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
		  <?php echo $form->textFieldRow($model,'location',array('id'=>'loc_id','class'=>'ext')); ?>
		  <a href="#" rel="tooltip" title="Get Current Location"><i class="icon-globe" onclick="getLocation()"></i></a>
		 <br />
		  <h2><?php echo $form->labelEx($model,'minrating'); ?></h2>
		  <?php echo $form->radioButtonList($model,'minrating', array('1'=>'1 Star','2'=>'2 Star','3'=>'3 Star','4'=>'4 Star','5'=>'5 Star'), array('separator'=>' ', 'uncheckValue'=>null)); ?>
   <br />
		  <h2><?php echo $form->labelEx($model,'cuisine'); ?></h2>
		  <?php echo $form->checkBoxList($model,'cuisine', array(0=>'English',1=>'Greek',2=>'Chinese',3=>'Indian',4=>'Fast Food',5=>'Light Bites'), array('separator'=>' ')); ?>
    <br />
		  <h2><?php echo $form->labelEx($model,'venue'); ?></h2>
		  <?php echo $form->radioButtonList($model,'venue', array(0=>'Restaurant',1=>'Pub/Bar',2=>'Cafe',3=>'Outlet',4=>'Canteen',5=>'Takeaway'), array('separator'=>' ')); ?>
    <br />
		  <h2><?php echo $form->labelEx($model,'socialfeeds'); ?></h2>
		  <?php echo $form->checkBoxList($model,'socialfeeds', array(0=>'Facebook',1=>'Twitter',2=>'Google+'), array('separator'=>' ')); ?>

<br />

<?php $this->widget('bootstrap.widgets.TbButton', array(
    'label'=>'Find me food!',
    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'size'=>'large', // null, 'large', 'small' or 'mini'
	'buttonType'=>'submit',
)); ?>

<?php $this->endWidget(); ?>
</div>

<?php endif; ?>

<script type="text/javascript">

var tbox=document.getElementById('loc_id');

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
	tbox.value = position.coords.latitude + ", " + position.coords.longitude; 
}

</script>
