<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $rankings common\models\TeamSeasonStat[] */
/* @var $seasonName string */

$this->title = 'M-League Data System';
?>

<div class="site-index">

    <div class="jumbotron" style="background: transparent; border-bottom: 1px solid #333; margin-bottom: 40px;">
        <h1 style="color: #d4af37; font-weight: 900; letter-spacing: 5px;">M.LEAGUE</h1>
        <p class="lead" style="color: #fff;">PROFESSIONAL MAHJONG LEAGUE</p>
        <p style="color: #aaa; font-style: italic;">—— 热狂を、外へ ——</p>
    </div>

    <div class="body-content">
        
        <div class="row">
            <div class="col-md-12">
                <h2 style="border-left: 5px solid #d4af37; padding-left: 15px; margin-bottom: 20px; color: #fff;">
                    <?= Html::encode($seasonName) ?> TEAM RANKING
                </h2>

                <div class="table-responsive">
                    <table class="table table-hover table-dark" style="background: #1a1a1a; border: 1px solid #333;">
                        <thead>
                            <tr style="background: #000; color: #d4af37;">
                                <th width="10%" class="text-center">Rank</th>
                                <th width="40%">Team Name</th>
                                <th width="20%" class="text-center">Total Score</th>
                                <th width="30%" class="text-center">Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rankings as $index => $stat): ?>
                            <?php 
                                $currentScore = $stat->getDisplayScore();
                                $stageLabel = 'REGULAR';
                                if ($stat->final_score !== null) $stageLabel = 'FINAL';
                                elseif ($stat->semifinal_score !== null) $stageLabel = 'SEMI-FINAL';
                            ?>
                            <tr>
                                <td class="text-center" style="font-size: 24px; font-style: italic; font-weight: bold; vertical-align: middle; color: <?= $index < 3 ? '#d4af37' : '#fff' ?>">
                                    <?= $index + 1 ?>
                                </td>
                                
                                <td style="vertical-align: middle;">
                                    <div style="display: flex; align-items: center;">
                                        <div style="width: 40px; height: 40px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-right: 15px;">
                                            <?php if ($stat->team->logo): ?>
                                                <img src="/uploads/teams/<?= $stat->team->logo ?>" style="width: 100%; height: 100%; object-fit: contain;">
                                            <?php else: ?>
                                                <span style="color: #000; font-size: 10px; font-weight: bold;">ML</span>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div style="font-size: 18px; font-weight: bold; color: #fff;">
                                                <?= Html::encode($stat->team->name) ?>
                                            </div>
                                            <div style="font-size: 12px; color: #888;">
                                                <?= Html::encode($stat->team->company) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="text-center" style="vertical-align: middle;">
                                    <div style="font-size: 22px; font-weight: bold; color: <?= $currentScore >= 0 ? '#fff' : '#aaa' ?>">
                                        <?= $currentScore > 0 ? '+' : '' ?><?= $currentScore ?>
                                        <span style="font-size: 14px;">pt</span>
                                    </div>
                                    <div style="font-size: 10px; color: #d4af37; letter-spacing: 1px; margin-top: 2px;">
                                        <?= $stageLabel ?> PHASE
                                    </div>
                                </td>
                                
                                <td class="text-center" style="vertical-align: middle;">
                                    <div style="background: #333; height: 6px; width: 100%; border-radius: 3px; overflow: hidden;">
                                        <?php 
                                            // 简单的视觉效果计算
                                            $width = min(100, max(0, ($currentScore + 500) / 10)); 
                                        ?>
                                        <div style="background: linear-gradient(90deg, #d4af37, #ffd700); height: 100%; width: <?= $width ?>%;"></div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            
                            <?php if (empty($rankings)): ?>
                            <tr>
                                <td colspan="4" class="text-center" style="padding: 30px; color: #666;">
                                    当前赛季暂无数据 / No Data Available
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div style="margin-top: 60px;"></div>

        <div class="row">
            <div class="col-md-12">
                <h2 style="border-left: 5px solid #d4af37; padding-left: 15px; margin-bottom: 20px; color: #fff;">TEAMS</h2>
            </div>
            
            <?php foreach ($rankings as $stat): ?>
            <div class="col-lg-3 col-md-4 col-sm-6" style="margin-bottom: 20px;">
                <a href="<?= Url::to(['team/view', 'id' => $stat->team_id]) ?>" style="text-decoration: none;">
                    <div class="team-card" style="border: 1px solid #333; border-radius: 5px; overflow: hidden; background: #1a1a1a;">
                        
                        <div style="height: 120px; display: flex; align-items: center; justify-content: center; background: #fff; border-bottom: 1px solid #d4af37;">
                            <?php if ($stat->team->logo): ?>
                                <img src="/uploads/teams/<?= $stat->team->logo ?>" style="width: 100%; height: 100%; object-fit: contain; padding: 15px;">
                            <?php else: ?>
                                <span style="color: #333; font-weight: 900; font-size: 30px; letter-spacing: -2px;">M.L</span>
                            <?php endif; ?>
                        </div>
                        
                        <div style="padding: 15px; text-align: center;">
                            <h4 style="color: #fff; margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase;">
                                <?= Html::encode($stat->team->name) ?>
                            </h4>
                            <div style="margin-top: 10px; color: #d4af37; font-size: 12px;">
                                View Team >
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>