<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Player;
use common\models\Season;
use common\models\Team;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\PlayerSeasonStat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="player-season-stat-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'player_id')->dropDownList(
    // 这里的 'name' 就是让界面显示名字的关键
    ArrayHelper::map(Player::find()->where(['display_status' => 1])->all(), 'id', 'name'),
    ['prompt' => '请选择选手...']
    ) ?>

    <?= $form->field($model, 'season_id')->textInput() ?>

    <?= $form->field($model, 'team_id')->textInput() ?>

    <?= $form->field($model, 'games_count')->textInput() ?>

    <?= $form->field($model, 'total_score')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rank_1_count')->textInput() ?>

    <?= $form->field($model, 'rank_2_count')->textInput() ?>

    <?= $form->field($model, 'rank_3_count')->textInput() ?>

    <?= $form->field($model, 'rank_4_count')->textInput() ?>

    <?= $form->field($model, 'max_score')->textInput() ?>

    <?= $form->field($model, 'avg_rank')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'top_rate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_avoid_rate')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
