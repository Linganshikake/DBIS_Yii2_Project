<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Work;

/** @var common\models\Work $model */
?>

<div class="white-card p-4">
  <?php $form = ActiveForm::begin(); ?>

  <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
  <?= $form->field($model, 'title_alt')->textInput(['maxlength' => true]) ?>

  <?= $form->field($model, 'work_type')->dropDownList(
      method_exists(Work::class, 'workTypeMap') ? Work::workTypeMap() : [],
      ['prompt' => '请选择类型']
  ) ?>

  <?= $form->field($model, 'publication_year')->textInput() ?>
  <?= $form->field($model, 'language')->textInput(['maxlength' => true]) ?>
  <?= $form->field($model, 'source_name')->textInput(['maxlength' => true]) ?>
  <?= $form->field($model, 'doi')->textInput(['maxlength' => true]) ?>
  <?= $form->field($model, 'abstract')->textarea(['rows' => 6]) ?>

  <div class="d-flex gap-2">
    <?= Html::submitButton('保存', ['class' => 'btn btn-brand']) ?>
    <?= Html::a('返回列表', ['index'], ['class' => 'btn btn-outline-dark']) ?>
  </div>

  <?php ActiveForm::end(); ?>
</div>
