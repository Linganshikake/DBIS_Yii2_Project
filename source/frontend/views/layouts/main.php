<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the frontend main layout view (前端主布局视图)
 */

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'M.LEAGUE DATA', // 网站标题
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            // ★★★ 核心修改：使用黑色主题，固定在顶部 ★★★
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
            'style' => 'background-color: #000 !important; border-bottom: 2px solid #d4af37;',
        ],
    ]);
    
    $menuItems = [
        ['label' => '首页', 'url' => ['/site/index']],
        ['label' => '队伍一览', 'url' => ['/team/index']],
        ['label' => '选手数据', 'url' => ['/player/index']],
        ['label' => '赛季成绩', 'url' => ['/ranking/index']],
        ['label' => '赛程表', 'url' => ['/schedule/index']],
        ['label' => '新闻动态', 'url' => ['/news/index']],
        ['label' => '评论广场', 'url' => ['/comment/index']],
        [
            'label' => '关于',
            'items' => [
                ['label' => 'M League是什么？', 'url' => ['/site/about']],
                ['label' => '课程文档下载', 'url' => ['/site/contact']],
            ],
        ],
    ];
    
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '注册', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => '登录', 'url' => ['/site/login']];
    } else {
        $menuItems[] = ['label' => '个人主页', 'url' => ['/profile/index']];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
