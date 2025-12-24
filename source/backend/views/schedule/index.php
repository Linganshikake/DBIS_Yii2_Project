<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the backend schedule index view (后台赛程管理列表视图)
 */
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the backend view for schedule index (赛程管理列表视图)
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScheduleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '日程管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('创建日程', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'match_date',
            'day_of_week',
            [
                'attribute' => 'team_id1',
                'value' => function($model) {
                    return $model->team1 ? $model->team1->name : '-';
                },
            ],
            [
                'attribute' => 'team_id2',
                'value' => function($model) {
                    return $model->team2 ? $model->team2->name : '-';
                },
            ],
            [
                'attribute' => 'team_id3',
                'value' => function($model) {
                    return $model->team3 ? $model->team3->name : '-';
                },
            ],
            [
                'attribute' => 'team_id4',
                'value' => function($model) {
                    return $model->team4 ? $model->team4->name : '-';
                },
            ],
            [
                'attribute' => 'match_status',
                'value' => function($model) {
                    return $model->getStatusText();
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
