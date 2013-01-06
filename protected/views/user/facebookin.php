<?php
/* @var $this FacebookController */

$this->breadcrumbs=array(
	'Facebook',
);
?>
<h1>Wow, you are now authenticated!</h1>

<marquee><h1><?php echo $facebook_name; ?></h1></marquee>

<?php var_dump($response); ?>

<h1>
<?php echo Yii::app()->user->getId(); ?>
</h1>

