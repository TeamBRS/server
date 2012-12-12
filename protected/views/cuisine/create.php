<?php
/* @var $this CuisineController */
/* @var $model Cuisine */

$this->breadcrumbs=array(
	'Cuisines'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Cuisine', 'url'=>array('index')),
	array('label'=>'Manage Cuisine', 'url'=>array('admin')),
);
?>

<h1>Create Cuisine</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>