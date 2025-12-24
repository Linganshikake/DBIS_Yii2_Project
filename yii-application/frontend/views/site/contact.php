<?php
/** @var yii\web\View $this */
/** @var yii\base\DynamicModel $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = '反馈/征集';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(<<<CSS
.contact-scope .white-card{
  background:#fff;
  border:1px solid rgba(0,0,0,.06);
  border-radius: 18px;
  box-shadow: 0 12px 30px rgba(0,0,0,.06);
}
.contact-scope .section-title{
  font-weight: 900;
  letter-spacing: .6px;
  margin: 0 0 10px;
  color: var(--ink);
}
.contact-scope .sub{ color: var(--muted); font-weight: 600; }
.contact-scope .hint{
  padding: 12px 14px;
  border-radius: 14px;
  background: rgba(122,15,27,.06);
  border: 1px solid rgba(122,15,27,.14);
  color: var(--ink);
  font-weight: 650;
}
CSS);
?>

<div class="contact-scope page-wrap">
  <div class="container my-4">

    <div class="white-card p-4 p-md-5 mb-3">
      <h1 class="section-title mb-1"><?= Html::encode($this->title) ?></h1>
      <div class="sub mb-3">
        用于记录问题与需求：检索异常、字段缺失、页面显示问题、希望增加筛选/排序/导出等功能。
      </div>

      <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success mb-3">
          <?= Yii::$app->session->getFlash('success') ?>
        </div>
      <?php endif; ?>

      <div class="hint mb-3">
        建议写法：<b>操作步骤</b> → <b>实际结果</b>（可截图）→ <b>期望结果</b>（你希望怎么表现）
      </div>

      <?php $form = ActiveForm::begin([
          'method' => 'post',
          'options' => ['class' => 'row g-3']
      ]); ?>

        <div class="col-12 col-md-4">
          <?= $form->field($model, 'category')->dropDownList([
              'feedback' => '纠错反馈（错误/缺失）',
              'collect'  => '资料征集（提交新文献）',
              'feature'  => '功能建议（体验优化）',
          ], ['prompt' => '请选择类型'])->label('反馈类型') ?>
        </div>

        <div class="col-12 col-md-4">
          <?= $form->field($model, 'name')->textInput([
              'maxlength' => true,
              'placeholder' => '可选：你的称呼'
          ])->label('称呼（可选）') ?>
        </div>

        <div class="col-12 col-md-4">
          <?= $form->field($model, 'email')->textInput([
              'maxlength' => true,
              'placeholder' => '可选：便于回复（不强制）'
          ])->label('邮箱（可选）') ?>
        </div>

        <div class="col-12">
          <?= $form->field($model, 'related')->textInput([
              'maxlength' => true,
              'placeholder' => '例如：文献ID=785 / DOI=10.xxx / 或直接粘贴详情页链接 / 或题名关键字'
          ])->label('关联对象（建议填写）') ?>
        </div>

        <div class="col-12">
          <?= $form->field($model, 'message')->textarea([
              'rows' => 6,
              'placeholder' => "请描述：\n1）你做了什么操作\n2）你看到的结果\n3）你期望的结果\n（可附上错误提示/截图说明）"
          ])->label('详细说明（必填）') ?>
        </div>

        <!-- 如果你想启用验证码，把 controller 里 captcha 规则打开，并取消下面注释 -->
        <?php /*
        <div class="col-12 col-md-4">
          <?= $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::class)->label('验证码') ?>
        </div>
        */ ?>

        <div class="col-12 d-flex gap-2">
          <?= Html::submitButton('提交', ['class' => 'btn btn-brand']) ?>
          <?= Html::a('返回文献检索', ['/work/index'], ['class' => 'btn btn-outline-secondary']) ?>
        </div>

      <?php ActiveForm::end(); ?>

      <hr class="my-4">

      <div class="row g-3">
        <div class="col-lg-4">
          <div class="white-card p-4 h-100" style="box-shadow:none;">
            <h5 class="mb-2" style="font-weight:900;">纠错反馈</h5>
            <div class="sub">字段缺失、显示错乱、DOI 打不开、检索结果异常等。</div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="white-card p-4 h-100" style="box-shadow:none;">
            <h5 class="mb-2" style="font-weight:900;">资料征集</h5>
            <div class="sub">欢迎提交：题名、作者、年份、类型、来源、DOI、摘要、关键词、外链。</div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="white-card p-4 h-100" style="box-shadow:none;">
            <h5 class="mb-2" style="font-weight:900;">功能建议</h5>
            <div class="sub">例如：高级检索、导出、引用格式、收藏、分享、批量导入等。</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
