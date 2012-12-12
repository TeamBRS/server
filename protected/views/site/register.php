<?php
/* @var $this SiteController */
/* @var $model RegisterForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - register';
$this->breadcrumbs=array(
        'Register',
);
?>

<h1>Tell us about yourself...</h1>

<?php if(Yii::app()->user->hasFlash('Register')): ?>

<div class="flash-success">
        <?php echo Yii::app()->user->getFlash('Register'); ?>
</div>

<?php else: ?>

<p>
Here at GastroNoNo, we want to know more about you and what you want from eating out!
</p>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm'); ?>

        <p class="note">Information marked <span class="required"> * </span> helps us get a better idea of what to recommend.</p>

        <?php echo $form->errorSummary($model, 'We still need the following information:'); ?>
       
        <div class="row">
                <?php echo $form->labelEx($model,'username'); ?>
                <?php echo $form->textField($model,'username'); ?>
        </div>

        <div class="row">
                <?php echo $form->labelEx($model,'password'); ?>
                <?php echo $form->passwordField($model,'password'); ?>
        </div>

        <div class="row">
                <?php echo $form->labelEx($model,'email'); ?>
                <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
        </div>

        <div class="row buttons">
                <?php echo CHtml::submitButton('Submit'); ?>
        </div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>
