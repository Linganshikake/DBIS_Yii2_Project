<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ScheduleScore */

$this->title = '成绩详情: #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '成绩管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$schedule = $model->schedule;
?>
<div class="schedule-score-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定要删除这条成绩吗？',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'schedule_id',
                'value' => $schedule ? $schedule->match_date . ' ' . $schedule->day_of_week : '-',
            ],
            [
                'attribute' => 'game_number',
                'value' => $model->getGameNumberText(),
            ],
            [
                'label' => $schedule && $schedule->team1 ? $schedule->team1->name : '队伍1',
                'value' => ($model->team1Player ? $model->team1Player->name : '-') . ' : ' . $model->team1_score . 'pt',
            ],
            [
                'label' => $schedule && $schedule->team2 ? $schedule->team2->name : '队伍2',
                'value' => ($model->team2Player ? $model->team2Player->name : '-') . ' : ' . $model->team2_score . 'pt',
            ],
            [
                'label' => $schedule && $schedule->team3 ? $schedule->team3->name : '队伍3',
                'value' => ($model->team3Player ? $model->team3Player->name : '-') . ' : ' . $model->team3_score . 'pt',
            ],
            [
                'label' => $schedule && $schedule->team4 ? $schedule->team4->name : '队伍4',
                'value' => ($model->team4Player ? $model->team4Player->name : '-') . ' : ' . $model->team4_score . 'pt',
            ],
            [
                'attribute' => 'display_status',
                'value' => $model->display_status ? '显示' : '隐藏',
            ],
        ],
    ]) ?>

</div>
