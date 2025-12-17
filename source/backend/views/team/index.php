<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 您的姓名 (学号), 2025xxxx
 * This is the controller class for table "teams".
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Teams';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Team', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            
            'name',
            'supervisor',
            'company',
            'description:ntext',
            //'display_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
