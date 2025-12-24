<?php
/** @var yii\web\View $this */
use yii\bootstrap5\Html;

$this->title = '项目说明';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(<<<CSS
.about-scope .white-card{
  background:#fff;
  border:1px solid rgba(0,0,0,.06);
  border-radius: 18px;
  box-shadow: 0 12px 30px rgba(0,0,0,.06);
}
.about-scope .section-title{
  font-weight: 900;
  letter-spacing: .6px;
  margin: 0 0 10px;
  color: var(--ink);
}
.about-scope .sub{
  color: var(--muted);
  font-weight: 600;
}
.about-scope .pill{
  display:inline-flex;
  align-items:center;
  padding:.35rem .75rem;
  border-radius:999px;
  background: rgba(122,15,27,.08);
  border: 1px solid rgba(122,15,27,.18);
  color: var(--brand);
  font-weight: 800;
  text-decoration:none;
}
.about-scope .pill:hover{ background: rgba(122,15,27,.12); color: var(--brand); }
CSS);
?>

<div class="about-scope page-wrap">
  <div class="container my-4">

    <div class="white-card p-4 p-md-5 mb-3">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
          <h1 class="section-title mb-1"><?= Html::encode($this->title) ?></h1>
          <div class="sub">抗战80周年主题文献检索与展示平台（课程作业演示）</div>
        </div>
        <div class="d-flex gap-2">
          <?= Html::a('进入文献检索', ['/work/index'], ['class'=>'pill']) ?>
          <?= Html::a('反馈/征集', ['/site/contact'], ['class'=>'pill']) ?>
        </div>
      </div>

      <hr>

      <p class="mb-2" style="color:var(--ink); font-weight:650;">
        本平台用于“抗战80周年”主题文献的检索与展示，支持关键词检索、类型/年份筛选、详情阅读与外链跳转。
      </p>

      <div class="row g-3 mt-1">
        <div class="col-md-6">
          <div class="white-card p-4 h-100" style="box-shadow:none;">
            <h5 class="mb-2" style="font-weight:900;">你可以做什么</h5>
            <ul class="mb-0" style="color:var(--muted); font-weight:600;">
              <li>在“文献检索”中按题名/摘要/来源/DOI进行关键词检索</li>
              <li>按文献类型、发表年份进行筛选，并可点击表头进行排序</li>
              <li>进入详情页查看作者、关键词、外链/附件（若有）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-6">
          <div class="white-card p-4 h-100" style="box-shadow:none;">
            <h5 class="mb-2" style="font-weight:900;">数据说明与免责声明</h5>
            <ul class="mb-0" style="color:var(--muted); font-weight:600;">
              <li>数据用于展示检索能力，不保证学术级准确性与完整性</li>
              <li>如需正式引用，请以原始数据库/期刊/档案馆来源为准</li>
              <li>前端不开放“新增/维护文献”，该功能属于后台管理</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-lg-4">
        <div class="white-card p-4 h-100">
          <h5 class="mb-2" style="font-weight:900;">检索能力</h5>
          <div style="color:var(--muted); font-weight:600;">
            支持关键词检索、类型/年份筛选、排序与分页浏览；详情页提供作者/关键词跳转到检索页。
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="white-card p-4 h-100">
          <h5 class="mb-2" style="font-weight:900;">页面风格</h5>
          <div style="color:var(--muted); font-weight:600;">
            采用酒红主色 + 米白背景 + 白色卡片分区，保证阅读舒适与信息层级清晰。
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="white-card p-4 h-100">
          <h5 class="mb-2" style="font-weight:900;">后续可扩展</h5>
          <div style="color:var(--muted); font-weight:600;">
            可增加：高级检索（字段组合）、导出（CSV/Excel）、收藏、引用格式（GB/T、APA）等。
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
