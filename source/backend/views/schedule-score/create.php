<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ScheduleScore */

$this->title = '录入成绩';
$this->params['breadcrumbs'][] = ['label' => '成绩管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-score-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'schedules' => $schedules,
        'players' => $players,
    ]) ?>

</div>
