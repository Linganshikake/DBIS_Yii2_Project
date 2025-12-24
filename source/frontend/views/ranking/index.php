<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the frontend ranking index view (前端排行榜视图)
 */
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\EChartsAsset;

// 注册 ECharts 资源
EChartsAsset::register($this);

/* @var $this yii\web\View */
/* @var $season common\models\Season */
/* @var $teamRankings common\models\TeamSeasonStat[] */
/* @var $playerRankings common\models\PlayerSeasonStat[] */
/* @var $mvpPlayer common\models\PlayerSeasonStat|null */
/* @var $avoidPlayer common\models\PlayerSeasonStat|null */
/* @var $maxScorePlayer common\models\PlayerSeasonStat|null */

$this->title = 'SEASON RANKINGS';

// 定义统一的卡片样式
$cardStyle = "background: linear-gradient(135deg, #222, #000); border: 1px solid #d4af37; padding: 20px; text-align: center; border-radius: 8px; position: relative; overflow: hidden; box-shadow: 0 0 15px rgba(212, 175, 55, 0.15);";
$avatarStyle = "width: 100px; height: 100px; border-radius: 50%; border: 3px solid #d4af37; margin: 0 auto 15px; overflow: hidden; background: #333;";
?>

<div class="ranking-index">

    <div class="row mb-5 mt-4">
        <div class="col-12 text-center">
            <h4 style="color: #d4af37; letter-spacing: 2px; font-weight: bold;">SEASON <?= Html::encode($season ? $season->name : '---') ?></h4>
            <h1 style="color: #fff; font-weight: 900; font-size: 48px; text-shadow: 0 0 20px rgba(212, 175, 55, 0.3);">
                OFFICIAL RANKINGS
            </h1>
        </div>
    </div>

    <?php if ($mvpPlayer): ?>
    <div class="row justify-content-center" style="margin-bottom: 60px;">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="title-card" style="<?= $cardStyle ?>">
                <div style="color: #d4af37; font-weight: 900; letter-spacing: 2px; font-size: 14px; margin-bottom: 15px;">SEASON MVP</div>
                <div style="<?= $avatarStyle ?>">
                    <?php if ($mvpPlayer->player->avatar): ?>
                        <img src="/uploads/players/<?= $mvpPlayer->player->avatar ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: top;">
                    <?php else: ?>
                        <div style="height: 100%; display: flex; align-items: center; justify-content: center; color: #666;"><i class="fas fa-user fa-2x"></i></div>
                    <?php endif; ?>
                </div>
                <div class="title-value text-warning" style="font-size: 32px; font-weight: bold;">
                    <?= $mvpPlayer->total_score > 0 ? '+' : '' ?><?= $mvpPlayer->total_score ?>
                </div>
                <div class="title-player" style="font-size: 20px; font-weight: bold; color: #fff; margin-top: 5px;">
                    <a href="<?= Url::to(['player/view', 'id' => $mvpPlayer->player_id]) ?>" style="color: #fff; text-decoration: none;">
                        <?= Html::encode($mvpPlayer->player->name) ?>
                    </a>
                </div>
                <div class="title-team" style="color: #888; font-size: 12px; margin-top: 5px;">
                    <?= Html::encode($mvpPlayer->team->name) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="title-card" style="<?= $cardStyle ?>">
                <div style="color: #d4af37; font-weight: 900; letter-spacing: 1px; font-size: 14px; margin-bottom: 15px;">4th PLACE AVOIDANCE</div>
                <div style="<?= $avatarStyle ?>">
                    <?php if ($avoidPlayer && $avoidPlayer->player->avatar): ?>
                        <img src="/uploads/players/<?= $avoidPlayer->player->avatar ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: top;">
                    <?php else: ?>
                        <div style="height: 100%; display: flex; align-items: center; justify-content: center; color: #666;"><i class="fas fa-shield-alt fa-2x"></i></div>
                    <?php endif; ?>
                </div>
                <div class="title-value" style="color: #fff; font-size: 32px; font-weight: bold;">
                    <?= $avoidPlayer ? number_format($avoidPlayer->last_avoid_rate * 100, 1) . '%' : '-' ?>
                </div>
                <div class="title-player" style="font-size: 20px; font-weight: bold; margin-top: 5px;">
                    <?php if ($avoidPlayer): ?>
                        <a href="<?= Url::to(['player/view', 'id' => $avoidPlayer->player_id]) ?>" style="color: #fff; text-decoration: none;">
                            <?= Html::encode($avoidPlayer->player->name) ?>
                        </a>
                    <?php else: ?>-<?php endif; ?>
                </div>
                <div class="title-team" style="color: #888; font-size: 12px; margin-top: 5px;">
                    <?= $avoidPlayer ? Html::encode($avoidPlayer->team->name) : '-' ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="title-card" style="<?= $cardStyle ?>">
                <div style="color: #d4af37; font-weight: 900; letter-spacing: 1px; font-size: 14px; margin-bottom: 15px;">HIGHEST SCORE</div>
                <div style="<?= $avatarStyle ?>">
                    <?php if ($maxScorePlayer && $maxScorePlayer->player->avatar): ?>
                        <img src="/uploads/players/<?= $maxScorePlayer->player->avatar ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: top;">
                    <?php else: ?>
                        <div style="height: 100%; display: flex; align-items: center; justify-content: center; color: #666;"><i class="fas fa-fire fa-2x"></i></div>
                    <?php endif; ?>
                </div>
                <div class="title-value" style="color: #fff; font-size: 32px; font-weight: bold;">
                    <?= $maxScorePlayer ? number_format($maxScorePlayer->max_score) : '-' ?>
                </div>
                <div class="title-player" style="font-size: 20px; font-weight: bold; margin-top: 5px;">
                    <?php if ($maxScorePlayer): ?>
                        <a href="<?= Url::to(['player/view', 'id' => $maxScorePlayer->player_id]) ?>" style="color: #fff; text-decoration: none;">
                            <?= Html::encode($maxScorePlayer->player->name) ?>
                        </a>
                    <?php else: ?>-<?php endif; ?>
                </div>
                <div class="title-team" style="color: #888; font-size: 12px; margin-top: 5px;">
                    <?= $maxScorePlayer ? Html::encode($maxScorePlayer->team->name) : '-' ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <ul class="nav nav-tabs ranking-nav-tabs justify-content-center" id="rankingTab" style="border-bottom: 1px solid #333; margin-bottom: 30px;">
        <li class="nav-item">
            <a class="nav-link active custom-tab-trigger" href="javascript:void(0);" data-target="#team"
                style="background: transparent; color: #fff; font-weight: bold; border: none; border-bottom: 3px solid transparent; font-size: 18px; padding: 10px 30px; cursor: pointer; transition: color 0.3s;">
                TEAM RANKING
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link custom-tab-trigger" href="javascript:void(0);" data-target="#player"
                style="background: transparent; color: #888; font-weight: bold; border: none; border-bottom: 3px solid transparent; font-size: 18px; padding: 10px 30px; cursor: pointer; transition: color 0.3s;">
                INDIVIDUAL RANKING
            </a>
        </li>
    </ul>

    <div class="tab-content" id="rankingTabContent" style="min-height: 500px; position: relative;">
        
        <div class="tab-pane fade show active" id="team" style="transition: opacity 0.3s ease-in-out;">
            <div class="table-responsive">
                <table class="table table-dark table-hover" style="background: #111; border: 1px solid #333;">
                    <thead>
                        <tr style="background: #000; color: #d4af37; border-bottom: 2px solid #d4af37;">
                            <th class="text-center" width="10%">Rank</th>
                            <th width="50%">Team Name</th>
                            <th class="text-center">Total Score</th>
                            <th class="text-center">Stage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($teamRankings)): ?>
                            <?php foreach ($teamRankings as $index => $stat): ?>
                                <?php $score = $stat->getDisplayScore(); ?>
                                <tr>
                                    <td class="text-center" style="font-size: 24px; font-weight: bold; vertical-align: middle; color: <?= $index < 4 ? '#d4af37' : '#fff' ?>">
                                        <?= $index + 1 ?>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <div style="display: flex; align-items: center;">
                                            <div style="width: 40px; height: 40px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; overflow: hidden;">
                                                <?php if ($stat->team->logo): ?>
                                                    <img src="/uploads/teams/<?= $stat->team->logo ?>" style="width: 100%; height: 100%; object-fit: contain;">
                                                <?php else: ?>
                                                    <span style="color: #000; font-size: 10px; font-weight: bold;">ML</span>
                                                <?php endif; ?>
                                            </div>
                                            <a href="<?= Url::to(['team/view', 'id' => $stat->team_id]) ?>" style="color: #fff; text-decoration: none; font-size: 18px; font-weight: bold;">
                                                <?= Html::encode($stat->team->name) ?>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="text-center" style="font-size: 20px; font-weight: bold; vertical-align: middle; color: <?= $score >= 0 ? '#fff' : '#aaa' ?>">
                                        <?= $score > 0 ? '+' : '' ?><?= $score ?>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <?php 
                                            if ($stat->final_score !== null) {
                                                echo "<span class='badge' style='background: #d4af37; color: #000; font-size: 12px; padding: 5px 10px; font-weight: 900;'>FINAL</span>";
                                            } elseif ($stat->semifinal_score !== null) {
                                                echo "<span class='badge badge-secondary' style='padding: 5px 10px;'>SEMI</span>";
                                            } else {
                                                echo "<span style='color: #666; font-size: 12px;'>REGULAR</span>";
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center" style="padding: 40px; color: #666;">暂无队伍数据</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- 队伍积分柱状图 -->
            <?php if (!empty($teamRankings)): ?>
            <div style="margin-top: 40px;">
                <h4 style="color: #d4af37; margin-bottom: 20px; text-align: center; font-weight: bold;">
                    <i class="fa fa-bar-chart" style="margin-right: 10px;"></i>TEAM SCORE CHART
                </h4>
                <div id="teamScoreChart" style="width: 100%; height: 400px; background: #1a1a1a; border: 1px solid #333; border-radius: 10px;"></div>
            </div>
            <?php endif; ?>
        </div>

        <div class="tab-pane fade" id="player" style="transition: opacity 0.3s ease-in-out;">
            <div class="table-responsive">
                <table class="table table-dark table-hover" style="background: #111; border: 1px solid #333;">
                    <thead>
                        <tr style="background: #000; color: #d4af37; border-bottom: 2px solid #d4af37;">
                            <th class="text-center" width="10%">Rank</th>
                            <th width="30%">Player</th>
                            <th width="20%">Team</th>
                            <th class="text-center">Total Score</th>
                            <th class="text-center">Games COUNT</th>
                            <th class="text-center">1st Rate</th>
                            <th class="text-center">4th Avoid Rate</th>
                            <th class="text-center">Max Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($playerRankings)): ?>
                            <?php foreach ($playerRankings as $index => $stat): ?>
                                <tr>
                                    <td class="text-center" style="font-size: 20px; font-weight: bold; vertical-align: middle; color: <?= $index < 3 ? '#d4af37' : '#fff' ?>">
                                        <?= $index + 1 ?>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <div style="display: flex; align-items: center;">
                                            <div style="width: 35px; height: 35px; background: #333; border-radius: 50%; margin-right: 10px; overflow: hidden; border: 1px solid #555;">
                                                <?php if ($stat->player->avatar): ?>
                                                    <img src="/uploads/players/<?= $stat->player->avatar ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: top;">
                                                <?php else: ?>
                                                    <div style="height: 100%; display: flex; align-items: center; justify-content: center; color: #888; font-size: 10px;"><i class="fas fa-user"></i></div>
                                                <?php endif; ?>
                                            </div>
                                            <a href="<?= Url::to(['player/view', 'id' => $stat->player_id]) ?>" style="color: #fff; font-weight: bold; text-decoration: none;">
                                                <?= Html::encode($stat->player->name) ?>
                                            </a>
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <a href="<?= Url::to(['team/view', 'id' => $stat->team_id]) ?>" style="color: #aaa; font-size: 14px; text-decoration: none;">
                                            <?= Html::encode($stat->team->name) ?>
                                        </a>
                                    </td>
                                    <td class="text-center" style="font-size: 18px; font-weight: bold; vertical-align: middle; color: <?= $stat->total_score >= 0 ? '#fff' : '#aaa' ?>">
                                        <?= $stat->total_score > 0 ? '+' : '' ?><?= $stat->total_score ?>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;"><?= $stat->games_count ?></td>
                                    <td class="text-center" style="color: #d4af37; vertical-align: middle;">
                                        <?= $stat->top_rate ? ($stat->top_rate * 100) . '%' : '-' ?>
                                    </td>

                                    <td class="text-center" style="color: #d4af37; vertical-align: middle;">
                                        <?= $stat->last_avoid_rate ? ($stat->last_avoid_rate * 100) . '%' : '-' ?>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <?= number_format($stat->max_score) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center" style="padding: 40px; color: #666;">暂无个人数据</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php
