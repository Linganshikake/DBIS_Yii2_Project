<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Work $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="work-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title_alt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'work_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'publication_year')->textInput() ?>

    <?= $form->field($model, 'publication_date')->input('date') ?>

    <?= $form->field($model, 'language')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'abstract')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'source_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'openalex_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'crossref_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'license')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
