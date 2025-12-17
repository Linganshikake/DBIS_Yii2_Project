<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SeasonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Seasons';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="season-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Season', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            
            'name',
            'start_date',
            'end_date',
            'is_current',
            //'display_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
