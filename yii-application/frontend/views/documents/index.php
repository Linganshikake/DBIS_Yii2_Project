<?php
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = '抗日文献资料数据';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_list_item',
    'layout' => "{items}\n<div class='mt-4'>{pager}</div>",
    'emptyText' => '<p>暂无文献数据</p>',
    'options' => ['class' => 'documents-list row g-3'],
]) ?>
