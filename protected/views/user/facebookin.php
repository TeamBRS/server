<?php
/* @var $this FacebookController */

$this->breadcrumbs=array(
	'Facebook',
);
?>
<h2>Thank you, <?php echo $facebook_name; ?>, you are now authenticated!</h2>

<p>Please wait while we redirect you to your profile page...</p>

<script type="text/javascript">
<!--
window.location = "index.php?r=user/view&id=<?php echo $gnn_user->id ?>"
//-->
</script>

