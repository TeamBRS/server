<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->username,
);



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
				<?php $this->widget('application.widgets.facebook.Facebook',array('appId'=>'100257963482709')); ?>
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
		<div class="span3"><img src="http://graph.facebook.com/<?php echo $fb_user->facebook_id;  ?>/picture?type=large"></div>
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
