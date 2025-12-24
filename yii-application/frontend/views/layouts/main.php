<?php
/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

if (empty($this->title)) {
    $this->title = '抗战80周年文献库';
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
<?php
NavBar::begin([
    'brandLabel' => '抗战80周年文献库',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => ['class' => 'navbar navbar-expand-md navbar-dark fixed-top'],
]);

$menuItems = [
    ['label' => '首页', 'url' => ['/site/index']],
    ['label' => '文献检索', 'url' => ['/work/index']],
    ['label' => '项目说明', 'url' => ['/site/about']],
    ['label' => '反馈/征集', 'url' => ['/site/contact']],
];

echo Nav::widget([
    'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
    'items' => $menuItems,
]);

echo '<div class="d-flex align-items-center gap-2">';

if (Yii::$app->user->isGuest) {
    echo Html::a('登录/注册', ['/site/login'], ['class' => 'btn btn-auth']);
} else {
    echo Html::beginForm(['/site/logout'], 'post', ['class' => 'm-0'])
        . Html::submitButton('退出（' . Html::encode(Yii::$app->user->identity->username) . '）', ['class' => 'btn btn-auth'])
        . Html::endForm();
}

echo '</div>';

NavBar::end();
?>
</header>

<main role="main" class="flex-shrink-0 site-main">
    <div class="container">
        <div class="content-wrap">
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs'] ?? []]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>
</main>

<footer class="footer mt-auto py-3">
    <div class="container footer-inner">
        <div>&copy; 抗战80周年文献库 <?= date('Y') ?></div>
        <div>Powered by Yii2</div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage(); ?>
