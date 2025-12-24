<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the backend view for news index (新闻管理列表视图)
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '新闻管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('创建新闻', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'publish_time',
            [
                'attribute' => 'is_hot',
                'value' => function($model) {
                    return $model->is_hot ? '是' : '否';
                },
                'filter' => ['0' => '否', '1' => '是'],
            ],
            'view_count',
            [
                'attribute' => 'display_status',
                'value' => function($model) {
                    return $model->display_status ? '显示' : '隐藏';
                },
                'filter' => ['0' => '隐藏', '1' => '显示'],
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
