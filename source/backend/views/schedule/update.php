<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Schedule */

$this->title = '更新日程: ' . $model->match_date;
$this->params['breadcrumbs'][] = ['label' => '日程管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->match_date, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="schedule-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'teams' => $teams,
        'seasons' => $seasons,
    ]) ?>

</div>
