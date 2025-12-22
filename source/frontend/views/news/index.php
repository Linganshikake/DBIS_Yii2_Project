<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $hotNews common\models\News[] */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'NEWS - 联赛新闻';
?>

<div class="news-index">

    <div class="text-center mb-5">
        <h1 style="color: #d4af37; font-weight: 900; letter-spacing: 5px; font-size: 48px;">NEWS</h1>
        <p style="color: #888;">联赛最新动态</p>
    </div>

    <!-- 热门新闻 Top 3 -->
    <?php if ($hotNews): ?>
    <div class="row mb-5">
        <?php foreach ($hotNews as $index => $news): ?>
        <div class="col-md-4 mb-4">
            <a href="<?= Url::to(['news/view', 'id' => $news->id]) ?>" style="text-decoration: none;">
                <div class="news-card-hot" style="background: #1a1a1a; border-radius: 10px; overflow: hidden; transition: transform 0.3s; height: 100%;">
                    <div style="height: 200px; background: #333; overflow: hidden;">
                        <?php if ($news->cover): ?>
                            <img src="/uploads/news/<?= $news->cover ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #666;">
                                <span style="font-size: 50px;">📰</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div style="padding: 20px;">
                        <div style="color: #888; font-size: 12px; margin-bottom: 10px;">
                            <?= date('Y.m.d', strtotime($news->publish_time)) ?>
                        </div>
                        <h3 style="color: #fff; font-size: 18px; font-weight: bold; line-height: 1.4; margin: 0;">
                            <?= Html::encode(mb_substr($news->title, 0, 40)) ?><?= mb_strlen($news->title) > 40 ? '...' : '' ?>
                        </h3>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- 新闻列表 -->
    <div class="news-list">
        <?php foreach ($dataProvider->models as $news): ?>
        <a href="<?= Url::to(['news/view', 'id' => $news->id]) ?>" style="text-decoration: none;">
            <div class="news-item" style="display: flex; background: #1a1a1a; margin-bottom: 20px; border-radius: 10px; overflow: hidden; transition: transform 0.3s;">
                <div style="width: 300px; height: 180px; flex-shrink: 0; background: #333;">
                    <?php if ($news->cover): ?>
                        <img src="/uploads/news/<?= $news->cover ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #666;">
                            <span style="font-size: 50px;">📰</span>
                        </div>
                    <?php endif; ?>
                </div>
                <div style="padding: 25px; flex: 1;">
                    <div style="color: #888; font-size: 12px; margin-bottom: 10px;">
                        <?= date('Y.m.d', strtotime($news->publish_time)) ?>
                    </div>
                    <h3 style="color: #fff; font-size: 22px; font-weight: bold; margin: 0 0 15px 0;">
                        <?= Html::encode($news->title) ?>
                    </h3>
                    <p style="color: #aaa; font-size: 14px; line-height: 1.6; margin: 0;">
                        <?= Html::encode(mb_substr(strip_tags($news->content), 0, 100)) ?>...
                    </p>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- 分页 -->
    <div class="text-center mt-4">
        <?= LinkPager::widget([
            'pagination' => $dataProvider->pagination,
            'options' => ['class' => 'pagination justify-content-center'],
            'linkOptions' => ['style' => 'color: #d4af37;'],
        ]) ?>
    </div>

</div>

<style>
.news-card-hot:hover,
.news-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.2);
}
</style>
