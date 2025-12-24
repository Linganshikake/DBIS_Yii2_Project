<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the backend season index view (后台赛季管理列表视图)
 */
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the backend view for season index (赛季管理列表视图)
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SeasonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '赛季';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="season-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('增加新赛季', ['create'], ['class' => 'btn btn-success']) ?>
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
