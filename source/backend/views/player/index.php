<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the backend player index view (后台球员管理列表视图)
 */
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the backend view for player index (选手管理列表视图)
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlayerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '选手';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="player-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('增加新选手', ['create'], ['class' => 'btn btn-success']) ?>
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
