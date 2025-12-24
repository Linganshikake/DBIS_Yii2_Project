<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Company */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'team_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(\common\models\Team::find()->all(), 'id', 'name'),
        ['prompt' => '请选择队伍']
    ) ?>

    <?= $form->field($model, 'e_mail')->textInput(['maxlength' => true, 'placeholder' => '企业联系邮箱']) ?>

    <?= $form->field($model, 'web')->textInput(['maxlength' => true, 'placeholder' => '企业官网URL']) ?>

    <?= $form->field($model, 'logoFile')->fileInput() ?>
    
    <?php if ($model->logo): ?>
        <div class="form-group mb-3">
            <label>当前Logo</label><br>
            <img src="/uploads/company/<?= $model->logo ?>" style="max-width: 150px;">
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'display_status')->dropDownList([
        1 => '显示',
        0 => '隐藏',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