$js = <<<JS
    $('.custom-tab-trigger').on('click', function(e) {
        e.preventDefault(); 
        
        var \$this = $(this);
        var targetSelector = \$this.data('target');
        var \$target = $(targetSelector);
        var \$activeTab = $('.tab-pane.active');

        // 如果点的就是当前激活的Tab，什么都不做
        if (\$this.hasClass('active')) return;

        // 1. 切换按钮样式
        $('.custom-tab-trigger').removeClass('active').css('color', '#888');
        \$this.addClass('active').css('color', '#fff');

        // 2. 丝滑切换逻辑 (Fade Out -> Fade In)
        
        // 第一步：让当前内容淡出 (移除 show 类，opacity 变为 0)
        \$activeTab.removeClass('show');

        // 等待过渡动画 (CSS transition 0.3s)
        setTimeout(function() {
            // 第二步：隐藏当前内容 (display: none)
            \$activeTab.removeClass('active');

            // 第三步：让新内容准备好 (display: block, 但 opacity 还是 0)
            \$target.addClass('active');

            // 稍微停顿一下，让浏览器渲染 display:block，否则 fade 效果出不来
            setTimeout(function() {
                // 第四步：淡入新内容 (opacity 变为 1)
                \$target.addClass('show');
            }, 50);

        }, 300); // 300ms 对应 CSS 里的 transition 时间
    });
