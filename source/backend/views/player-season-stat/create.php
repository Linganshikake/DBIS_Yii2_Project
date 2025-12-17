<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PlayerSeasonStat */

$this->title = '增加新的选手赛季成绩';
$this->params['breadcrumbs'][] = ['label' => 'Player Season Stats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="player-season-stat-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
