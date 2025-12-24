<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\LoginForm $model */

$this->title = 'Login';
?>

<div class="login-wrap">
  <div class="login-card">
    <div class="login-head">
      <div class="login-brand">
        <span class="dot"></span>
        <div style="font-weight:900; letter-spacing:.5px;">抗战80周年文献库 · 后台</div>
      </div>

      <h1 class="login-title">Login</h1>
      <p class="login-sub">Please fill out the following fields to login:</p>
    </div>

    <div class="login-body">
      <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form->field($model, 'username')
            ->textInput(['autofocus' => true, 'placeholder' => 'Username'])
        ?>

        <?= $form->field($model, 'password')
            ->passwordInput(['placeholder' => 'Password'])
        ?>

        <?= $form->field($model, 'rememberMe')->checkbox() ?>

        <div class="d-grid">
          <?= Html::submitButton('Login', ['class' => 'btn btn-login']) ?>
        </div>

      <?php ActiveForm::end(); ?>

      <div class="login-foot">
        Powered by Yii2 · Backend Console
      </div>
    </div>
  </div>
</div>
