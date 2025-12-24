<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->title ?: ('文献 #' . $model->id);
$this->params['breadcrumbs'][] = ['label' => '文献管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="work-view">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="m-0"><?= Html::encode($this->title) ?></h1>
    <div>
      <?= Html::a('编辑', ['update','id'=>$model->id], ['class'=>'btn btn-primary']) ?>
      <?= Html::a('删除', ['delete','id'=>$model->id], [
          'class'=>'btn btn-danger ms-2',
          'data' => ['confirm' => '确定删除？', 'method' => 'post']
      ]) ?>
    </div>
  </div>

  <?= DetailView::widget([
      'model' => $model,
      'attributes' => [
          'id',
          'title',
          'title_alt',
          'work_type',
          'publication_year',
          'language',
          'source_name',
          'doi',
          'url:url',
          'abstract:ntext',
          [
              'attribute' => 'created_at',
              'format' => ['date','php:Y-m-d H:i'],
          ],
          [
              'attribute' => 'updated_at',
              'format' => ['date','php:Y-m-d H:i'],
          ],
      ],
  ]); ?>
</div>