JS;
$this->registerJs($js);

// 准备队伍积分图表数据
$teamNames = [];
$teamScores = [];
$teamColors = [];
if (!empty($teamRankings)) {
    foreach ($teamRankings as $stat) {
        $teamNames[] = $stat->team->name;
        // 确保分数不是 null，如果是 null 则用 0
        $score = $stat->total_score;
        $score = ($score !== null && $score !== '') ? floatval($score) : 0;
        $teamScores[] = $score;
        // 正分绿色，负分红色
        $teamColors[] = $score >= 0 ? '#00a550' : '#e74c3c';
    }
}

// 直接输出图表数据
$teamNamesJson = json_encode($teamNames, JSON_UNESCAPED_UNICODE);
// 构建带颜色的数据数组
$teamDataArray = [];
for ($i = 0; $i < count($teamScores); $i++) {
    $teamDataArray[] = [
        'value' => $teamScores[$i],
        'itemStyle' => ['color' => $teamColors[$i]]
    ];
}
$teamDataJson = json_encode($teamDataArray);

$echartsJs = <<<JS
// 初始化队伍积分柱状图
var teamChartDom = document.getElementById('teamScoreChart');
if (teamChartDom) {
    var teamChart = echarts.init(teamChartDom, 'dark');
    var teamOption = {
        backgroundColor: 'transparent',
        title: {
            text: 'TEAM TOTAL SCORE',
            left: 'center',
            top: 10,
            textStyle: {
                color: '#d4af37',
                fontSize: 16,
                fontWeight: 'bold'
            }
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: { type: 'shadow' },
            formatter: function(params) {
                var data = params[0];
                var score = data.value;
                var sign = score >= 0 ? '+' : '';
                return data.name + '<br/>Score: <strong>' + sign + score.toFixed(1) + '</strong>';
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '15%',
            top: '15%',
            containLabel: true
        },
        xAxis: {
            type: 'category',
            data: {$teamNamesJson},
            axisLabel: {
                color: '#aaa',
                rotate: 30,
                fontSize: 11
            },
            axisLine: { lineStyle: { color: '#333' } }
        },
        yAxis: {
            type: 'value',
            name: 'Score',
            nameTextStyle: { color: '#888' },
            axisLabel: { color: '#aaa' },
            axisLine: { lineStyle: { color: '#333' } },
            splitLine: { lineStyle: { color: '#222' } }
        },
        series: [{
            name: 'Score',
            type: 'bar',
            data: {$teamDataJson},
            barWidth: '50%',
            itemStyle: {
                borderRadius: [4, 4, 0, 0]
            },
            label: {
                show: true,
                position: 'top',
                color: '#d4af37',
                fontSize: 11,
                formatter: function(params) {
                    var v = params.value;
                    return (v >= 0 ? '+' : '') + v.toFixed(1);
                }
            }
        }]
    };
    teamChart.setOption(teamOption);
    
    // 响应式调整
    window.addEventListener('resize', function() {
        teamChart.resize();
    });
}
JS;
$this->registerJs($echartsJs, \yii\web\View::POS_END);
?>