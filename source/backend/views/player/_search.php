<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlayerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="player-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'register_name') ?>

    <?= $form->field($model, 'gender') ?>

    <?= $form->field($model, 'nickname') ?>

    <?php // echo $form->field($model, 'team_id') ?>

    <?php // echo $form->field($model, 'org_id') ?>

    <?php // echo $form->field($model, 'org_rank') ?>

    <?php // echo $form->field($model, 'join_date') ?>

    <?php // echo $form->field($model, 'display_status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
