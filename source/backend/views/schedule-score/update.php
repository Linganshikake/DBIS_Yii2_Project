<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ScheduleScore */

$this->title = '更新成绩';
$this->params['breadcrumbs'][] = ['label' => '成绩管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="schedule-score-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'schedules' => $schedules,
        'players' => $players,
    ]) ?>

</div>
