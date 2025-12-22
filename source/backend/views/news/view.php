<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '新闻管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定要删除这条新闻吗？',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'publish_time',
            [
                'attribute' => 'content',
                'format' => 'ntext',
            ],
            [
                'attribute' => 'cover',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->cover ? Html::img('/uploads/news/' . $model->cover, ['style' => 'max-width: 200px;']) : '无';
                },
            ],
            [
                'attribute' => 'images',
                'format' => 'raw',
                'value' => function($model) {
                    $images = $model->getImagesArray();
                    if (empty($images)) return '无';
                    $html = '';
                    foreach ($images as $img) {
                        $html .= Html::img('/uploads/news/images/' . $img, ['style' => 'max-width: 100px; margin: 5px;']);
                    }
                    return $html;
                },
            ],
            'view_count',
            [
                'attribute' => 'is_hot',
                'value' => $model->is_hot ? '是' : '否',
            ],
            [
                'attribute' => 'display_status',
                'value' => $model->display_status ? '显示' : '隐藏',
            ],
        ],
    ]) ?>

</div>
