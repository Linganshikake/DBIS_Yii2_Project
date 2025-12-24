<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap5\LinkPager;

$this->title = '文献管理';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(<<<CSS
.white-card{
  background:#fff;
  border:1px solid rgba(0,0,0,.06);
  border-radius:18px;
  box-shadow:0 12px 30px rgba(0,0,0,.08);
  padding:18px;
}
.panel-title{ font-weight:900; font-size:30px; margin:0 0 6px; color:#111; }
.panel-sub{ color:rgba(0,0,0,.55); font-weight:600; margin-bottom:14px; }
.work-pager{ margin-top:14px; }
.work-pager .page-item{ margin:0 .18rem; }
.work-pager .page-link{
  border-radius:12px;
  border:1px solid rgba(0,0,0,.12);
  color:#111;
}
.work-pager .active .page-link{
  background: #7c1d2a;   /* 你站点酒红 */
  border-color:#7c1d2a;
  color:#fff;
}
.work-pager .disabled .page-link{
  pointer-events:none;
  opacity:.45;
}
CSS);
?>

<div class="container py-4">

  <!-- 白框1：标题 + 操作 -->
  <div class="white-card mb-3 d-flex align-items-start justify-content-between flex-wrap gap-2">
    <div>
      <div class="panel-title">文献管理</div>
      <div class="panel-sub">用于新增/编辑/删除文献数据（后台权限功能）。</div>
    </div>
    <div class="d-flex gap-2">
      <?= Html::a('＋ 新增文献', ['create'], ['class' => 'btn btn-success']) ?>
      <?= Html::a('去“文献展示”浏览', ['display'], ['class' => 'btn btn-outline-dark']) ?>
    </div>
  </div>

  <!-- 白框2：管理表格（不重复展示页） -->
  <div class="white-card">

    <?= GridView::widget([
      'dataProvider' => $dataProvider,
      'filterModel' => null, // ✅ 管理页不再重复一套筛选（展示页负责筛选/浏览）
      'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
      'summary' => '共 <b>{totalCount}</b> 条（管理页仅展示关键字段）',
      'summaryOptions' => ['class' => 'text-muted fw-semibold mb-2'],
      'layout' => "{summary}\n{items}\n<div class='mt-3'>{pager}</div>",
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
      'columns' => [
        ['class' => 'yii\grid\SerialColumn', 'header' => '#'],

        [
          'attribute' => 'id',
          'label' => 'ID',
          'contentOptions' => ['style' => 'width:90px;'],
        ],

        [
          'attribute' => 'title',
          'label' => '题名',
          'format' => 'raw',
          'contentOptions' => ['style' => 'max-width:520px;'],
          'value' => function($m){
            $t = $m->title ?: '(无题名)';
            return Html::a(Html::encode($t), ['view', 'id' => $m->id], [
              'title' => $t,
              'style' => 'font-weight:800; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;',
            ]);
          }
        ],

        [
          'attribute' => 'work_type',
          'label' => '类型',
          'contentOptions' => ['style' => 'width:140px;'],
          'value' => fn($m) => $m->work_type ?: '—',
        ],

        [
          'attribute' => 'publication_year',
          'label' => '年份',
          'contentOptions' => ['style' => 'width:110px;'],
          'value' => fn($m) => $m->publication_year ?: '—',
        ],

        [
          'attribute' => 'created_at',
          'label' => '入库时间',
          'format' => ['date', 'php:Y-m-d H:i'],
          'contentOptions' => ['style' => 'width:170px;'],
        ],

        [
          'class' => 'yii\grid\ActionColumn',
          'header' => '操作',
          'contentOptions' => ['style' => 'width:200px; white-space:nowrap;'],
          'template' => '{view} {update} {delete}',
          'buttons' => [
            'view' => fn($url) => Html::a('查看', $url, ['class' => 'btn btn-sm btn-outline-dark']),
            'update' => fn($url) => Html::a('编辑', $url, ['class' => 'btn btn-sm btn-outline-dark']),
            'delete' => fn($url) => Html::a('删除', $url, [
              'class' => 'btn btn-sm btn-outline-danger',
              'data' => [
                'confirm' => '确定要删除这条文献吗？',
                'method' => 'post',
              ],
            ]),
          ],
        ],
      ],
    ]); ?>

  </div>
</div>
