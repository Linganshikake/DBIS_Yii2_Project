<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the frontend site index view (前端首页视图)
 */
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $rankings common\models\TeamSeasonStat[] */
/* @var $seasonName string */
/* @var $playerRankings array */
/* @var $latestNews common\models\News[] */

$this->title = 'M-League Data System';
?>

<div class="site-index">

    <div class="jumbotron" style="background: transparent; border-bottom: 1px solid #333; margin-bottom: 40px;">
        <h1 style="color: #d4af37; font-weight: 900; letter-spacing: 5px;">M.LEAGUE</h1>
        <p class="lead" style="color: #fff;">PROFESSIONAL MAHJONG LEAGUE</p>
        <p style="color: #aaa; font-style: italic;">—— 热狂を、外へ ——</p>
    </div>

    <div class="body-content">
        
        <!-- 什么是M.LEAGUE介绍区域 -->
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="intro-section" style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); border: 1px solid #333; border-radius: 10px; padding: 40px; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: 0; left: 0; width: 5px; height: 100%; background: linear-gradient(to bottom, #d4af37, #ffd700);"></div>
                    
                    <h2 style="color: #d4af37; font-weight: 900; margin-bottom: 25px; letter-spacing: 3px;">
                        <span style="border-bottom: 2px solid #d4af37; padding-bottom: 10px;">什么是M.LEAGUE？</span>
                    </h2>
                    
                    <div class="row">
                        <div class="col-lg-8">
                            <p style="color: #ddd; font-size: 16px; line-height: 2; text-align: justify;">
                                <strong style="color: #fff;">M.LEAGUE（エムリーグ）</strong>是日本首个竞技麻将职业联赛，于2018年成立。
                                该联赛汇集了日本各大麻将团体的顶尖职业选手，由多家大型企业赞助组建战队参赛。
                            </p>
                            <p style="color: #ddd; font-size: 16px; line-height: 2; text-align: justify;">
                                M.LEAGUE的诞生标志着麻将从传统的博弈游戏正式迈向职业化、竞技化的新阶段。
                                赛事采用四麻竞技规则，每个赛季从秋季开始，经过常规赛、半决赛，最终决出年度冠军。
                            </p>
                            <p style="color: #888; font-size: 14px; margin-top: 20px;">
                                <i class="fa fa-quote-left" style="color: #d4af37; margin-right: 10px;"></i>
                                "热狂を、外へ" —— 将麻将的热情传递给更多人
                                <i class="fa fa-quote-right" style="color: #d4af37; margin-left: 10px;"></i>
                            </p>
                        </div>
                        <div class="col-lg-4 text-center" style="display: flex; align-items: center; justify-content: center;">
                            <div style="background: #fff; border-radius: 50%; padding: 0; box-shadow: 0 4px 20px rgba(212,175,55,0.4); width: 160px; height: 160px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                <img src="/uploads/mleague_logo_circle.png" alt="M.LEAGUE" style="width: 100%; height: 100%; object-fit: contain; display: block;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 选手个人排行榜 - 4个维度 -->
        <?php if (!empty($playerRankings)): ?>
        <div class="row mb-5">
            <div class="col-md-12">
                <h2 style="border-left: 5px solid #d4af37; padding-left: 15px; margin-bottom: 30px; color: #fff;">
                    <?= Html::encode($seasonName) ?> 选手排行榜
                </h2>
            </div>
            
            <!-- 总得点排行 -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="ranking-card" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px; overflow: hidden;">
                    <div style="background: #d4af37; padding: 15px; text-align: center;">
                        <h5 style="color: #000; margin: 0; font-weight: bold;">总得点</h5>
                    </div>
                    <div style="padding: 15px;">
                        <div class="ranking-list" data-type="total_score">
                        <?php foreach ($playerRankings['total_score'] ?? [] as $index => $stat): ?>
                        <a href="<?= Url::to(['player/view', 'id' => $stat->player_id]) ?>" style="text-decoration: none; display: block;">
                        <div style="display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #333;" class="ranking-item">
                            <div style="width: 25px; font-size: 18px; font-weight: bold; color: <?= $index < 3 ? '#d4af37' : '#666' ?>;">
                                <?= $index + 1 ?>
                            </div>
                            <div style="width: 35px; height: 35px; border-radius: 50%; overflow: hidden; background: #333; margin: 0 10px;">
                                <?php if ($stat->player && $stat->player->avatar): ?>
                                    <img src="/uploads/players/<?= $stat->player->avatar ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php endif; ?>
                            </div>
                            <div style="flex: 1;">
                                <div style="color: #fff; font-size: 13px; font-weight: bold;">
                                    <?= Html::encode($stat->player->name ?? '未知') ?>
                                </div>
                                <div style="color: #888; font-size: 10px;">
                                    <?= Html::encode($stat->player->team->name ?? '') ?>
                                </div>
                            </div>
                            <div style="color: <?= $stat->total_score >= 0 ? '#00a550' : '#e74c3c' ?>; font-weight: bold; font-size: 12px;">
                                <?= $stat->total_score >= 0 ? '+' : '' ?><?= number_format($stat->total_score, 1) ?>
                            </div>
                        </div>
                        </a>
                        <?php endforeach; ?>
                        </div>
                        <!-- 隐藏的完整列表 -->
                        <div class="ranking-list-full" data-type="total_score" style="display: none;">
                        <?php foreach ($playerRankings['total_score_all'] ?? [] as $index => $stat): ?>
                        <a href="<?= Url::to(['player/view', 'id' => $stat->player_id]) ?>" style="text-decoration: none; display: block;">
                        <div style="display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #333;" class="ranking-item">
                            <div style="width: 25px; font-size: 18px; font-weight: bold; color: <?= $index < 3 ? '#d4af37' : '#666' ?>;">
                                <?= $index + 1 ?>
                            </div>
                            <div style="width: 35px; height: 35px; border-radius: 50%; overflow: hidden; background: #333; margin: 0 10px;">
                                <?php if ($stat->player && $stat->player->avatar): ?>
                                    <img src="/uploads/players/<?= $stat->player->avatar ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php endif; ?>
                            </div>
                            <div style="flex: 1;">
                                <div style="color: #fff; font-size: 13px; font-weight: bold;">
                                    <?= Html::encode($stat->player->name ?? '未知') ?>
                                </div>
                                <div style="color: #888; font-size: 10px;">
                                    <?= Html::encode($stat->player->team->name ?? '') ?>
                                </div>
                            </div>
                            <div style="color: <?= $stat->total_score >= 0 ? '#00a550' : '#e74c3c' ?>; font-weight: bold; font-size: 12px;">
                                <?= $stat->total_score >= 0 ? '+' : '' ?><?= number_format($stat->total_score, 1) ?>
                            </div>
                        </div>
                        </a>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 平均顺位排行 -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="ranking-card" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px; overflow: hidden;">
                    <div style="background: #3498db; padding: 15px; text-align: center;">
                        <h5 style="color: #fff; margin: 0; font-weight: bold;">平均顺位</h5>
                    </div>
                    <div style="padding: 15px;">
                        <div class="ranking-list" data-type="avg_rank">
                        <?php foreach ($playerRankings['avg_rank'] ?? [] as $index => $stat): ?>
                        <a href="<?= Url::to(['player/view', 'id' => $stat->player_id]) ?>" style="text-decoration: none; display: block;">
                        <div style="display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #333;" class="ranking-item">
                            <div style="width: 25px; font-size: 18px; font-weight: bold; color: <?= $index < 3 ? '#3498db' : '#666' ?>;">
                                <?= $index + 1 ?>
                            </div>
                            <div style="width: 35px; height: 35px; border-radius: 50%; overflow: hidden; background: #333; margin: 0 10px;">
                                <?php if ($stat->player && $stat->player->avatar): ?>
                                    <img src="/uploads/players/<?= $stat->player->avatar ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php endif; ?>
                            </div>
                            <div style="flex: 1;">
                                <div style="color: #fff; font-size: 13px; font-weight: bold;">
                                    <?= Html::encode($stat->player->name ?? '未知') ?>
                                </div>
                                <div style="color: #888; font-size: 10px;">
                                    <?= Html::encode($stat->player->team->name ?? '') ?>
                                </div>
                            </div>
                            <div style="color: #3498db; font-weight: bold; font-size: 12px;">
                                <?= number_format($stat->avg_rank, 2) ?>
                            </div>
                        </div>
                        </a>
                        <?php endforeach; ?>
                        </div>
                        <div class="ranking-list-full" data-type="avg_rank" style="display: none;">
                        <?php foreach ($playerRankings['avg_rank_all'] ?? [] as $index => $stat): ?>
                        <a href="<?= Url::to(['player/view', 'id' => $stat->player_id]) ?>" style="text-decoration: none; display: block;">
                        <div style="display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #333;" class="ranking-item">
                            <div style="width: 25px; font-size: 18px; font-weight: bold; color: <?= $index < 3 ? '#3498db' : '#666' ?>;">
                                <?= $index + 1 ?>
                            </div>
                            <div style="width: 35px; height: 35px; border-radius: 50%; overflow: hidden; background: #333; margin: 0 10px;">
                                <?php if ($stat->player && $stat->player->avatar): ?>
                                    <img src="/uploads/players/<?= $stat->player->avatar ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php endif; ?>
                            </div>
                            <div style="flex: 1;">
                                <div style="color: #fff; font-size: 13px; font-weight: bold;">
                                    <?= Html::encode($stat->player->name ?? '未知') ?>
                                </div>
                                <div style="color: #888; font-size: 10px;">
                                    <?= Html::encode($stat->player->team->name ?? '') ?>
                                </div>
                            </div>
                            <div style="color: #3498db; font-weight: bold; font-size: 12px;">
                                <?= number_format($stat->avg_rank, 2) ?>
                            </div>
                        </div>
                        </a>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 1位率排行 -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="ranking-card" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px; overflow: hidden;">
                    <div style="background: #e74c3c; padding: 15px; text-align: center;">
                        <h5 style="color: #fff; margin: 0; font-weight: bold;">1位率</h5>
                    </div>
                    <div style="padding: 15px;">
                        <div class="ranking-list" data-type="first_rate">
                        <?php foreach ($playerRankings['first_rate'] ?? [] as $index => $stat): ?>
                        <a href="<?= Url::to(['player/view', 'id' => $stat->player_id]) ?>" style="text-decoration: none; display: block;">
                        <div style="display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #333;" class="ranking-item">
                            <div style="width: 25px; font-size: 18px; font-weight: bold; color: <?= $index < 3 ? '#e74c3c' : '#666' ?>;">
                                <?= $index + 1 ?>
                            </div>
                            <div style="width: 35px; height: 35px; border-radius: 50%; overflow: hidden; background: #333; margin: 0 10px;">
                                <?php if ($stat->player && $stat->player->avatar): ?>
                                    <img src="/uploads/players/<?= $stat->player->avatar ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php endif; ?>
                            </div>
                            <div style="flex: 1;">
                                <div style="color: #fff; font-size: 13px; font-weight: bold;">
                                    <?= Html::encode($stat->player->name ?? '未知') ?>
                                </div>
                                <div style="color: #888; font-size: 10px;">
                                    <?= Html::encode($stat->player->team->name ?? '') ?>
                                </div>
                            </div>
                            <div style="color: #e74c3c; font-weight: bold; font-size: 12px;">
                                <?= number_format($stat->top_rate * 100, 1) ?>%
                            </div>
                        </div>
                        </a>
                        <?php endforeach; ?>
                        </div>
                        <div class="ranking-list-full" data-type="first_rate" style="display: none;">
                        <?php foreach ($playerRankings['first_rate_all'] ?? [] as $index => $stat): ?>
                        <a href="<?= Url::to(['player/view', 'id' => $stat->player_id]) ?>" style="text-decoration: none; display: block;">
                        <div style="display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #333;" class="ranking-item">
                            <div style="width: 25px; font-size: 18px; font-weight: bold; color: <?= $index < 3 ? '#e74c3c' : '#666' ?>;">
                                <?= $index + 1 ?>
                            </div>
                            <div style="width: 35px; height: 35px; border-radius: 50%; overflow: hidden; background: #333; margin: 0 10px;">
                                <?php if ($stat->player && $stat->player->avatar): ?>
                                    <img src="/uploads/players/<?= $stat->player->avatar ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php endif; ?>
                            </div>
                            <div style="flex: 1;">
                                <div style="color: #fff; font-size: 13px; font-weight: bold;">
                                    <?= Html::encode($stat->player->name ?? '未知') ?>
                                </div>
                                <div style="color: #888; font-size: 10px;">
                                    <?= Html::encode($stat->player->team->name ?? '') ?>
                                </div>
                            </div>
                            <div style="color: #e74c3c; font-weight: bold; font-size: 12px;">
                                <?= number_format($stat->top_rate * 100, 1) ?>%
                            </div>
                        </div>
                        </a>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 避四率排行 -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="ranking-card" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px; overflow: hidden;">
                    <div style="background: #00a550; padding: 15px; text-align: center;">
                        <h5 style="color: #fff; margin: 0; font-weight: bold;">避四率</h5>
                    </div>
                    <div style="padding: 15px;">
                        <div class="ranking-list" data-type="avoid_rate">
                        <?php foreach ($playerRankings['avoid_rate'] ?? [] as $index => $stat): ?>
                        <a href="<?= Url::to(['player/view', 'id' => $stat->player_id]) ?>" style="text-decoration: none; display: block;">
                        <div style="display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #333;" class="ranking-item">
                            <div style="width: 25px; font-size: 18px; font-weight: bold; color: <?= $index < 3 ? '#00a550' : '#666' ?>;">
                                <?= $index + 1 ?>
                            </div>
                            <div style="width: 35px; height: 35px; border-radius: 50%; overflow: hidden; background: #333; margin: 0 10px;">
                                <?php if ($stat->player && $stat->player->avatar): ?>
                                    <img src="/uploads/players/<?= $stat->player->avatar ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php endif; ?>
                            </div>
                            <div style="flex: 1;">
                                <div style="color: #fff; font-size: 13px; font-weight: bold;">
                                    <?= Html::encode($stat->player->name ?? '未知') ?>
                                </div>
                                <div style="color: #888; font-size: 10px;">
                                    <?= Html::encode($stat->player->team->name ?? '') ?>
                                </div>
                            </div>
                            <div style="color: #00a550; font-weight: bold; font-size: 12px;">
                                <?= number_format($stat->last_avoid_rate * 100, 1) ?>%
                            </div>
                        </div>
                        </a>
                        <?php endforeach; ?>
                        </div>
                        <div class="ranking-list-full" data-type="avoid_rate" style="display: none;">
                        <?php foreach ($playerRankings['avoid_rate_all'] ?? [] as $index => $stat): ?>
                        <a href="<?= Url::to(['player/view', 'id' => $stat->player_id]) ?>" style="text-decoration: none; display: block;">
                        <div style="display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #333;" class="ranking-item">
                            <div style="width: 25px; font-size: 18px; font-weight: bold; color: <?= $index < 3 ? '#00a550' : '#666' ?>;">
                                <?= $index + 1 ?>
                            </div>
                            <div style="width: 35px; height: 35px; border-radius: 50%; overflow: hidden; background: #333; margin: 0 10px;">
                                <?php if ($stat->player && $stat->player->avatar): ?>
                                    <img src="/uploads/players/<?= $stat->player->avatar ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php endif; ?>
                            </div>
                            <div style="flex: 1;">
                                <div style="color: #fff; font-size: 13px; font-weight: bold;">
                                    <?= Html::encode($stat->player->name ?? '未知') ?>
                                </div>
                                <div style="color: #888; font-size: 10px;">
                                    <?= Html::encode($stat->player->team->name ?? '') ?>
                                </div>
                            </div>
                            <div style="color: #00a550; font-weight: bold; font-size: 12px;">
                                <?= number_format($stat->last_avoid_rate * 100, 1) ?>%
                            </div>
                        </div>
                        </a>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 展开按钮 -->
            <div class="col-12 text-center mt-3">
                <button id="toggleRankingBtn" class="btn" style="background: #d4af37; color: #000; font-weight: bold; padding: 10px 40px; border-radius: 25px;">
                    <span class="btn-text">展开完整榜单</span>
                    <i class="fa fa-chevron-down ml-2"></i>
                </button>
            </div>
        </div>
        <?php endif; ?>

        <!-- 新闻预览 -->
        <?php if (!empty($latestNews)): ?>
        <div class="row mb-5">
            <div class="col-md-12">
                <h2 style="border-left: 5px solid #d4af37; padding-left: 15px; margin-bottom: 30px; color: #fff;">
                    最新新闻 <a href="<?= Url::to(['news/index']) ?>" style="font-size: 14px; color: #d4af37; margin-left: 20px;">查看更多 ></a>
                </h2>
            </div>
            
            <?php foreach ($latestNews as $news): ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <a href="<?= Url::to(['news/view', 'id' => $news->id]) ?>" style="text-decoration: none;">
                    <div class="news-card" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px; overflow: hidden; height: 100%;">
                        <div style="height: 150px; background: #333; overflow: hidden;">
                            <?php if ($news->cover): ?>
                                <img src="/uploads/news/cover/<?= $news->cover ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #666;">
                                    <i class="fa fa-newspaper-o" style="font-size: 40px;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div style="padding: 15px;">
                            <div style="color: #888; font-size: 12px; margin-bottom: 8px;">
                                <?= date('Y-m-d', strtotime($news->publish_time)) ?>
                            </div>
                            <h5 style="color: #fff; font-size: 14px; line-height: 1.5; margin: 0; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                <?= Html::encode($news->title) ?>
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- 战队排行榜 -->
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
                                            <a href="<?= Url::to(['team/view', 'id' => $stat->team_id]) ?>" style="font-size: 18px; font-weight: bold; color: #fff; text-decoration: none;">
                                                <?= Html::encode($stat->team->name) ?>
                                            </a>
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
            
            <?php foreach ($allTeams ?? [] as $team): ?>
            <div class="col-lg-3 col-md-4 col-sm-6" style="margin-bottom: 20px;">
                <a href="<?= Url::to(['team/view', 'id' => $team->id]) ?>" style="text-decoration: none;">
                    <div class="team-card" style="border: 1px solid #333; border-radius: 5px; overflow: hidden; background: #1a1a1a;">
                        
                        <div style="height: 120px; display: flex; align-items: center; justify-content: center; background: #fff; border-bottom: 1px solid #d4af37;">
                            <?php if ($team->logo): ?>
                                <img src="/uploads/teams/<?= $team->logo ?>" style="width: 100%; height: 100%; object-fit: contain; padding: 15px;">
                            <?php else: ?>
                                <span style="color: #333; font-weight: 900; font-size: 30px; letter-spacing: -2px;">M.L</span>
                            <?php endif; ?>
                        </div>
                        
                        <div style="padding: 15px; text-align: center;">
                            <h4 style="color: #fff; margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase;">
                                <?= Html::encode($team->name) ?>
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

