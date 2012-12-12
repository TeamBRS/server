<?php
/* @var $this CuisineController */
/* @var $model Cuisine */

$this->breadcrumbs=array(
	'Cuisines'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Cuisine', 'url'=>array('index')),
	array('label'=>'Create Cuisine', 'url'=>array('create')),
	array('label'=>'Update Cuisine', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Cuisine', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Cuisine', 'url'=>array('admin')),
);
?>

<h1>View Cuisine #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'type',
		'description',
		'parent',
	),
)); ?>
