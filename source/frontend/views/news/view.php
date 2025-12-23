<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = $model->title;
?>

<div class="news-view">

    <!-- 返回按钮 -->
    <div class="mb-4">
        <?= Html::a('← 返回新闻列表', ['index'], ['style' => 'color: #d4af37; text-decoration: none;']) ?>
    </div>

    <!-- 新闻头部 -->
    <div class="news-header" style="margin-bottom: 40px;">
        <h1 style="color: #fff; font-size: 36px; font-weight: bold; line-height: 1.4; margin-bottom: 20px;">
            <?= Html::encode($model->title) ?>
        </h1>
        <div style="color: #888; font-size: 14px;">
            <span style="margin-right: 30px;">📅 <?= date('Y年m月d日 H:i', strtotime($model->publish_time)) ?></span>
            <span>👁 <?= $model->view_count ?> 次浏览</span>
        </div>
    </div>

    <!-- 封面图 -->
    <?php if ($model->cover): ?>
    <div class="news-cover" style="margin-bottom: 40px; text-align: center;">
        <img src="/uploads/news/cover/<?= $model->cover ?>" style="max-width: 100%; max-height: 400px; object-fit: contain; border-radius: 10px;">
    </div>
    <?php endif; ?>

    <!-- 新闻正文 -->
    <div class="news-content" style="background: #1a1a1a; padding: 40px; border-radius: 10px; margin-bottom: 40px;">
        <div class="news-body" style="color: #ddd; font-size: 16px; line-height: 2; max-width: 700px; margin: 0 auto;">
            <?= $this->render('_content', ['content' => $model->content]) ?>
        </div>
    </div>

    <!-- 图片画廊 -->
    <?php $images = $model->getImagesArray(); ?>
    <?php if ($images): ?>
    <div class="news-gallery" style="margin-bottom: 40px;">
        <h3 style="color: #d4af37; margin-bottom: 20px;">相关图片</h3>
        <div class="row">
            <?php foreach ($images as $img): ?>
            <div class="col-md-4 mb-3">
                <a href="/uploads/news/images/<?= $img ?>" target="_blank">
                    <img src="/uploads/news/images/<?= $img ?>" style="width: 100%; height: 200px; object-fit: cover; border-radius: 5px;">
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- 底部分享 -->
    <div class="news-footer" style="border-top: 1px solid #333; padding-top: 30px;">
        <div class="row">
            <div class="col-6">
                <?= Html::a('← 返回新闻列表', ['index'], ['class' => 'btn btn-outline-warning']) ?>
            </div>
            <div class="col-6 text-right">
                <!-- 可以添加分享按钮 -->
            </div>
        </div>
    </div>

</div>
