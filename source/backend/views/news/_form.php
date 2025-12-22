<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'publish_time')->textInput(['type' => 'datetime-local', 'value' => date('Y-m-d\TH:i', strtotime($model->publish_time ?: 'now'))]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 10]) ?>

    <?= $form->field($model, 'coverFile')->fileInput() ?>
    
    <?php if ($model->cover): ?>
        <div class="form-group">
            <label>当前封面</label><br>
            <img src="/uploads/news/<?= $model->cover ?>" style="max-width: 200px; max-height: 150px;">
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true]) ?>
    
    <?php if ($model->getImagesArray()): ?>
        <div class="form-group">
            <label>已上传图片</label><br>
            <?php foreach ($model->getImagesArray() as $img): ?>
                <img src="/uploads/news/images/<?= $img ?>" style="max-width: 100px; max-height: 100px; margin: 5px;">
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'is_hot')->dropDownList(['0' => '否', '1' => '是']) ?>

    <?= $form->field($model, 'display_status')->dropDownList(['0' => '隐藏', '1' => '显示']) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
