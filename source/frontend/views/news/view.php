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

    <!-- 新闻正文 -->
    <div class="news-content" style="background: #1a1a1a; padding: 40px; border-radius: 10px; margin-bottom: 40px;">
        <div class="news-body" style="color: #ddd; font-size: 16px; line-height: 2; max-width: 700px; margin: 0 auto;">
            <?= $this->render('_content', ['content' => $model->content, 'cover' => $model->cover]) ?>
        </div>
    </div>

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
