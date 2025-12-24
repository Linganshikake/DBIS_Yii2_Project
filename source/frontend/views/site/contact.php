<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the frontend contact view (前端联系我们视图)
 */

/* @var $this yii\web\View */
/* @var $personalWorks array */
/* @var $teamWorks array */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '课程文档下载';
?>

<div class="site-contact">
    <!-- 标题区域 -->
    <div class="text-center" style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); padding: 50px 20px; margin: -20px -15px 40px; border-radius: 0 0 20px 20px;">
        <h1 style="color: #d4af37; font-weight: 900; letter-spacing: 3px; font-size: 36px;">
            <i class="fa fa-download"></i> 课程文档下载
        </h1>
        <p style="color: #888; margin-top: 15px;">数据库课程设计 - 团队项目与个人作业</p>
    </div>

    <!-- 团队文档区域 -->
    <div class="team-docs" style="margin-bottom: 50px;">
        <h2 style="color: #d4af37; border-bottom: 2px solid #d4af37; padding-bottom: 15px; margin-bottom: 25px;">
            <i class="fa fa-users"></i> 团队文档
        </h2>
        
        <?php if (!empty($teamWorks)): ?>
        <div class="row">
            <?php foreach ($teamWorks as $work): ?>
            <div class="col-md-4 col-sm-6" style="margin-bottom: 20px;">
                <div class="doc-card" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px; padding: 20px; text-align: center; transition: all 0.3s;">
                    <?php 
                    $ext = strtolower(pathinfo($work['name'], PATHINFO_EXTENSION));
                    $icon = 'fa-file-o';
                    $color = '#888';
                    if (in_array($ext, ['pdf'])) {
                        $icon = 'fa-file-pdf-o';
                        $color = '#e74c3c';
                    } elseif (in_array($ext, ['doc', 'docx'])) {
                        $icon = 'fa-file-word-o';
                        $color = '#3498db';
                    } elseif (in_array($ext, ['ppt', 'pptx'])) {
                        $icon = 'fa-file-powerpoint-o';
                        $color = '#e67e22';
                    } elseif (in_array($ext, ['mp4', 'avi', 'mov'])) {
                        $icon = 'fa-file-video-o';
                        $color = '#9b59b6';
                    } elseif (in_array($ext, ['zip', 'rar', '7z'])) {
                        $icon = 'fa-file-archive-o';
                        $color = '#f1c40f';
                    }
                    ?>
                    <i class="fa <?= $icon ?>" style="font-size: 48px; color: <?= $color ?>; margin-bottom: 15px;"></i>
                    <h4 style="color: #fff; margin-bottom: 15px; font-size: 14px; word-break: break-all;">
                        <?= Html::encode($work['name']) ?>
                    </h4>
                    <a href="<?= Url::to($work['path']) ?>" class="btn btn-warning btn-sm" style="font-weight: bold;">
                        <i class="fa fa-download"></i> 下载
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="alert" style="background: #2a2a2a; border: 1px solid #444; color: #888; text-align: center; padding: 30px;">
            <i class="fa fa-info-circle"></i> 团队文档即将上传，敬请期待...
        </div>
        <?php endif; ?>
    </div>

    <!-- 个人作业区域 -->
    <div class="personal-docs">
        <h2 style="color: #d4af37; border-bottom: 2px solid #d4af37; padding-bottom: 15px; margin-bottom: 25px;">
            <i class="fa fa-user"></i> 个人作业
        </h2>
        
        <?php if (!empty($personalWorks)): ?>
        <?php foreach ($personalWorks as $studentData): ?>
        <div class="student-section" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
            <h4 style="color: #d4af37; margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 10px;">
                <i class="fa fa-graduation-cap"></i> <?= Html::encode($studentData['student']) ?>
            </h4>
            <div class="row">
                <?php foreach ($studentData['works'] as $work): ?>
                <div class="col-md-4 col-sm-6" style="margin-bottom: 15px;">
                    <div class="work-item" style="background: #222; border-radius: 8px; padding: 15px; display: flex; align-items: center; justify-content: space-between;">
                        <span style="color: #ccc; font-size: 13px; flex: 1; word-break: break-all; margin-right: 15px;">
                            <?php 
                            $ext = strtolower(pathinfo($work['name'], PATHINFO_EXTENSION));
                            $icon = 'fa-file-o';
                            if (in_array($ext, ['zip', 'rar', '7z'])) {
                                $icon = 'fa-file-archive-o';
                            } elseif (in_array($ext, ['pdf'])) {
                                $icon = 'fa-file-pdf-o';
                            } elseif (in_array($ext, ['doc', 'docx'])) {
                                $icon = 'fa-file-word-o';
                            }
                            ?>
                            <i class="fa <?= $icon ?>" style="margin-right: 8px;"></i>
                            <?= Html::encode($work['name']) ?>
                        </span>
                        <a href="<?= Url::to($work['path']) ?>" class="btn btn-warning" style="white-space: nowrap; padding: 8px 15px; font-size: 13px; font-weight: bold;">
                            <i class="fa fa-download"></i> 下载
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="alert" style="background: #2a2a2a; border: 1px solid #444; color: #888; text-align: center; padding: 30px;">
            <i class="fa fa-info-circle"></i> 暂无个人作业
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.doc-card:hover {
    transform: translateY(-5px);
    border-color: #d4af37;
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.2);
}
.work-item:hover {
    background: #2a2a2a !important;
}
.btn-outline-warning {
    border: 1px solid #d4af37;
    color: #d4af37;
    background: transparent;
}
.btn-outline-warning:hover {
    background: #d4af37;
    color: #000;
}
</style>
