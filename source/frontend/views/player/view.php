<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Player */

$this->title = $model->name;
?>

<div class="player-view">

    <div class="row" style="margin-bottom: 50px;">
        
        <div class="col-md-4">
            
            <div style="width: 100%; height: 400px; background: #000; border: 4px solid #d4af37; overflow: hidden; position: relative; box-shadow: 0 5px 15px rgba(0,0,0,0.5);">
                <?php if ($model->avatar): ?>
                    <img src="/uploads/players/<?= $model->avatar ?>" 
                         alt="<?= Html::encode($model->name) ?>" 
                         style="width: 100%; height: 100%; object-fit: cover; object-position: top;">
                <?php else: ?>
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                        <span style="color: #333; font-size: 30px;">NO PHOTO</span>
                    </div>
                <?php endif; ?>
            </div>

            <div style="margin-top: 20px;">
                <h5 style="color: #d4af37; font-weight: bold; font-size: 14px; margin-bottom: 10px; letter-spacing: 1px;">
                    CURRENT TEAM
                </h5>
                <a href="<?= Url::to(['team/view', 'id' => $model->team_id]) ?>" style="text-decoration: none;">
                    <div style="background: #1a1a1a; border: 1px solid #333; border-radius: 4px; padding: 20px; text-align: center; transition: 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
                        <?php if($model->team && $model->team->logo): ?>
                            <div style="width: 80px; height: 80px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden; margin: 0 auto 15px; box-shadow: 0 0 10px rgba(255,255,255,0.1);">
                                <img src="/uploads/teams/<?= $model->team->logo ?>" style="width: 60%; height: 60%; object-fit: contain;">
                            </div>
                        <?php endif; ?>
                        
                        <div style="color: #fff; font-weight: bold; font-size: 18px; text-transform: uppercase;">
                            <?= Html::encode($model->team->name ?? '') ?>
                        </div>
                        
                        <div style="margin-top: 15px;">
                            <span class="btn btn-outline-warning btn-sm" style="border-radius: 20px; font-size: 12px; padding: 5px 20px;">
                                View Team Info
                            </span>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <div class="col-md-8">
            <div style="padding-left: 20px;">
                
                <h5 style="color: #d4af37; font-weight: bold; letter-spacing: 2px;">M-LEAGUER</h5>
                <h1 style="font-size: 60px; color: #fff; font-weight: 900; line-height: 1; margin-bottom: 10px;">
                    <?= Html::encode($model->name) ?>
                </h1>
                <h3 style="color: #888; font-weight: 300; margin-top: 0;">
                    <?= Html::encode($model->register_name) ?>
                </h3>

                <table class="table" style="margin-top: 20px; background: transparent; border: none;">
                    <tr style="border-bottom: 1px solid #333;">
                        <td style="width: 150px; color: #888;">NICKNAME</td>
                        <td style="color: #fff; font-size: 18px;">"<?= Html::encode($model->nickname) ?>"</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #333;">
                        <td style="color: #888;">ORGANIZATION</td>
                        <td style="color: #fff; font-size: 18px;"><?= Html::encode($model->org ? $model->org->name : '-') ?></td>
                    </tr>
                    <tr>
                        <td style="color: #888;">JOINED</td>
                        <td style="color: #fff; font-size: 18px;"><?= date('Y', strtotime($model->join_date)) ?> Season</td>
                    </tr>
                </table>

                <?php if ($model->intro_video): ?>
                <div style="margin-top: 30px; border-top: 1px solid #333; padding-top: 25px;">
                    <h5 style="color: #d4af37; font-weight: bold; margin-bottom: 15px;">
                        <i class="fas fa-video" style="margin-right: 5px;"></i> SELF INTRODUCTION
                    </h5>
                    
                    <div style="background: #000; border: 1px solid #333; padding: 5px; border-radius: 4px; box-shadow: 0 5px 15px rgba(0,0,0,0.5);">
                        <?php 
                            $posterUrl = '';
                            if ($model->cover) {
                                $posterUrl = '/uploads/players/cover/' . $model->cover;
                            } elseif ($model->avatar) {
                                $posterUrl = '/uploads/players/' . $model->avatar;
                            }
                        ?>
                        <video controls style="width: 100%; display: block; outline: none; max-height: 400px; background: #000;" 
                               poster="<?= $posterUrl ?>">
                            <source src="/uploads/players/video/<?= $model->intro_video ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h2 style="border-left: 5px solid #d4af37; padding-left: 15px; margin-bottom: 20px; color: #fff;">
                SEASON STATISTICS
            </h2>

            <div class="table-responsive">
                <table class="table table-hover table-dark" style="background: #111; border: 1px solid #333;">
                    <thead>
                        <tr style="background: #000; color: #d4af37;">
                            <th>SEASON</th>
                            <th>TEAM</th>
                            <th class="text-center">GAMES</th>
                            <th class="text-center">TOTAL SCORE</th>
                            <th class="text-center">AVG RANK</th>
                            <th class="text-center">1st RATE</th>
                            <th class="text-center">4th AVOID</th>
                            <th class="text-center">MAX SCORE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($model->playerSeasonStats as $stat): ?>
                        <tr>
                            <td style="font-weight: bold;"><?= Html::encode($stat->season->name ?? '-') ?></td>
                            <td><?= Html::encode($stat->team->name ?? '-') ?></td>
                            
                            <td class="text-center"><?= $stat->games_count ?? '-' ?></td>
                            
                            <td class="text-center" style="font-size: 18px; font-weight: bold; color: <?= ($stat->total_score ?? 0) >= 0 ? '#00a550' : '#e74c3c' ?>">
                                <?= ($stat->total_score ?? 0) > 0 ? '+' : '' ?><?= number_format($stat->total_score ?? 0, 1) ?>
                            </td>
                            
                            <td class="text-center" style="color: #3498db;">
                                <?= $stat->avg_rank ? number_format($stat->avg_rank, 2) : '-' ?>
                            </td>
                            
                            <td class="text-center" style="color: #d4af37;">
                                <?= $stat->top_rate !== null ? number_format($stat->top_rate * 100, 1) . '%' : '-' ?>
                            </td>

                            <td class="text-center" style="color: #00a550;">
                                <?= $stat->last_avoid_rate !== null ? number_format($stat->last_avoid_rate * 100, 1) . '%' : '-' ?>
                            </td>
                            
                            <td class="text-center" style="color: #fff;">
                                <?= $stat->max_score ? number_format($stat->max_score) : '-' ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php if (empty($model->playerSeasonStats)): ?>
                        <tr>
                            <td colspan="8" class="text-center" style="padding: 30px; color: #666;">
                                暂无该选手的赛季数据
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- 详细数据卡片 -->
            <?php if ($model->playerSeasonStats): ?>
            <?php $currentStat = $model->playerSeasonStats[0] ?? null; ?>
            <?php if ($currentStat): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <h4 style="color: #888; margin-bottom: 20px;">
                        <?= Html::encode($currentStat->season->name ?? '') ?> 详细数据
                    </h4>
                </div>
                
                <!-- 数据卡片 -->
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div style="background: #1a1a1a; border: 1px solid #333; border-radius: 8px; padding: 20px; text-align: center;">
                        <div style="color: #888; font-size: 12px; margin-bottom: 5px;">最高得点</div>
                        <div style="color: #d4af37; font-size: 24px; font-weight: bold;">
                            <?= $currentStat->max_score ? number_format($currentStat->max_score) : '-' ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div style="background: #1a1a1a; border: 1px solid #333; border-radius: 8px; padding: 20px; text-align: center;">
                        <div style="color: #888; font-size: 12px; margin-bottom: 5px;">1位次数</div>
                        <div style="color: #d4af37; font-size: 24px; font-weight: bold;">
                            <?= $currentStat->rank_1_count ?? '-' ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div style="background: #1a1a1a; border: 1px solid #333; border-radius: 8px; padding: 20px; text-align: center;">
                        <div style="color: #888; font-size: 12px; margin-bottom: 5px;">2位次数</div>
                        <div style="color: #9b59b6; font-size: 24px; font-weight: bold;">
                            <?= $currentStat->rank_2_count ?? '-' ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div style="background: #1a1a1a; border: 1px solid #333; border-radius: 8px; padding: 20px; text-align: center;">
                        <div style="color: #888; font-size: 12px; margin-bottom: 5px;">3位次数</div>
                        <div style="color: #e67e22; font-size: 24px; font-weight: bold;">
                            <?= $currentStat->rank_3_count ?? '-' ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div style="background: #1a1a1a; border: 1px solid #333; border-radius: 8px; padding: 20px; text-align: center;">
                        <div style="color: #888; font-size: 12px; margin-bottom: 5px;">4位次数</div>
                        <div style="color: #e74c3c; font-size: 24px; font-weight: bold;">
                            <?= $currentStat->rank_4_count ?? '-' ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div style="background: #1a1a1a; border: 1px solid #333; border-radius: 8px; padding: 20px; text-align: center;">
                        <div style="color: #888; font-size: 12px; margin-bottom: 5px;">总场次</div>
                        <div style="color: #3498db; font-size: 24px; font-weight: bold;">
                            <?= $currentStat->games_count ?? '-' ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row mt-5 mb-5">
        <div class="col-12 text-center">
            <?= Html::a('← BACK TO LIST', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>

</div>
