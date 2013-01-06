<?php if(Yii::app()->user->isGuest): ?>
    <div id="<?php echo $this->fbLoginButtonId; ?>"><?php echo $this->facebookButtonTitle; ?></div>
<?php endif; ?>