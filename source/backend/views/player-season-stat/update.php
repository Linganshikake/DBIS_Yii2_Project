<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PlayerSeasonStat */

$this->title = 'Update Player Season Stat: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Player Season Stats', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="player-season-stat-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
