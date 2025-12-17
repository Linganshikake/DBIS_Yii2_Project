<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $rankings common\models\TeamSeasonStat[] */
/* @var $seasonName string */

$this->title = 'M-League Data System';
?>

<div class="site-index">

    <div class="jumbotron">
        <h1 style="color: #d4af37; font-weight: 900; letter-spacing: 5px;">M.LEAGUE</h1>
        <p class="lead" style="color: #fff;">PROFESSIONAL MAHJONG LEAGUE</p>
        <p style="color: #aaa;">—— 热狂を、外へ ——</p>
    </div>

    <div class="body-content">
        
        <div class="row">
            <div class="col-md-12">
                <h2 style="border-left: 5px solid #d4af37; padding-left: 15px; margin-bottom: 20px;">
                    <?= Html::encode($seasonName) ?> TEAM RANKING
                </h2>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="10%" class="text-center">Rank</th>
                                <th width="40%">Team Name</th>
                                <th width="20%" class="text-center">Total Score</th>
                                <th width="30%" class="text-center">Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rankings as $index => $stat): ?>
                            <?php 
                                // 获取当前应该展示的分数 (调用 Model 中的方法)
                                $currentScore = $stat->getDisplayScore();
                                
                                // 判断当前分数的阶段
                                $stageLabel = 'REGULAR';
                                if ($stat->final_score !== null) $stageLabel = 'FINAL';
                                elseif ($stat->semifinal_score !== null) $stageLabel = 'SEMI-FINAL';
                            ?>
                            <tr>
                                <td class="text-center" style="font-size: 24px; font-style: italic; font-weight: bold; color: <?= $index < 3 ? '#d4af37' : '#fff' ?>">
                                    <?= $index + 1 ?>
                                </td>
                                
                                <td style="vertical-align: middle;">
                                    <div style="font-size: 18px; font-weight: bold;">
                                        <?= Html::encode($stat->team->name) ?>
                                    </div>
                                    <div style="font-size: 12px; color: #888;">
                                        <?= Html::encode($stat->team->company) ?>
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
                                            // 计算一个简单的百分比长度 (假设 -500 到 +500 的范围)
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
                <h2 style="border-left: 5px solid #d4af37; padding-left: 15px; margin-bottom: 20px;">TEAMS</h2>
            </div>
            
            <?php foreach ($rankings as $stat): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="team-card">
                    <div style="height: 120px; display: flex; align-items: center; justify-content: center; background: #000; border-bottom: 1px solid #333;">
                        <span style="color: #333; font-weight: 900; font-size: 30px; letter-spacing: -2px;">M.L</span>
                    </div>
                    
                    <h3 style="color: #fff;"><?= Html::encode($stat->team->name) ?></h3>
                    
                    <?= Html::a('VIEW TEAM', ['team/view', 'id' => $stat->team_id], [
                        'class' => 'btn btn-sm', 
                        'style' => 'border: 1px solid #666; color: #ccc; margin-top: 10px; width: 100%;'
                    ]) ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>