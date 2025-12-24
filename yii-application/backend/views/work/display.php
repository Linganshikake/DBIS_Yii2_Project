<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap5\LinkPager;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\base\Model $searchModel */

$this->title = '文献展示';
$this->params['breadcrumbs'][] = $this->title;

/** ✅ 每页数量（沿用 Yii 默认 pageSizeParam = per-page） */
$perPage = (int)Yii::$app->request->get('per-page', 20);
if (!in_array($perPage, [10, 20, 50, 100], true)) $perPage = 20;
if ($dataProvider->pagination !== false) {
    $dataProvider->pagination->pageSize = $perPage;
}

/** ✅ 先取总数（触发 totalCount 计算） */
$totalCount = (int)$dataProvider->getTotalCount();

/** ✅ 取分页对象 */
$pagination = $dataProvider->getPagination();

/** ✅ 手动算页码（避免 Pagination 的缓存坑） */
if ($pagination === false) {
    $pageSize  = $totalCount > 0 ? $totalCount : 1;
    $pageCount = 1;
    $page      = 1;
    $pageParam = 'page';
} else {
    $pageSize = (int)$pagination->getPageSize();
    if ($pageSize <= 0) $pageSize = $perPage;

    $pageCount = (int)ceil($totalCount / max(1, $pageSize));
    $pageCount = max(1, $pageCount);

    $pageParam = $pagination->pageParam; // 默认 page
    $reqPage0  = (int)Yii::$app->request->get($pageParam, 0); // 0-based
    $page      = max(1, min($reqPage0 + 1, $pageCount));
}

/** ✅ 页面样式（复用你 admin-theme.css 的变量：--brand/--muted/--ink） */
$this->registerCss(<<<CSS
.white-card{
  background:rgba(255,255,255,.92);
  border:1px solid rgba(0,0,0,.06);
  border-radius:18px;
  box-shadow:0 12px 30px rgba(0,0,0,.08);
  padding:18px;
}
.panel-title{ font-weight:900; font-size:30px; margin:0 0 6px; color:var(--ink); }
.panel-sub{ color:var(--muted); font-weight:700; margin-bottom:14px; }

.work-pager{ margin-top:14px; }
.work-pager .page-item{ margin:0 .18rem; }
.work-pager .page-link{
  border-radius:12px;
  border:1px solid rgba(0,0,0,.12);
  color:var(--ink);
  font-weight:800;
}
.work-pager .active .page-link{
  background:var(--brand);
  border-color:var(--brand);
  color:#fff;
}
.work-pager .disabled .page-link{
  pointer-events:none;
  opacity:.45;
}

.badge-type{
  display:inline-block;
  padding:.35rem .55rem;
  border-radius:999px;
  border:1px solid rgba(123,30,43,.22);
  background: rgba(123,30,43,.08);
  color: var(--brand);
  font-weight: 900;
}

.title-link{
  color: var(--brand);
  font-weight: 900;
  text-decoration: none;
}
.title-link:hover{
  text-decoration: underline;
  text-underline-offset: 5px;
}

.search-row .form-control,
.search-row .form-select{
  border-radius: 12px;
}
CSS);
?>

