<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the backend schedule-score form partial view (后台赛程得分表单片段视图)
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ScheduleScore */
/* @var $form yii\widgets\ActiveForm */
/* @var $schedules array */
/* @var $players array */
?>

<div class="schedule-score-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'schedule_id')->dropDownList($schedules, ['prompt' => '选择日程']) ?>

    <?= $form->field($model, 'game_number')->dropDownList([
        0 => '第一回战',
        1 => '第二回战',
    ]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'team1_player_id')->dropDownList($players, ['prompt' => '选择队伍1选手']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'team1_score')->textInput(['type' => 'number', 'step' => '0.1']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'team2_player_id')->dropDownList($players, ['prompt' => '选择队伍2选手']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'team2_score')->textInput(['type' => 'number', 'step' => '0.1']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'team3_player_id')->dropDownList($players, ['prompt' => '选择队伍3选手']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'team3_score')->textInput(['type' => 'number', 'step' => '0.1']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'team4_player_id')->dropDownList($players, ['prompt' => '选择队伍4选手']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'team4_score')->textInput(['type' => 'number', 'step' => '0.1']) ?>
        </div>
    </div>

    <div class="alert alert-info">
        <strong>提示：</strong>四个队伍的得分之和必须为0
    </div>

    <?= $form->field($model, 'display_status')->dropDownList(['0' => '隐藏', '1' => '显示']) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
