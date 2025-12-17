<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlayerSeasonStatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Player Season Stats';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="player-season-stat-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Player Season Stat', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => '排名',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    // 计算公式：当前页码 * 每页条数 + 当前行索引 + 1
                    $pagination = $column->grid->dataProvider->pagination;
                    return $pagination->page * $pagination->pageSize + $index + 1;
                },
                'headerOptions' => ['width' => '60px'], // 设置宽度
            ],

            [
                'attribute' => 'player_id',
                'label' => '选手姓名',
                'value' => function($model) {
                    return $model->player->name; // 这里的写法稍微不同，用匿名函数更安全
                },
            ],
            [
                'attribute' => 'season_id',
                'label' => '赛季',
                'value' => function($model) {
                    return $model->season->name;
                },
            ],
            [
                'attribute' => 'team_id',
                'label' => '所属队伍',
                'value' => function($model) {
                    return $model->team->name;
                },
            ],
            'games_count',
            'total_score',
            //'rank_1_count',
            //'rank_2_count',
            //'rank_3_count',
            //'rank_4_count',
            //'max_score',
            //'avg_rank',
            //'top_rate',
            //'last_avoid_rate',
            //'display_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
