<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap5\LinkPager;

$this->title = '文献检索';
$this->params['breadcrumbs'][] = $this->title;

/** ✅ 先取总数（会触发 ActiveDataProvider 计算 totalCount） */
$totalCount = (int)$dataProvider->getTotalCount();

/** ✅ 取分页对象 */
$pagination = $dataProvider->getPagination();

/**
 * ✅ 重要：不要在这里调用 $pagination->getPage() / getPageCount()
 * 因为 Pagination 会缓存 page，且在 totalCount 未正确设置时会把 page 归零
 */
if ($pagination === false) {
    $pageSize  = $totalCount > 0 ? $totalCount : 1;
    $pageCount = 1;
    $page      = 1;
} else {
    $pageSize = (int)$pagination->getPageSize();
    if ($pageSize <= 0) $pageSize = 15;

    $pageCount = (int)ceil($totalCount / max(1, $pageSize));
    $pageCount = max(1, $pageCount);

    // Yii2 的 page 参数是 0-based：page=0 表示第 1 页
    $reqPage0 = (int)Yii::$app->request->get($pagination->pageParam, 0);
    $page     = max(1, min($reqPage0 + 1, $pageCount));
}

$this->registerCss(<<<CSS
.white-card{
  background:#fff;
  border:1px solid rgba(0,0,0,.06);
  border-radius:18px;
  box-shadow:0 12px 30px rgba(0,0,0,.08);
  padding:18px;
}
.panel-title{ font-weight:900; font-size:30px; margin:0 0 6px; color:var(--ink); }
.panel-sub{ color:var(--muted); font-weight:600; margin-bottom:14px; }
.work-pager{ margin-top:14px; }
.work-pager .page-item{ margin:0 .18rem; }
.work-pager .page-link{
  border-radius:12px;
  border:1px solid rgba(0,0,0,.12);
  color:var(--ink);
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
CSS);

?>

<div class="page-wrap">
  <div class="work-index work-scope">
    <div class="container">

      <!-- ✅ 白框 1：筛选区 -->
      <div class="panel white-card mb-3">
        <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
        <div class="panel-sub">支持字段筛选 + 点击表头排序（题名 / 类型 / 年份 / 入库时间等）。</div>
        <?= $this->render('_search', ['model' => $searchModel]); ?>
      </div>

      <!-- ✅ 白框 2：统计/页码区 -->
      <div class="panel white-card mb-3 d-flex align-items-center justify-content-between">
        <div style="font-weight:800;">共 <?= $totalCount ?> 条结果</div>
        <div style="color:var(--muted); font-weight:800;">第 <?= $page ?> / <?= $pageCount ?> 页</div>
      </div>

      <!-- ✅ 白框 3：结果区 -->
      <div class="panel white-card">

        <?= GridView::widget([
          'dataProvider' => $dataProvider,
          'filterModel' => null,

          // ✅ “showing...” 改中文
          'summary' => '显示第 <b>{begin}</b> - <b>{end}</b> 条，共 <b>{totalCount}</b> 条',
          'summaryOptions' => ['class' => 'text-muted fw-semibold mb-2'],

          'layout' => "{summary}\n{items}\n<div class='mt-2'>{pager}</div>",

          // ✅ 用 bootstrap5 的 LinkPager（更贴合你的页面）
          'pager' => [
            'class' => LinkPager::class,
            'pagination' => $dataProvider->pagination,

            'options' => ['class' => 'pagination justify-content-center work-pager mb-0'],
            'firstPageLabel' => '首页',
            'lastPageLabel'  => '末页',
            'prevPageLabel'  => '上一页',
            'nextPageLabel'  => '下一页',

            // 首页/末页自动 disabled（不可点）
            'disabledListItemSubTagOptions' => ['class' => 'page-link'],
            'maxButtonCount' => 7,
          ],

          'tableOptions' => ['class' => 'table table-hover align-middle work-table'],
          'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => '#'],

            [
              'attribute' => 'title',
              'label' => '题名',
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
                    'style' => '
                      display:-webkit-box;
                      -webkit-line-clamp:2;
                      -webkit-box-orient:vertical;
                      overflow:hidden;
                      text-decoration:none;
                      font-weight:900;
                      line-height:1.35;
                      color:var(--brand);
                    '
                  ]
                );
              }
            ],

            [
              'attribute' => 'work_type',
              'label' => '类型',
              'format' => 'raw',
              'value' => function($m){
                $txt = method_exists($m,'getWorkTypeText') ? $m->workTypeText : ($m->work_type ?: '未填写');
                return '<span class="badge-type">'.Html::encode($txt).'</span>';
              },
              'contentOptions' => ['style' => 'width:120px;'],
            ],

            [
              'attribute' => 'publication_year',
              'label' => '年份',
              'value' => fn($m) => $m->publication_year ?: '—',
              'contentOptions' => ['style' => 'width:90px;'],
            ],

            [
              'attribute' => 'language',
              'label' => '语种',
              'value' => function($m){
                return method_exists($m,'getLanguageText') ? $m->languageText : ($m->language ?: '英文');
              },
              'contentOptions' => ['style' => 'width:90px;'],
            ],

            [
              'attribute' => 'source_name',
              'label' => '来源/刊物',
              'value' => fn($m) => $m->source_name ?: '—',
              'contentOptions' => ['style' => 'max-width:220px; white-space:normal;'],
            ],

            [
              'attribute' => 'doi',
              'label' => 'DOI',
              'format' => 'raw',
              'enableSorting' => true,
              'contentOptions' => ['style' => 'width:140px; white-space:nowrap;'],
              'value' => function($m){
                $doi = trim((string)$m->doi);
                if ($doi === '') return '—';

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
              'attribute' => 'created_at',
              'label' => '入库时间',
              'format' => ['date', 'php:Y-m-d H:i'],
              'contentOptions' => ['style' => 'width:150px;'],
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

  function go(){
    let p = parseInt(inp.value || '1', 10);
    const max = parseInt(btn.dataset.max || '1', 10);
    if (isNaN(p)) p = 1;
    p = Math.max(1, Math.min(p, max));

    const url = new URL(window.location.href);
    url.searchParams.set('page', String(p - 1)); // Yii2 page 0-based
    window.location.href = url.toString();
  }

  btn.addEventListener('click', go);
  inp.addEventListener('keydown', function(e){
    if(e.key === 'Enter') go();
  });

  // ✅ 提交筛选时，自动回到第 1 页（避免带着旧 page 导致“看起来没变”）
  const form = document.querySelector('form'); 
  if(form){
    form.addEventListener('submit', function(){
      const url = new URL(window.location.href);
      url.searchParams.delete('page');
      window.history.replaceState(null, '', url.toString());
    });
  }
})();
JS);
?>
