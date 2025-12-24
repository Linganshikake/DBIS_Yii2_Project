<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var frontend\models\SignupForm $model */

$this->title = '注册';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container my-4" style="max-width:520px;">
  <div class="panel white-card p-4">
    <h3 class="mb-3" style="font-weight:900;"><?= Html::encode($this->title) ?></h3>
    <div class="text-muted mb-3">填写信息后将自动登录。</div>

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

      <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
      <?= $form->field($model, 'password')->passwordInput() ?>

      <div class="d-grid">
        <?= Html::submitButton('注册并登录', ['class' => 'btn btn-brand btn-lg']) ?>
      </div>

      <div class="mt-3 text-muted">
        已有账号？<?= Html::a('去登录', ['/site/login'], ['class' => 'text-decoration-none']) ?>
      </div>

    <?php ActiveForm::end(); ?>
  </div>
</div>
