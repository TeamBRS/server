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
<h4>Let us know what you want and we'll show you where to go and where to avoid!<h4>

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
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'fsa-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
	<div class="row">
		  <h2><?php echo $form->labelEx($model,'location'); ?></h2>
		  <?php echo $form->textField($model,'location',array('id'=>'loc_id', 'onclick'=>'getLocation()', 'class'=>'ext')); ?>
    </div>
    <br />
    <div class="row">
		  <h2><?php echo $form->labelEx($model,'minrating'); ?></h2>
		  <?php echo $form->radioButtonList($model,'minrating', array('1'=>'1 Star','2'=>'2 Star','3'=>'3 Star','4'=>'4 Star','5'=>'5 Star'), array('separator'=>' ', 'uncheckValue'=>null)); ?>
          <p class="hint">Don't get caught out by poor restaurant standards!</p>
    </div>
    <br />
    <div class="row">
		  <h2><?php echo $form->labelEx($model,'cuisine'); ?></h2>
		  <?php echo $form->checkBoxList($model,'cuisine', array(0=>'English',1=>'Greek',2=>'Chinese',3=>'Indian',4=>'Fast Food',5=>'Light Bites'), array('separator'=>' ')); ?>
          <p class="hint">What do you fancy?</p>
    </div>
    <br />
    <div class="row">
		  <h2><?php echo $form->labelEx($model,'venue'); ?></h2>
		  <?php echo $form->checkBoxList($model,'venue', array(0=>'Restaurant',1=>'Pub/Bar',2=>'Cafe',3=>'Outlet',4=>'Canteen',5=>'Takeaway'), array('separator'=>' ')); ?>
          <p class="hint">Where do you fancy it?</p>
    </div>
    <br />
    <div class="row">
		  <h2><?php echo $form->labelEx($model,'socialfeeds'); ?></h2>
		  <?php echo $form->checkBoxList($model,'socialfeeds', array(0=>'Facebook',1=>'Twitter',2=>'TripAdvisor'), array('separator'=>' ')); ?>
          <p class="hint">Find out what others are saying.</p>
    </div>
    <div class="row buttons">
		<?php echo CHtml::submitButton('Find me food!'); ?>
	</div>
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
