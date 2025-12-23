<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Team */
/* @var $teamSeasonStat common\models\TeamSeasonStat|null */
/* @var $currentSeason common\models\Season|null */

$this->title = $model->name;
?>

<div class="team-view">

    <div class="team-header" style="background: #1a1a1a; padding: 40px; border-bottom: 4px solid #d4af37; margin-bottom: 40px; position: relative;">
        <div class="row align-items-center">
            
            <div class="col-md-3 text-center">
                <div style="width: 150px; height: 150px; background: #fff; border: 4px solid #d4af37; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: 0 0 15px rgba(212, 175, 55, 0.3);">
                    <?php if ($model->logo): ?>
                        <img src="/uploads/teams/<?= $model->logo ?>" alt="Logo" style="width: 100%; height: 100%; object-fit: contain; padding: 15px;">
                    <?php else: ?>
                        <span style="font-size: 30px; font-weight: 900; color: #333;">LOGO</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-md-9">
                <h4 style="color: #d4af37; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 5px;">
                    OFFICIAL TEAM
                </h4>
                <h1 style="color: #fff; font-size: 48px; font-weight: 900; margin-top: 0; line-height: 1;">
                    <?= Html::encode($model->name) ?>
                </h1>
                <p style="color: #888; font-size: 18px; margin-top: 10px;">
                    <i class="fas fa-building"></i> 所属企业：<?= Html::encode($model->company) ?>
                </p>
                <div style="margin-top: 20px; color: #ccc; font-size: 14px; max-width: 800px;">
                    <?= nl2br(Html::encode($model->description)) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        
        <!-- 监督信息 + 战队介绍视频 并排 -->
        <?php if ($model->intro_video || $model->supervisor || $model->supervisor_photo): ?>
        <div class="row mb-5">
            <!-- SUPERVISOR (左侧) -->
            <?php if ($model->supervisor || $model->supervisor_photo): ?>
            <div class="col-lg-4 col-md-5">
                <h2 style="border-left: 5px solid #d4af37; padding-left: 15px; color: #fff; margin-bottom: 25px;">
                    SUPERVISOR
                </h2>
                <div style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px; overflow: hidden; text-align: center;">
                    <!-- 监督照片 -->
                    <div style="height: 280px; background: #222; overflow: hidden;">
                        <?php if ($model->supervisor_photo): ?>
                            <img src="/uploads/teams/supervisor/<?= $model->supervisor_photo ?>" 
                                 style="width: 100%; height: 100%; object-fit: cover; object-position: top;">
                        <?php else: ?>
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-user" style="font-size: 80px; color: #333;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div style="padding: 20px;">
                        <div style="color: #d4af37; font-size: 12px; letter-spacing: 2px; margin-bottom: 5px;">SUPERVISOR</div>
                        <h4 style="color: #fff; margin: 0; font-weight: bold;">
                            <?= Html::encode($model->supervisor ?: '未设定') ?>
                        </h4>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- TEAM VIDEO (右侧) -->
            <?php if ($model->intro_video): ?>
            <div class="col-lg-8 col-md-7">
                <h2 style="border-left: 5px solid #d4af37; padding-left: 15px; color: #fff; margin-bottom: 25px;">
                    TEAM VIDEO
                </h2>
                <div style="background: #000; border: 2px solid #333; border-radius: 8px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                    <?php 
                    $posterUrl = '';
                    if ($model->video_cover) {
                        $posterUrl = '/uploads/teams/cover/' . $model->video_cover;
                    } elseif ($model->logo) {
                        $posterUrl = '/uploads/teams/' . $model->logo;
                    }
                    ?>
                    <video controls style="width: 100%; display: block; outline: none; max-height: 400px; background: #000;" 
                           poster="<?= $posterUrl ?>">
                        <source src="/uploads/teams/video/<?= $model->intro_video ?>" type="video/mp4">
                        您的浏览器不支持视频播放
                    </video>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- 战队成员 -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 style="border-left: 5px solid #d4af37; padding-left: 15px; color: #fff;">
                    TEAM MEMBERS
                </h2>
            </div>
        </div>

        <div class="row">
            <?php foreach ($model->players as $player): ?>
                <?php if ($player->display_status == 1): // 只显示有效的选手 ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="player-card" style="background: #111; border: 1px solid #333; text-align: center; transition: 0.3s;">
                        
                        <div style="height: 200px; background: #222; position: relative; overflow: hidden;">
                            <?php if ($player->avatar): ?>
                                <img src="/uploads/players/<?= $player->avatar ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: top;">
                            <?php else: ?>
                                <div style="height: 100%; display: flex; align-items: center; justify-content: center; color: #333;">
                                    <span style="font-size: 50px;">NO IMG</span>
                                </div>
                            <?php endif; ?>

                            <div style="position: absolute; bottom: 0; width: 100%; padding: 10px; background: linear-gradient(to top, #000, transparent);">
                                <h3 style="margin: 0; color: #fff; font-size: 20px; font-weight: bold;">
                                    <?= Html::encode($player->name) ?>
                                </h3>
                                <div style="color: #d4af37; font-size: 12px; text-transform: uppercase;">
                                    <?= Html::encode($player->register_name) ?>
                                </div>
                            </div>
                        </div>

                        <div style="padding: 15px;">
                            <div class="row text-center">
                                <div class="col-6" style="border-right: 1px solid #333;">
                                    <div style="color: #888; font-size: 10px;">GENDER</div>
                                    <div style="color: #fff;"><?= $player->gender ?></div>
                                </div>
                                <div class="col-6">
                                    <div style="color: #888; font-size: 10px;">JOINED</div>
                                    <div style="color: #fff;"><?= date('Y', strtotime($player->join_date)) ?></div>
                                </div>
                            </div>
                            
                            <div style="margin-top: 15px;">
                                <a href="<?= Url::to(['player/view', 'id' => $player->id]) ?>" class="btn btn-sm btn-block" style="background: #333; color: #fff; width: 100%;">
                                    VIEW PROFILE
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if (empty($model->players)): ?>
                <div class="col-12 text-center" style="padding: 50px; color: #666;">
                    该队伍暂无选手数据
                </div>
            <?php endif; ?>
        </div>

        <!-- 战队赛季成绩 -->
        <div class="row mb-4 mt-5">
            <div class="col-12">
                <h2 style="border-left: 5px solid #d4af37; padding-left: 15px; color: #fff;">
                    TEAM SEASON STATISTICS
                </h2>
            </div>
        </div>

        <?php if (isset($teamSeasonStat) && $teamSeasonStat): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px; padding: 50px;">
                    <?php if (isset($currentSeason) && $currentSeason): ?>
                    <div class="text-center" style="margin-bottom: 40px;">
                        <span style="color: #d4af37; font-size: 14px; letter-spacing: 2px;">SEASON <?= Html::encode($currentSeason->name) ?></span>
                    </div>
                    <?php endif; ?>
                    <div style="display: flex; justify-content: space-between; gap: 25px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 160px;">
                            <div style="background: #222; border-radius: 10px; padding: 30px 20px; text-align: center;">
                                <div style="color: #888; font-size: 12px; margin-bottom: 15px; letter-spacing: 1px;">REGULAR SCORE</div>
                                <div style="color: #fff; font-size: 32px; font-weight: bold;">
                                    <?= $teamSeasonStat->regular_score !== null ? ($teamSeasonStat->regular_score >= 0 ? '+' : '') . $teamSeasonStat->regular_score : '-' ?>
                                </div>
                            </div>
                        </div>
                        <div style="flex: 1; min-width: 160px;">
                            <div style="background: #222; border-radius: 10px; padding: 30px 20px; text-align: center;">
                                <div style="color: #888; font-size: 12px; margin-bottom: 15px; letter-spacing: 1px;">SEMIFINAL SCORE</div>
                                <div style="color: <?= $teamSeasonStat->semifinal_score !== null ? '#fff' : '#444' ?>; font-size: 32px; font-weight: bold;">
                                    <?= $teamSeasonStat->semifinal_score !== null ? ($teamSeasonStat->semifinal_score >= 0 ? '+' : '') . $teamSeasonStat->semifinal_score : '-' ?>
                                </div>
                            </div>
                        </div>
                        <div style="flex: 1; min-width: 160px;">
                            <div style="background: #222; border-radius: 10px; padding: 30px 20px; text-align: center;">
                                <div style="color: #888; font-size: 12px; margin-bottom: 15px; letter-spacing: 1px;">FINAL SCORE</div>
                                <div style="color: <?= $teamSeasonStat->final_score !== null ? '#fff' : '#444' ?>; font-size: 32px; font-weight: bold;">
                                    <?= $teamSeasonStat->final_score !== null ? ($teamSeasonStat->final_score >= 0 ? '+' : '') . $teamSeasonStat->final_score : '-' ?>
                                </div>
                            </div>
                        </div>
                        <div style="flex: 1; min-width: 160px;">
                            <div style="background: linear-gradient(135deg, #d4af37, #b8860b); border-radius: 10px; padding: 30px 20px; text-align: center;">
                                <div style="color: #000; font-size: 12px; margin-bottom: 15px; letter-spacing: 1px;">TOTAL SCORE</div>
                                <div style="color: #000; font-size: 32px; font-weight: bold;">
                                    <?= $teamSeasonStat->total_score !== null ? ($teamSeasonStat->total_score >= 0 ? '+' : '') . $teamSeasonStat->total_score : '-' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($teamSeasonStat->total_rank): ?>
                    <div class="text-center" style="margin-top: 40px;">
                        <span style="background: #333; color: #d4af37; padding: 12px 40px; border-radius: 25px; font-size: 18px; font-weight: bold; letter-spacing: 2px;">
                            CURRENT RANK: #<?= $teamSeasonStat->total_rank ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    <div class="text-center" style="margin-top: 20px;">
                        <span style="color: #666; font-size: 12px;">
                            <?php 
                            if ($teamSeasonStat->final_score !== null) {
                                echo "✓ FINAL STAGE";
                            } elseif ($teamSeasonStat->semifinal_score !== null) {
                                echo "✓ SEMIFINAL STAGE";
                            } else {
                                echo "✓ REGULAR STAGE";
                            }
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="row mb-4">
            <div class="col-12 text-center" style="padding: 50px; color: #666; background: #1a1a1a; border-radius: 10px;">
                暂无本赛季成绩数据
            </div>
        </div>
        <?php endif; ?>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <?= Html::a('← BACK TO TEAM LIST', ['index'], ['class' => 'btn btn-outline-secondary', 'style' => 'color: #888; border-color: #444;']) ?>
            </div>
        </div>
    </div>

</div>
