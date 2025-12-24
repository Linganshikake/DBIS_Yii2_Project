<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the backend main layout view (后台主布局视图)
 */

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
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
    <style>
        .navbar-inverse {
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
            border-color: #d4af37;
        }
        .navbar-inverse .navbar-brand {
            color: #d4af37 !important;
            font-weight: bold;
        }
        .navbar-inverse .navbar-nav > li > a {
            color: #fff !important;
        }
        .navbar-inverse .navbar-nav > li > a:hover,
        .navbar-inverse .navbar-nav > li > a:focus {
            color: #d4af37 !important;
            background: rgba(212, 175, 55, 0.1);
        }
        .navbar-inverse .navbar-nav > .active > a,
        .navbar-inverse .navbar-nav > .active > a:hover,
        .navbar-inverse .navbar-nav > .active > a:focus {
            background: rgba(212, 175, 55, 0.2);
            color: #d4af37 !important;
        }
        .dropdown-menu {
            background: #2a2a2a;
            border: 1px solid #d4af37;
        }
        .dropdown-menu > li > a {
            color: #fff !important;
        }
        .dropdown-menu > li > a:hover {
            background: rgba(212, 175, 55, 0.2) !important;
            color: #d4af37 !important;
        }
        .navbar-inverse .navbar-nav > .open > a,
        .navbar-inverse .navbar-nav > .open > a:hover,
        .navbar-inverse .navbar-nav > .open > a:focus {
            background: rgba(212, 175, 55, 0.2);
        }
        .caret {
            border-top-color: #d4af37 !important;
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '<span style="color:#d4af37;">M.LEAGUE</span> 后台管理',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    
    if (Yii::$app->user->isGuest) {
        $menuItems = [
            ['label' => '首页', 'url' => ['/site/index']],
            ['label' => '登录', 'url' => ['/site/login']],
        ];
    } else {
        $menuItems = [
            ['label' => '首页', 'url' => ['/site/index']],
            [
                'label' => '队伍与选手',
                'items' => [
                    ['label' => '队伍管理', 'url' => ['/team/index']],
                    ['label' => '选手管理', 'url' => ['/player/index']],
                    ['label' => '团体管理', 'url' => ['/organization/index']],
                ],
            ],
            [
                'label' => '赛程与成绩',
                'items' => [
                    ['label' => '赛季管理', 'url' => ['/season/index']],
                    ['label' => '赛程管理', 'url' => ['/schedule/index']],
                    ['label' => '赛程成绩', 'url' => ['/schedule-score/index']],
                    ['label' => '选手赛季成绩', 'url' => ['/player-season-stat/index']],
                ],
            ],
            [
                'label' => '其他管理',
                'items' => [
                    ['label' => '新闻管理', 'url' => ['/news/index']],
                    ['label' => '企业管理', 'url' => ['/company/index']],
                ],
            ],
            '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    '退出 (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout', 'style' => 'color:#fff;']
                )
                . Html::endForm()
                . '</li>',
        ];
    }
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
        'encodeLabels' => false,
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
