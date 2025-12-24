<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Work;

/** @var yii\web\View $this */
/** @var common\models\Work[] $recommend */

$this->title = '抗战80周年文献库';

/**
 * 4 个快捷入口（点了直接跳文献检索并带类型筛选）
 * 注意：key 必须是 works.work_type 里存的值
 */
$quickTypes = [
    '图书' => 'book',
    '期刊' => 'journal-article',
    '报纸' => 'newspaper-article',
    '档案' => 'archive',
];

// 类型下拉：直接复用 Work::workTypeMap()，并补一个“全部”
$typeOptions = ['' => '全部'] + Work::workTypeMap();
?>

<div class="hero">
  <div class="container hero-inner">
    <div class="hero-kicker">庆祝抗日战争胜利80周年！</div>

    <h1 class="hero-title">
      抗日战争及后续研究<br>
      <span>文献数据库平台</span>
    </h1>

    <p class="hero-desc">
      支持题名/摘要关键词检索、类型筛选与年份排序；可查看文献详情、作者、关键词与外链/附件。
    </p>

    <div class="hero-search">

      <?php $form = ActiveForm::begin([
          'action' => ['/work/index'],
          'method' => 'get',
          'options' => ['class' => 'row g-2 align-items-center'],
      ]); ?>

        <div class="col-md-8">
          <?= Html::textInput('WorkSearch[global]', Yii::$app->request->get('WorkSearch')['global'] ?? '', [
              'class' => 'form-control form-control-lg',
              'placeholder' => '请输入检索内容（题名 / 摘要 / 作者 / 关键词）',
              'autocomplete' => 'off',
          ]) ?>
        </div>

        <div class="col-md-2">
          <?= Html::dropDownList(
              'WorkSearch[work_type]',
              Yii::$app->request->get('WorkSearch')['work_type'] ?? '',
              $typeOptions,
              ['class' => 'form-select form-select-lg']
          ) ?>
        </div>

        <div class="col-md-2 d-grid">
          <?= Html::submitButton('检索', ['class' => 'btn btn-brand btn-lg']) ?>
        </div>

      <?php ActiveForm::end(); ?>

      <div class="hero-tags mt-2">
        <?php foreach ($quickTypes as $label => $typeKey): ?>
          <?= Html::a(
              Html::encode($label),
              ['/work/index', 'WorkSearch' => ['work_type' => $typeKey]],
              ['class' => 'tag-pill']
          ) ?>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</div>

<div class="container my-4">
  <h2 class="section-title">使用指引</h2>
  <div class="row g-3">
    <div class="col-md-4">
      <div class="feature-card">
        <h5>快速检索</h5>
        <p>输入关键词并选择类型，即可跳转到检索页查看结果列表。</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="feature-card">
        <h5>结果排序</h5>
        <p>在“文献检索”页点击表头即可按题名/类型/年份/入库时间排序。</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="feature-card">
        <h5>详情查看</h5>
        <p>进入详情页可查看作者、关键词以及外链/附件。</p>
      </div>
    </div>
  </div>

  <?php if (!empty($recommend)): ?>
    <h2 class="section-title mt-4">特别推荐</h2>
    <div class="row g-3">
      <?php foreach ($recommend as $w): ?>
        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <h5 style="margin-bottom:.5rem;">
              <?= Html::a(Html::encode($w->title), ['/work/view','id'=>$w->id], [
                  'style' => 'color:var(--brand); text-decoration:none;'
              ]) ?>
            </h5>
            <p style="margin-bottom:.6rem;">
              类型：<?= Html::encode(method_exists($w, 'getWorkTypeText') ? $w->getWorkTypeText() : ($w->work_type ?: '—')) ?>
              &nbsp;|&nbsp; 年份：<?= Html::encode($w->publication_year ?: '—') ?>
            </p>
            <div>
              <?= Html::a('打开详情', ['/work/view','id'=>$w->id], ['class'=>'btn btn-brand btn-sm']) ?>
              <?= Html::a('去检索页', ['/work/index'], ['class'=>'btn btn-outline-secondary btn-sm']) ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
