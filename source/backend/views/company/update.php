<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Company */

$teamName = $model->team->name ?? '企业 #' . $model->id;
$this->title = '编辑: ' . $teamName;
$this->params['breadcrumbs'][] = ['label' => '企业管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $teamName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="company-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
