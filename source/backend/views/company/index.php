<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '企业管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('新增企业', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'team_id',
                'label' => '关联队伍',
                'value' => function ($model) {
                    return $model->team->name ?? '-';
                },
            ],
            'e_mail:email',
            [
                'attribute' => 'logo',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->logo 
                        ? Html::img('@web/uploads/company/' . $model->logo, ['style' => 'max-width: 80px;'])
                        : '-';
                },
            ],
            [
                'attribute' => 'display_status',
                'value' => function ($model) {
                    return $model->display_status ? '显示' : '隐藏';
                },
                'filter' => [1 => '显示', 0 => '隐藏'],
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
