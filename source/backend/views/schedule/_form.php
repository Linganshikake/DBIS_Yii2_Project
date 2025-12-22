<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Schedule */
/* @var $form yii\widgets\ActiveForm */
/* @var $teams array */
/* @var $seasons array */
?>

<div class="schedule-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'match_date')->textInput(['type' => 'date']) ?>

    <?= $form->field($model, 'season_id')->dropDownList($seasons, ['prompt' => '选择赛季']) ?>

    <?= $form->field($model, 'team_id1')->dropDownList($teams, ['prompt' => '选择队伍1']) ?>

    <?= $form->field($model, 'team_id2')->dropDownList($teams, ['prompt' => '选择队伍2']) ?>

    <?= $form->field($model, 'team_id3')->dropDownList($teams, ['prompt' => '选择队伍3']) ?>

    <?= $form->field($model, 'team_id4')->dropDownList($teams, ['prompt' => '选择队伍4']) ?>

    <?= $form->field($model, 'match_status')->dropDownList([
        0 => '未开始',
        1 => '进行中',
        2 => '已结束',
    ]) ?>

    <?= $form->field($model, 'top_team_id')->dropDownList($teams, ['prompt' => '选择首位队伍（可选）']) ?>

    <?= $form->field($model, 'display_status')->dropDownList(['0' => '隐藏', '1' => '显示']) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
