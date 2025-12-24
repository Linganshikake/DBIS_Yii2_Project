<?php
use yii\helpers\Html;

/** @var frontend\models\Documents $model */
?>

<div class="col-md-4">
    <div class="card h-100 shadow-sm">
        <?php if ($model->image): ?>
            <div class="mb-3 text-center">
                <?= Html::img("@web/{$model->image}", ['class' => 'img-fluid rounded shadow-sm', 'alt' => $model->title]) ?>
            </div>
        <?php endif; ?>
        <div class="card-body d-flex flex-column">
            <h5 class="card-title">
                <?= Html::a(Html::encode($model->title), ['view', 'id' => $model->id], ['class' => 'text-decoration-none']) ?>
            </h5>
            <h6 class="card-subtitle mb-2 text-muted">
                <?= Html::encode($model->category) ?> | <?= Html::encode($model->year) ?> | <?= Html::encode($model->source) ?>
            </h6>
            <p class="card-text flex-grow-1"><?= Html::encode(mb_substr($model->summary, 0, 120)) ?>...</p>
            <?= Html::a('查看详情', ['view', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary mt-auto']) ?>
        </div>
    </div>
</div>
