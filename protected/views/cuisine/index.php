<?php
/* @var $this CuisineController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Cuisines',
);

$this->menu=array(
	array('label'=>'Create Cuisine', 'url'=>array('create')),
	array('label'=>'Manage Cuisine', 'url'=>array('admin')),
);
?>

<h1>Cuisines</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