<?php
$js = <<<JS
$(document).ready(function() {
    var isExpanded = false;
    
    $('#toggleRankingBtn').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        
        console.log('Toggle button clicked, isExpanded:', isExpanded);
        
        if (!isExpanded) {
            // 展开 - 隐藏前5名列表，显示完整列表
            $('.ranking-list').slideUp(300);
            $('.ranking-list-full').slideDown(300);
            btn.find('.btn-text').text('收起榜单');
            btn.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            isExpanded = true;
        } else {
            // 收起 - 显示前5名列表，隐藏完整列表
            $('.ranking-list-full').slideUp(300);
            $('.ranking-list').slideDown(300);
            btn.find('.btn-text').text('展开完整榜单');
            btn.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            isExpanded = false;
        }
    });
    
    // 悬停效果
    $('.ranking-item').hover(
        function() {
            $(this).css({'background': '#222', 'transform': 'scale(1.02)'});
        },
        function() {
            $(this).css({'background': 'transparent', 'transform': 'scale(1)'});
        }
    );
    
    console.log('Ranking toggle script loaded. Button found:', $('#toggleRankingBtn').length);
    console.log('ranking-list elements:', $('.ranking-list').length);
    console.log('ranking-list-full elements:', $('.ranking-list-full').length);
});
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>