<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var int $totalWorks */
/** @var int $worksToday */
/** @var array $typeCounts */
/** @var \common\models\Work[] $latestWorks */

$this->title = '后台首页';
?>

<div class="admin-hero mb-4">
  <div class="kicker">抗战80周年文献库 · 后台管理</div>
  <div class="title">管理控制台</div>
  <p class="desc">
    在这里你可以完成：文献数据的新增/编辑/删除、字段维护、检索效果检查，以及后台权限登录管理。
  </p>

  <div class="d-flex flex-wrap gap-2 mt-3">
    <?= Html::a('进入文献管理', ['/work/index'], ['class' => 'btn btn-outline-brand']) ?>
    <?= Html::a('只读文献展示', ['/work/display'], ['class' => 'btn btn-outline-brand']) ?>
    <?= Html::a('新增文献', ['/work/create'], ['class' => 'btn btn-brand']) ?>
  </div>
</div>

<div class="row g-3">
  <!-- 左侧：统计 + 功能 -->
  <div class="col-lg-7">
    <div class="white-card p-4 mb-3">
      <h3 class="card-title-strong">核心统计</h3>
      <div class="subtle mb-3">快速掌握当前数据规模与新增情况</div>

      <div class="row g-3">
        <div class="col-md-6">
          <div class="mini-stat">
            <div>
              <div class="label">文献总量</div>
              <div class="num"><?= (int)$totalWorks ?></div>
            </div>
            <div class="subtle">条</div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mini-stat">
            <div>
              <div class="label">今日新增</div>
              <div class="num"><?= (int)$worksToday ?></div>
            </div>
            <div class="subtle">条</div>
          </div>
        </div>
      </div>
    </div>

    <div class="white-card p-4">
      <h3 class="card-title-strong">后台功能说明</h3>
      <div class="subtle mb-3">面向维护人员的日常操作入口</div>

      <div class="row g-3">
        <div class="col-md-6">
          <div class="p-3" style="border:1px solid rgba(17,24,39,.10); border-radius:16px; background:rgba(255,255,255,.85);">
            <div style="font-weight:900; margin-bottom:6px;">文献管理（CRUD）</div>
            <div class="subtle" style="font-weight:700;">
              支持新增/编辑/删除文献；用于维护题名、类型、年份、来源、DOI、摘要等字段。
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-3" style="border:1px solid rgba(17,24,39,.10); border-radius:16px; background:rgba(255,255,255,.85);">
            <div style="font-weight:900; margin-bottom:6px;">文献展示（只读）</div>
            <div class="subtle" style="font-weight:700;">
              快速核对检索与排序效果，适合演示展示，不进行修改操作。
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-3" style="border:1px solid rgba(17,24,39,.10); border-radius:16px; background:rgba(255,255,255,.85);">
            <div style="font-weight:900; margin-bottom:6px;">权限登录</div>
            <div class="subtle" style="font-weight:700;">
              仅登录用户可进入后台操作；
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-3" style="border:1px solid rgba(17,24,39,.10); border-radius:16px; background:rgba(255,255,255,.85);">
            <div style="font-weight:900; margin-bottom:6px;">数据质量检查</div>
            <div class="subtle" style="font-weight:700;">
              通过“来源/DOI/年份/类型”筛选，检查字段缺失与异常格式，方便修复。
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- 右侧：类型分布 + 最近新增 -->
  <div class="col-lg-5">
    <div class="white-card p-4 mb-3">
      <h3 class="card-title-strong">类型分布（Top）</h3>
      <div class="subtle mb-3">用于了解当前库中主要文献类型构成</div>

      <?php
        $max = 0;
        foreach ($typeCounts as $row) $max = max($max, (int)($row['c'] ?? 0));
        $max = max(1, $max);
      ?>

      <?php if (empty($typeCounts)): ?>
        <div class="subtle">暂无数据</div>
      <?php else: ?>
        <?php foreach (array_slice($typeCounts, 0, 6) as $row): ?>
          <?php
            $type = trim((string)($row['work_type'] ?? '未填写'));
            $c = (int)($row['c'] ?? 0);
            $pct = (int)round($c * 100 / $max);
          ?>
          <div class="mb-2">
            <div class="d-flex justify-content-between">
              <div style="font-weight:900;"><?= Html::encode($type === '' ? '未填写' : $type) ?></div>
              <div class="subtle"><?= $c ?> 条</div>
            </div>
            <div class="progress">
              <div class="progress-bar" style="width: <?= $pct ?>%;"></div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="white-card p-4">
      <h3 class="card-title-strong">最近新增</h3>
      <div class="subtle mb-3">最近入库的文献，便于快速复核</div>

      <?php if (empty($latestWorks)): ?>
        <div class="subtle">暂无数据</div>
      <?php else: ?>
        <div class="list-group">
          <?php foreach ($latestWorks as $w): ?>
            <a class="list-group-item list-group-item-action"
               href="<?= Html::encode(\yii\helpers\Url::to(['/work/view','id'=>$w->id])) ?>"
               style="border:1px solid rgba(17,24,39,.10); border-radius:14px; margin-bottom:10px;">
              <div style="font-weight:900; color: var(--brand);">
                <?= Html::encode($w->title ?: '(无题名)') ?>
              </div>
              <div class="subtle" style="margin-top:4px;">
                <?= Html::encode($w->work_type ?: '—') ?> · <?= Html::encode($w->publication_year ?: '—') ?> · ID <?= (int)$w->id ?>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <div class="d-flex gap-2 mt-2">
        <?= Html::a('查看全部', ['/work/index'], ['class' => 'btn btn-brand']) ?>
        <?= Html::a('只读展示', ['/work/display'], ['class' => 'btn btn-outline-dark']) ?>
      </div>
    </div>
  </div>
</div>
