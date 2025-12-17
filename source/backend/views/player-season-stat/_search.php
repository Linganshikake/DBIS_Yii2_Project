<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlayerSeasonStatSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="player-season-stat-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'player_id') ?>

    <?= $form->field($model, 'season_id') ?>

    <?= $form->field($model, 'team_id') ?>

    <?= $form->field($model, 'games_count') ?>

    <?php // echo $form->field($model, 'total_score') ?>

    <?php // echo $form->field($model, 'rank_1_count') ?>

    <?php // echo $form->field($model, 'rank_2_count') ?>

    <?php // echo $form->field($model, 'rank_3_count') ?>

    <?php // echo $form->field($model, 'rank_4_count') ?>

    <?php // echo $form->field($model, 'max_score') ?>

    <?php // echo $form->field($model, 'avg_rank') ?>

    <?php // echo $form->field($model, 'top_rate') ?>

    <?php // echo $form->field($model, 'last_avoid_rate') ?>

    <?php // echo $form->field($model, 'display_status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
