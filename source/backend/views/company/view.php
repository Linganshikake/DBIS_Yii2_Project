<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Company */

$this->title = $model->team->name ?? '企业 #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '企业管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定要删除吗？',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'logo',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->logo 
                        ? Html::img('@web/uploads/company/' . $model->logo, ['style' => 'max-width: 200px;'])
                        : '-';
                },
            ],
            'description:ntext',
            'website:url',
            [
                'attribute' => 'display_status',
                'value' => $model->display_status ? '显示' : '隐藏',
            ],
        ],
    ]) ?>

</div>
