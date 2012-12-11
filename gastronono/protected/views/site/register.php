<?php
/* @var $this SiteController */
/* @var $model RegisterForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - register';
$this->breadcrumbs=array(
        'Register',
);
?>

<h1>Påmelding</h1>

<?php if(Yii::app()->user->hasFlash('Register')): ?>

<div class="flash-success">
        <?php echo Yii::app()->user->getFlash('Register'); ?>
</div>

<?php else: ?>

<p>
Fyll in din information her.
</p>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm'); ?>

        <p class="note">Felt med <span class="required">*</span> må fylles in.</p>

        <?php echo $form->errorSummary($model, 'Vennligst endre føljende feil:'); ?>
       
        <div class="row">
                <?php echo $form->labelEx($model,'username'); ?>
                <?php echo $form->dropDownList($model,'username', array(''=>'-- Velg by --', 'Oslo'=>'Oslo','Bergen'=>'Bergen','Hamar'=>'Hamar')); ?>
        </div>

        <div class="row">
                <?php echo $form->labelEx($model,'password'); ?>
                <?php echo $form->dropDownList($model,'password', array('Nej, takk'=>'Nej, takk','Enkeltrom'=>'Enkeltrom','Dobbeltrom'=>'Dobbeltrom')); ?>
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
