<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit',array(
    'heading'=>'Welcome to '.CHtml::encode(Yii::app()->name),
)); ?>

<br />
<center><img src="http://i49.tinypic.com/33trq8n.png" width="400" alt="logo"/></center>

<a href="#" rel="tooltip" title="Get Current Location"><i class="icon-globe" onclick="getLocation()"></i></a>

<?php $this->widget('bootstrap.widgets.TbButton', array(
    'label'=>"I'm feeling lucky!",
    'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'size'=>'large', // null, 'large', 'small' or 'mini'
	'buttonType'=>'submit',
)); ?>

<?php $this->endWidget(); ?>

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
