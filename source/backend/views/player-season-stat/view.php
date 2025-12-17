<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PlayerSeasonStat */


$this->title = $model->player->name . ' (' . $model->season->name . ')';
$this->params['breadcrumbs'][] = ['label' => 'Player Season Stats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="player-season-stat-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 实时计算排名 
            [
                'label' => '当前排名',
                'value' => function($model) {
                    // 逻辑：查找同赛季中，分数比我高的人数
                    $count = \common\models\PlayerSeasonStat::find()
                        ->where(['season_id' => $model->season_id]) // 必须是同赛季
                        ->andWhere(['>', 'total_score', $model->total_score]) // 分数比我高
                        ->count();
                    return '第 ' . ($count + 1) . ' 名';
                },
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
            'rank_1_count',
            'rank_2_count',
            'rank_3_count',
            'rank_4_count',
            'max_score',
            'avg_rank',
            'top_rate',
            'last_avoid_rate',
            'display_status',
        ],
    ]) ?>

</div>
