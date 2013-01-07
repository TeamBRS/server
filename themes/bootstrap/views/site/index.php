<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<center><?php $this->beginWidget('bootstrap.widgets.TbHeroUnit',array(
    'heading'=>'Welcome to '.CHtml::encode(Yii::app()->name),
)); ?>
<br />
<center><img src="http://i49.tinypic.com/33trq8n.png" width="400" alt="logo"/></center>

<center><a href="index.php?r=site/fsa"><?php $this->widget('bootstrap.widgets.TbButton', array(
    'label'=>"Start Search",
    'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'size'=>'large', // null, 'large', 'small' or 'mini'
    'buttonType'=>'submit',
)); ?></a></center>

<?php $this->endWidget(); ?></center>

