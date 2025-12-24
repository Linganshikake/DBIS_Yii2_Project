<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the backend schedule-score index view (后台赛程得分管理列表视图)
 */
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the backend view for schedule-score index (比赛成绩列表视图)
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScheduleScoreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '成绩管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-score-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('录入成绩', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'schedule_id',
                'value' => function($model) {
                    $schedule = $model->schedule;
                    return $schedule ? $schedule->match_date . ' ' . $schedule->day_of_week : '-';
                },
            ],
            [
                'attribute' => 'game_number',
                'value' => function($model) {
                    return $model->getGameNumberText();
                },
            ],
            'team1_score',
            'team2_score',
            'team3_score',
            'team4_score',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
