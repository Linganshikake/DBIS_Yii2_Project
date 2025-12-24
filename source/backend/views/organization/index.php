<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the backend organization index view (后台组织/单位管理列表视图)
 */
/**
 * Team: DBIS_Yii_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the backend view for organization index (麻将团体管理列表视图)
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrganizationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '职业团体';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Organization', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            
            'name',
            'top_title_name',
            //'display_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
