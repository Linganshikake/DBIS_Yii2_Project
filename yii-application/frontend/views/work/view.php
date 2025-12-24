<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Work $model */

$this->title = $model->title ?: '文献详情';
$this->params['breadcrumbs'][] = ['label' => '文献检索', 'url' => ['index']];
$this->params['breadcrumbs'][] = '详情';

$doi = trim((string)$model->doi);
$doiUrl = $doi ? (str_starts_with($doi,'http') ? $doi : 'https://doi.org/'.$doi) : null;

$url = trim((string)$model->url);
$urlBtn = $url ? $url : null;

$typeText = method_exists($model, 'getWorkTypeText') ? $model->getWorkTypeText() : ($model->work_type ?: '—');
$langText = method_exists($model, 'getLanguageText') ? $model->getLanguageText() : ($model->language ?: '—');
?>

<div class="work-page">
  <div class="container">

    <div class="work-hero-card">
      <div class="work-hero-top">
        <div class="work-title clamp-3"><?= Html::encode($model->title ?: '(无标题)') ?></div>

        <div class="work-meta-line">
          <span class="meta-chip">类型：<?= Html::encode($typeText) ?></span>
          <span class="meta-chip">年份：<?= Html::encode($model->publication_year ?: '—') ?></span>
          <span class="meta-chip">语种：<?= Html::encode($langText) ?></span>
        </div>

        <div class="work-actions">
          <?= Html::a('返回检索', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
          <?php if ($urlBtn): ?>
            <?= Html::a('原文链接', $urlBtn, ['class' => 'btn btn-outline-secondary', 'target'=>'_blank','rel'=>'noopener']) ?>
          <?php endif; ?>
          <?php if ($doiUrl): ?>
            <?= Html::a('DOI', $doiUrl, ['class' => 'btn btn-outline-secondary', 'target'=>'_blank','rel'=>'noopener']) ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="section-card">
      <div class="section-title">基本信息</div>
      <div class="kv-table">
        <div class="kv-row"><div class="k">编号</div><div class="v"><?= Html::encode($model->id) ?></div></div>
        <div class="kv-row"><div class="k">题名</div><div class="v"><?= Html::encode($model->title ?: '—') ?></div></div>
        <div class="kv-row"><div class="k">外文标题/别名</div><div class="v"><?= Html::encode($model->title_alt ?: '—') ?></div></div>
        <div class="kv-row"><div class="k">文献类型</div><div class="v"><?= Html::encode($typeText) ?></div></div>
        <div class="kv-row"><div class="k">发表年份</div><div class="v"><?= Html::encode($model->publication_year ?: '—') ?></div></div>
        <div class="kv-row"><div class="k">发表日期</div><div class="v"><?= Html::encode($model->publication_date ?: '—') ?></div></div>
        <div class="kv-row"><div class="k">语种</div><div class="v"><?= Html::encode($langText) ?></div></div>
        <div class="kv-row"><div class="k">来源/刊物</div><div class="v"><?= Html::encode($model->source_name ?: '—') ?></div></div>

        <div class="kv-row">
          <div class="k">DOI</div>
          <div class="v">
            <?php if ($doiUrl): ?>
              <?= Html::a(Html::encode($doi), $doiUrl, ['target'=>'_blank','rel'=>'noopener','class'=>'doi-link']) ?>
            <?php else: ?>
              —
            <?php endif; ?>
          </div>
        </div>

        <div class="kv-row">
          <div class="k">原文链接</div>
          <div class="v">
            <?php if ($urlBtn): ?>
              <?= Html::a(Html::encode($urlBtn), $urlBtn, ['target'=>'_blank','rel'=>'noopener','class'=>'doi-link']) ?>
            <?php else: ?>
              —
            <?php endif; ?>
          </div>
        </div>

        <div class="kv-row">
          <div class="k">摘要</div>
          <div class="v"><?= nl2br(Html::encode($model->abstract ?: '—')) ?></div>
        </div>
      </div>
    </div>

    <!-- 作者 -->
    <div class="work-card p-4 mb-4">
        <h3 class="sec-title mb-3">作者</h3>

        <div class="pill-wrap">
            <?php
                $authors = $model->authors ?? [];
                if (!empty($authors)) {
                    foreach ($authors as $a) {
                        $name = trim((string)($a->name ?? ''));
                        if ($name === '') continue;

                        echo \yii\helpers\Html::a(
                            \yii\helpers\Html::encode($name),
                            ['index', 'WorkSearch[global]' => $name],
                            ['class' => 'pill pill-author']
                        );
                    }
                } else {
                    echo '<span class="text-muted">暂无</span>';
                }
            ?>
        </div>
    </div>


    <!-- 关键词 -->
    <div class="work-card p-4 mb-4">
        <h3 class="sec-title mb-3">关键词</h3>

        <div class="pill-wrap">
            <?php
                $ks = array_map(fn($k) => $k->keyword, $model->keywords ?? []);
                if ($ks) {
                    foreach ($ks as $kw) {
                        echo \yii\helpers\Html::a(
                            \yii\helpers\Html::encode($kw),
                            ['index', 'WorkSearch[global]' => $kw],
                            ['class' => 'pill']
                        );
                    }
                } else {
                    echo '<span class="text-muted">暂无</span>';
                }
            ?>
        </div>
    </div>



    <div class="section-card">
      <div class="section-title">附件/外链</div>
      <?php if (!empty($model->files)): ?>
        <ul class="link-list">
          <?php foreach ($model->files as $f): ?>
            <li>
              <a target="_blank" rel="noopener" href="<?= Html::encode($f->url) ?>">
                <?= Html::encode($f->label ?: ($f->file_type ?: $f->url)) ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <div class="muted">暂无</div>
      <?php endif; ?>
    </div>

  </div>
</div>
