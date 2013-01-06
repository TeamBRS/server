<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit',array(
    'heading'=>'Welcome to '.CHtml::encode(Yii::app()->name),
)); ?>

<br />
<center><img src="http://i49.tinypic.com/33trq8n.png" width="400" alt="logo"/></center>

<div class="btn-toolbar">
    <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type'=>'danger', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'buttons'=>array(
            array('label'=>'Minimum Rating', 'items'=>array(
                array('label'=>'1 Star', 'url'=>'#'),
                array('label'=>'2 Star', 'url'=>'#'),
                array('label'=>'3 Star', 'url'=>'#'),
                array('label'=>'4 Star', 'url'=>'#'),
                array('label'=>'5 Star', 'url'=>'#'),

                '---',
                array('label'=>'Surprise me!', 'url'=>'#'),
            )),
        ),
    )); ?>
</div>

<div class="btn-toolbar">
    <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type'=>'success', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'buttons'=>array(
            array('label'=>'Cuisine', 'items'=>array(
            	array('label'=>'English', 'url'=>'#'),
                array('label'=>'Italian', 'url'=>'#'),
                array('label'=>'Chinese', 'url'=>'#'),
                array('label'=>'Indian', 'url'=>'#'),
                array('label'=>'Greek', 'url'=>'#'),
                array('label'=>'Fast Food', 'url'=>'#'),
                array('label'=>'Light Bites', 'url'=>'#'),
                '---',
                array('label'=>'Surprise me!', 'url'=>'#'),
            )),
        ),
    )); ?>
</div>

<div class="btn-toolbar">
    <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type'=>'inverse', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'buttons'=>array(
            array('label'=>'Venue', 'items'=>array(
                array('label'=>'Restaurant', 'url'=>'#'),
                array('label'=>'Pub/Bar', 'url'=>'#'),
                array('label'=>'Cafe', 'url'=>'#'),
                array('label'=>'Outlet', 'url'=>'#'),
                array('label'=>'Canteen', 'url'=>'#'),
                array('label'=>'Takeaway', 'url'=>'#'),
                '---',
                array('label'=>'Surprise me!', 'url'=>'#'),
            )),
        ),
    )); ?>
</div>

<div class="btn-toolbar">
    <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type'=>'primary', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'buttons'=>array(
            array('label'=>'Social Feeds', 'items'=>array(
                array('label'=>'Facebook', 'url'=>'#'),
                array('label'=>'Twitter', 'url'=>'#'),
                array('label'=>'Google+', 'url'=>'#'),
                '---',
                array('label'=>'Surprise me!', 'url'=>'#'),
            )),
        ),
    )); ?>
</div>

<?php $this->endWidget(); ?>
