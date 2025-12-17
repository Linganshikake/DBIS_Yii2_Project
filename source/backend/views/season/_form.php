<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Season */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="season-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_date')->input('date', ['class' => 'form-control']) ?>
    <?= $form->field($model, 'end_date')->input('date', ['class' => 'form-control']) ?>
    
    <?= $form->field($model, 'is_current')->textInput() ?>

    <?= $form->field($model, 'display_status')->dropDownList(
    [1 => '显示', 0 => '隐藏'], 
    ['prompt' => '请选择状态...']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
