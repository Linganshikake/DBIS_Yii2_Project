<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Schedule */

$this->title = '日程: ' . $model->match_date . ' ' . $model->day_of_week;
$this->params['breadcrumbs'][] = ['label' => '日程管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定要删除这个日程吗？',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('录入成绩', ['schedule-score/create', 'schedule_id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'match_date',
            'day_of_week',
            [
                'attribute' => 'season_id',
                'value' => $model->season ? $model->season->name : '-',
            ],
            [
                'attribute' => 'team_id1',
                'value' => $model->team1 ? $model->team1->name : '-',
            ],
            [
                'attribute' => 'team_id2',
                'value' => $model->team2 ? $model->team2->name : '-',
            ],
            [
                'attribute' => 'team_id3',
                'value' => $model->team3 ? $model->team3->name : '-',
            ],
            [
                'attribute' => 'team_id4',
                'value' => $model->team4 ? $model->team4->name : '-',
            ],
            [
                'attribute' => 'top_team_id',
                'value' => $model->topTeam ? $model->topTeam->name : '未定',
            ],
            [
                'attribute' => 'match_status',
                'value' => $model->getStatusText(),
            ],
            [
                'attribute' => 'display_status',
                'value' => $model->display_status ? '显示' : '隐藏',
            ],
        ],
    ]) ?>

    <h3>比赛成绩</h3>
    <?php if ($model->scheduleScores): ?>
        <?php foreach ($model->scheduleScores as $score): ?>
            <div class="panel panel-default" style="margin-top: 10px; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
                <h4><?= $score->getGameNumberText() ?></h4>
                <table class="table table-bordered">
                    <tr>
                        <th>队伍1 (<?= $model->team1->name ?>)</th>
                        <th>队伍2 (<?= $model->team2->name ?>)</th>
                        <th>队伍3 (<?= $model->team3->name ?>)</th>
                        <th>队伍4 (<?= $model->team4->name ?>)</th>
                    </tr>
                    <tr>
                        <td><?= $score->team1Player ? $score->team1Player->name : '-' ?></td>
                        <td><?= $score->team2Player ? $score->team2Player->name : '-' ?></td>
                        <td><?= $score->team3Player ? $score->team3Player->name : '-' ?></td>
                        <td><?= $score->team4Player ? $score->team4Player->name : '-' ?></td>
                    </tr>
                    <tr>
                        <td style="<?= $score->team1_score >= 0 ? 'color: green;' : 'color: red;' ?>"><?= $score->team1_score ?>pt</td>
                        <td style="<?= $score->team2_score >= 0 ? 'color: green;' : 'color: red;' ?>"><?= $score->team2_score ?>pt</td>
                        <td style="<?= $score->team3_score >= 0 ? 'color: green;' : 'color: red;' ?>"><?= $score->team3_score ?>pt</td>
                        <td style="<?= $score->team4_score >= 0 ? 'color: green;' : 'color: red;' ?>"><?= $score->team4_score ?>pt</td>
                    </tr>
                </table>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>暂无成绩记录</p>
    <?php endif; ?>

</div>
