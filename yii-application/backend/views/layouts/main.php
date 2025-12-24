<?php
/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

/**
 * ✅ CSS 必须在 beginPage 之前 register（或至少在 endBody 之前）
 * 这样 Yii 才能把 CSS 正确输出到 <head>，避免“看起来没生效”
 */
$this->registerCss(<<<CSS
/* =========================
   全局：把链接强制改黑
========================= */
:root{
  --ink:#111;
}

a, a:visited{
  color: var(--ink) !important;
  text-decoration: none !important;
}
a:hover, a:active{
  color: var(--ink) !important;
  text-decoration: none !important;
  opacity: .88;
}

/* GridView 表头排序链接 */
table thead th a{
  color: var(--ink) !important;
  font-weight: 800;
}

/* 面包屑 */
.breadcrumb a{
  color: var(--ink) !important;
}

/* Bootstrap 的 link 类 */
.btn-link,
.link-primary, .link-secondary, .link-success, .link-danger, .link-warning, .link-info, .link-dark{
  color: var(--ink) !important;
}

/* =========================
   右上角“退出”按钮美化
========================= */
.navbar-logout-form{
  margin-left: .75rem;
}

.navbar-logout-btn{
  border-radius: 999px !important;
  padding: .45rem .95rem !important;
  font-weight: 800 !important;
  letter-spacing: .2px;

  border: 1px solid rgba(255,255,255,.38) !important;
  background: rgba(255,255,255,.10) !important;
  color: rgba(255,255,255,.95) !important;

  transition: all .18s ease;
  box-shadow: 0 10px 22px rgba(0,0,0,.18);
}

.navbar-logout-btn:hover{
  background: rgba(255,255,255,.18) !important;
  border-color: rgba(255,255,255,.62) !important;
  color: #fff !important;
  transform: translateY(-1px);
}

.navbar-logout-btn:active{
  transform: translateY(0);
  box-shadow: 0 8px 18px rgba(0,0,0,.16);
}

.navbar-logout-btn:focus{
  outline: none !important;
  box-shadow: 0 0 0 4px rgba(255,255,255,.14);
}

/* =========================
   ✅ 登录页：最快让白字变黑
   （只影响登录页，不影响其它页面）
========================= */
body.site-login, body.site-login *{
  color:#111 !important;
  -webkit-text-fill-color:#111 !important;
  opacity:1 !important;
}

/* 登录页常见的淡化文字也强制黑 */
body.site-login .text-muted,
body.site-login .text-white-50,
body.site-login .form-label,
body.site-login .form-text{
  color:#111 !important;
  -webkit-text-fill-color:#111 !important;
  opacity:1 !important;
}

/* 如果你希望主按钮文字保持白色更好看（否则删掉这段） */
body.site-login .btn-primary,
body.site-login .btn-primary *{
  color:#fff !important;
  -webkit-text-fill-color:#fff !important;
}
CSS);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title ?: '抗战80周年文献库') ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
<?php
NavBar::begin([
    'brandLabel' => '抗战80周年文献库',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
    ],
]);

$menuItems = [
    ['label' => '首页', 'url' => ['/site/index']],
    ['label' => '文献展示', 'url' => ['/work/display']],
    ['label' => '文献管理', 'url' => ['/work/index']],
];

echo Nav::widget([
    'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
    'items' => $menuItems,
]);

// 登录/退出按钮
if (Yii::$app->user->isGuest) {
    echo Html::tag('div',
        Html::a('登录', ['/site/login'], ['class' => 'btn btn-link login text-decoration-none']),
        ['class' => ['d-flex']]
    );
} else {
    echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex navbar-logout-form'])
        . Html::submitButton(
            '退出（' . Html::encode(Yii::$app->user->identity->username) . '）',
            ['class' => 'btn navbar-logout-btn']
        )
        . Html::endForm();
}

NavBar::end();
?>
</header>

<main role="main" class="flex-shrink-0 mt-5">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => $this->params['breadcrumbs'] ?? [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted bg-light">
    <div class="container">
        <p class="float-start">&copy; 抗战80周年文献库 <?= date('Y') ?></p>
        <p class="float-end"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
