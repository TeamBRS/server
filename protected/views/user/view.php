<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->username,
);

/*$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);*/

?>

<div class="form">
	<div class="row">
		<div class="span6">
			<h1><?php echo $model->username; ?>'s Profile</h1>
			<div>
				<h4>Vitals</h4>
				<strong>Email:</strong> <?php echo $model->email; ?> <br />
				<strong>Password:</strong> <?php echo $model->password; ?> <br />
				<a href="<?php echo Yii::app()->createUrl('user/update', array('id'=>$model->id)); ?>">[edit]</a>
			</div>
			
			<div>
				<h4>Favourite cuisines</h4>
				Insert results of a beautiful query here...
			</div>
			
			<div>
				<h4>Places Visited</h4>
				Insert results of a beautiful query here...
			</div>
		</div>
		<div class="span3">Change Picture</div>
	</div>
</div>


<!--
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'password',
		'email',
	),
)); ?>-->
