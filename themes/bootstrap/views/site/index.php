<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit',array(
    'heading'=>'Welcome to '.CHtml::encode(Yii::app()->name),
)); ?>

<br />
<center><img src="http://i49.tinypic.com/33trq8n.png" width="400" alt="logo"/></center>

<?php $this->endWidget(); ?>