<div class="page-wrap">
  <div class="work-index work-scope">
    <div class="container">

      <!-- ✅ 白框 1：筛选区（独立搜索，替代 GridView filter row） -->
      <div class="panel white-card mb-3">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
          <div>
            <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            <div class="panel-sub">只读查看：用于快速浏览数据（支持筛选/排序/翻页）。</div>
          </div>
          <div class="d-flex gap-2">
            <?= Html::a('进入管理', ['/work/index'], ['class' => 'btn btn-sm btn-outline-dark']) ?>
          </div>
        </div>

        <?php
          // ✅ 注意：action 指向当前路由，不带 page 参数，提交后天然回到第一页
          echo Html::beginForm(['/work/display'], 'get', ['class' => 'search-row']);
        ?>
          <div class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
              <label class="form-label text-muted fw-semibold">关键词（标题/摘要等）</label>
              <?= Html::activeTextInput($searchModel, 'global', [
                'class' => 'form-control',
                'placeholder' => '如：抗战、华北、情报…（回车检索）'
              ]) ?>
            </div>

            <div class="col-6 col-md-2">
              <label class="form-label text-muted fw-semibold">类型</label>
              <?= Html::activeTextInput($searchModel, 'work_type', [
                'class' => 'form-control',
                'placeholder' => '如：article'
              ]) ?>
            </div>

            <div class="col-6 col-md-2">
              <label class="form-label text-muted fw-semibold">年份</label>
              <?= Html::activeTextInput($searchModel, 'publication_year', [
                'class' => 'form-control',
                'placeholder' => '如：1937'
              ]) ?>
            </div>

            <div class="col-6 col-md-2">
              <label class="form-label text-muted fw-semibold">每页数量</label>
              <?= Html::dropDownList('per-page', $perPage, [
                10 => '10', 20 => '20', 50 => '50', 100 => '100'
              ], ['class' => 'form-select']) ?>
            </div>

            <div class="col-6 col-md-2 d-flex gap-2">
              <?= Html::submitButton('检索', ['class' => 'btn btn-dark w-100']) ?>
              <?= Html::a('清空', ['/work/display'], ['class' => 'btn btn-outline-dark w-100']) ?>
            </div>
          </div>
        <?= Html::endForm(); ?>
      </div>

      <!-- ✅ 白框 2：统计/页码区 -->
      <div class="panel white-card mb-3 d-flex align-items-center justify-content-between">
        <div style="font-weight:900;">共 <?= $totalCount ?> 条结果</div>
        <div style="color:var(--muted); font-weight:900;">第 <?= $page ?> / <?= $pageCount ?> 页</div>
      </div>

      <!-- ✅ 白框 3：结果区 -->
      <div class="panel white-card">

        <?= GridView::widget([
          'dataProvider' => $dataProvider,

          // ✅ 关键：禁用 filter row（你截图里那排输入框就是它）
          'filterModel' => null,

          // ✅ summary 中文化
          'summary' => '显示第 <b>{begin}</b> - <b>{end}</b> 条，共 <b>{totalCount}</b> 条',
          'summaryOptions' => ['class' => 'text-muted fw-semibold mb-2'],

          'layout' => "{summary}\n{items}\n<div class='mt-2'>{pager}</div>",

          // ✅ Bootstrap5 LinkPager：自动禁用首/末页不可点
          'pager' => [
            'class' => LinkPager::class,
            'pagination' => $dataProvider->pagination,
            'options' => ['class' => 'pagination justify-content-center work-pager mb-0'],
            'firstPageLabel' => '首页',
            'lastPageLabel'  => '末页',
            'prevPageLabel'  => '上一页',
            'nextPageLabel'  => '下一页',
            'maxButtonCount' => 7,
            'disabledListItemSubTagOptions' => ['class' => 'page-link'],
          ],

          'tableOptions' => ['class' => 'table table-hover align-middle work-table'],
          'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => '#'],

            [
              'attribute' => 'id',
              'label' => '编号',
              'enableSorting' => true,
              'contentOptions' => ['style' => 'width:100px;'],
            ],
            [
              'attribute' => 'title',
              'label' => '标题',
              'format' => 'raw',
              'enableSorting' => true,
              'contentOptions' => ['style' => 'max-width:520px;'],
              'value' => function($m){
                $t = $m->title ?: '(无题名)';
                return Html::a(
                  Html::encode($t),
                  ['view', 'id' => $m->id],
                  [
                    'title' => $t,
                    'class' => 'title-link',
                    'style' => '
                      display:-webkit-box;
                      -webkit-line-clamp:2;
                      -webkit-box-orient:vertical;
                      overflow:hidden;
                      line-height:1.35;
                    '
                  ]
                );
              }
            ],
            [
              'attribute' => 'work_type',
              'label' => '文献类型',
              'format' => 'raw',
              'enableSorting' => true,
              'value' => function($m){
                $txt = $m->work_type ?: '未填写';
                return '<span class="badge-type">'.Html::encode($txt).'</span>';
              },
              'contentOptions' => ['style' => 'width:140px;'],
            ],
            [
              'attribute' => 'publication_year',
              'label' => '发表年份',
              'enableSorting' => true,
              'value' => fn($m) => $m->publication_year ?: '—',
              'contentOptions' => ['style' => 'width:120px;'],
            ],
            [
              'attribute' => 'source_name',
              'label' => '来源/刊物',
              'enableSorting' => true,
              'value' => fn($m) => $m->source_name ?: '—',
              'contentOptions' => ['style' => 'max-width:240px; white-space:normal;'],
            ],
            [
                'attribute' => 'doi',
                'label' => 'DOI',
                'format' => 'raw',
                'enableSorting' => true,
                'contentOptions' => ['style' => 'width:140px; white-space:nowrap;'],
                'value' => function($m){
                    $doi = trim((string)$m->doi);

                    // ✅ not set / 空 -> —
                    if ($doi === '' || strtolower($doi) === '(not set)' || strtolower($doi) === 'not set') {
                    return '—';
                    }

                    // 兼容：doi:xxxx 或 https://doi.org/xxxx
                    $pure = preg_replace('#^https?://(dx\.)?doi\.org/#i', '', $doi);
                    $pure = preg_replace('#^doi:\s*#i', '', $pure);
                    $url = 'https://doi.org/' . $pure;

                    return Html::a('打开', $url, [
                    'target' => '_blank',
                    'rel' => 'noopener',
                    'class' => 'btn btn-sm btn-outline-dark',
                    'title' => $pure,
                    ]);
                },
                ],

            [
              'label' => '操作',
              'format' => 'raw',
              'value' => fn($m) => Html::a('查看', ['view', 'id' => $m->id], ['class'=>'btn btn-sm btn-outline-dark']),
              'contentOptions' => ['style' => 'width:90px; text-align:center;'],
              'headerOptions' => ['style' => 'width:90px; text-align:center;'],
            ],
          ],
        ]); ?>

        <!-- ✅ 底部分页跳转 -->
        <?php if ($pageCount > 1): ?>
          <div class="d-flex justify-content-center align-items-center gap-2 mt-3">
            <span class="text-muted fw-semibold">跳转到第</span>
            <input
              id="jumpPageInput"
              type="number"
              class="form-control form-control-sm"
              style="width:92px;"
              min="1"
              max="<?= $pageCount ?>"
              value="<?= $page ?>"
            >
            <span class="text-muted fw-semibold">页</span>
            <button
              id="jumpPageBtn"
              type="button"
              class="btn btn-sm btn-outline-dark"
              data-max="<?= $pageCount ?>"
              data-pageparam="<?= Html::encode($pageParam) ?>"
            >Go</button>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>

<?php
$this->registerJs(<<<JS
(function(){
  const btn = document.getElementById('jumpPageBtn');
  const inp = document.getElementById('jumpPageInput');
  if(!btn || !inp) return;

  const pageParam = btn.dataset.pageparam || 'page';

  function go(){
    let p = parseInt(inp.value || '1', 10);
    const max = parseInt(btn.dataset.max || '1', 10);
    if (isNaN(p)) p = 1;
    p = Math.max(1, Math.min(p, max));

    const url = new URL(window.location.href);
    url.searchParams.set(pageParam, String(p - 1)); // Yii2 page 0-based
    window.location.href = url.toString();
  }

  btn.addEventListener('click', go);
  inp.addEventListener('keydown', function(e){
    if(e.key === 'Enter') go();
  });
})();
JS);
?>
