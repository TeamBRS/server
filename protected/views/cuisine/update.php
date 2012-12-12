<?php
/* @var $this CuisineController */
/* @var $model Cuisine */

$this->breadcrumbs=array(
	'Cuisines'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Cuisine', 'url'=>array('index')),
	array('label'=>'Create Cuisine', 'url'=>array('create')),
	array('label'=>'View Cuisine', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Cuisine', 'url'=>array('admin')),
);
?>

<h1>Update Cuisine <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>