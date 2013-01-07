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
				<ul>
				<?php
					foreach($cats as $c) {
						if($c) {
							echo "<li>" .$c ."</li>";
						}
					}
				?>
				</ul>
			</div>
			
			<div>
				<h4>Places Visited</h4>
								<?php
					foreach($previous as $p) {
						if($c) {
							echo "<li>" .$p ."</li>";
						}
					}
				?>
			</div>
		</div>
		<div class="span3">
			<!-- Facebook profile picture -->
			<p>
				<img src="http://graph.facebook.com/<?php if($fb_user) {echo $fb_user->facebook_id;}  ?>/picture?type=large">
			</p>
			<p>
				<?php if(!$fb_user) { ?>
					<strong> <a href="index.php?r=user/facebookconnect"> Connect my Facebook Profile </a> </strong>
				<?php } else { ?>
					<strong> Disconnect my Facebook Profile </strong>
				<?php } ?>
			</p>
		</div>
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
