<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlayerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Players';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="player-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Player', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            
            'name',
            'register_name',
            'gender',
            'nickname',
            //'team_id',
            //'org_id',
            //'org_rank',
            //'join_date',
            //'display_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
