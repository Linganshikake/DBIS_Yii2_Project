<?php
use yii\helpers\Html;

/** @var frontend\models\Documents $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '文献资料', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 class="mb-3"><?= Html::encode($this->title) ?></h1>

<?php if ($model->image): ?>
<div class="mb-3 text-center">
    <?= Html::img("@web/{$model->image}", ['class' => 'img-fluid rounded shadow-sm', 'alt' => $model->title]) ?>
</div>
<?php endif; ?>

<div class="mb-3 text-muted">
    <strong>类别：</strong><?= Html::encode($model->category) ?> |
    <strong>年代：</strong><?= Html::encode($model->year) ?> |
    <strong>来源：</strong><?= Html::encode($model->source) ?> |
    <strong>创建时间：</strong><?= date('Y-m-d H:i', $model->created_at) ?>
</div>

<hr>

<div>
    <?= Html::encode($model->content) ?>
</div>
