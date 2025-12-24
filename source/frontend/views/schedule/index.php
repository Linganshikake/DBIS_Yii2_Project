<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the frontend schedule index view (前端赛程列表视图)
 */

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $upcomingSchedules common\models\Schedule[] */
/* @var $monthlySchedules common\models\Schedule[] */
/* @var $year int */
/* @var $month int */

$this->title = 'SCHEDULE - 赛程表';

$months = [9, 10, 11, 12, 1, 2, 3, 4, 5];
?>

<div class="schedule-index">

    <!-- 标题 -->
    <div class="text-center" style="background: #00a550; padding: 40px 0; margin: -20px -15px 40px;">
        <h1 style="color: #fff; font-weight: 900; letter-spacing: 5px; font-size: 48px; margin: 0;">SCHEDULE</h1>
        <p style="color: #fff; margin-top: 10px;">近期对战日程</p>
    </div>

    <!-- 近期比赛 -->
    <?php if ($upcomingSchedules): ?>
    <div class="upcoming-matches mb-5">
        <?php 
        // 按日期+队伍组合去重（同一天同样4支队伍只显示一次）
        $displayedMatches = [];
        foreach ($upcomingSchedules as $schedule): 
            // 生成唯一标识：日期 + 排序后的队伍ID组合
            $teamIds = [$schedule->team_id1, $schedule->team_id2, $schedule->team_id3, $schedule->team_id4];
            sort($teamIds);
            $matchKey = $schedule->match_date . '_' . implode('_', $teamIds);
            
            // 如果已显示过相同的比赛，跳过
            if (isset($displayedMatches[$matchKey])) {
                continue;
            }
            $displayedMatches[$matchKey] = true;
        ?>
        <div class="upcoming-item" style="display: flex; align-items: center; padding: 20px 0; border-bottom: 1px solid #333;">
            <div style="width: 120px; text-align: center;">
                <div style="font-size: 36px; font-weight: bold; color: #fff;">
                    <?= date('n/j', strtotime($schedule->match_date)) ?>
                </div>
                <div style="color: #888;">（<?= $schedule->day_of_week ?>）</div>
            </div>
            <div style="flex: 1; display: flex; justify-content: center; gap: 30px;">
                <?php 
                $teams = [$schedule->team1, $schedule->team2, $schedule->team3, $schedule->team4];
                foreach ($teams as $team): 
                    if (!$team) continue;
                ?>
                <div style="text-align: center;">
                    <?php if ($team->logo): ?>
                        <img src="/uploads/teams/<?= $team->logo ?>" style="width: 80px; height: 60px; object-fit: contain;">
                    <?php else: ?>
                        <div style="width: 80px; height: 60px; background: #333; display: flex; align-items: center; justify-content: center; color: #666;">LOGO</div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div style="color: #888; font-size: 12px; padding: 10px 0 20px 120px;">
            <?= Html::encode($schedule->team1->name ?? '') ?> / 
            <?= Html::encode($schedule->team2->name ?? '') ?> / 
            <?= Html::encode($schedule->team3->name ?? '') ?> / 
            <?= Html::encode($schedule->team4->name ?? '') ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- 月份选择 -->
    <div class="month-selector text-center mb-4">
        <?php foreach ($months as $m): ?>
            <?php 
            $y = $m >= 9 ? 2025 : 2026; 
            $isActive = ($year == $y && $month == $m);
            ?>
            <a href="<?= Url::to(['schedule/index', 'year' => $y, 'month' => $m]) ?>" 
               style="display: inline-block; padding: 10px 20px; margin: 5px; border: 2px solid <?= $isActive ? '#00a550' : '#333' ?>; border-radius: 30px; color: <?= $isActive ? '#00a550' : '#fff' ?>; text-decoration: none; font-weight: bold;">
                <?= $m ?>月
            </a>
        <?php endforeach; ?>
    </div>

    <!-- 月度日程卡片 -->
    <div class="monthly-schedules">
        <div class="row" style="row-gap: 30px;">
            <?php foreach ($monthlySchedules as $schedule): ?>
            <div class="col-lg-3 col-md-4 col-sm-6" style="margin-bottom: 30px;">
                <div class="schedule-card" style="background: #fff; border: 2px solid #00a550; border-radius: 10px; padding: 20px; cursor: pointer; height: 100%;" 
                     data-id="<?= $schedule->id ?>" onclick="showScheduleDetail(<?= $schedule->id ?>)">
                    
                    <div class="text-center mb-3">
                        <span style="font-size: 36px; font-weight: 900; color: #00a550;">
                            <?= date('n/j', strtotime($schedule->match_date)) ?>
                        </span>
                        <span style="color: #666;">（<?= mb_substr($schedule->day_of_week, 2) ?>）</span>
                    </div>
                    
                    <div class="row">
                        <?php 
                        $teams = [
                            $schedule->team_id1 => $schedule->team1,
                            $schedule->team_id2 => $schedule->team2,
                            $schedule->team_id3 => $schedule->team3,
                            $schedule->team_id4 => $schedule->team4,
                        ];
                        foreach ($teams as $teamId => $team): 
                            if (!$team) continue;
                            $isTop = $schedule->top_team_id == $teamId;
                        ?>
                        <div class="col-6 mb-2 text-center">
                            <div style="opacity: <?= $isTop ? '1' : ($schedule->top_team_id ? '0.3' : '1') ?>;">
                                <?php if ($team->logo): ?>
                                    <img src="/uploads/teams/<?= $team->logo ?>" style="width: 60px; height: 45px; object-fit: contain;">
                                <?php else: ?>
                                    <div style="width: 60px; height: 45px; background: #eee; margin: 0 auto; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #999;">LOGO</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($monthlySchedules)): ?>
            <div class="col-12 text-center" style="padding: 50px; color: #666;">
                本月暂无比赛日程
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- 成绩弹窗 -->
<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: #fff; color: #333; border-radius: 15px; overflow: hidden;">
            <div class="modal-header" style="border-bottom: 1px solid #ddd; background: #f5f5f5;">
                <h5 class="modal-title" style="color: #333; font-weight: bold;">比赛详情</h5>
                <button type="button" class="close" data-dismiss="modal" style="color: #333; font-size: 30px; opacity: 1; background: #00a550; border-radius: 50%; width: 35px; height: 35px; line-height: 35px; text-align: center; padding: 0;">
                    <span style="color: #fff;">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="scheduleModalBody" style="padding: 20px;">
                <!-- 内容通过AJAX加载 -->
            </div>
        </div>
    </div>
</div>

<script>
function showScheduleDetail(scheduleId) {
    $.get('<?= Url::to(['schedule/detail']) ?>', {id: scheduleId}, function(data) {
        if (data.error) {
            alert(data.error);
            return;
        }
        
        var html = '<div class="row">';
        
        // 如果有成绩
        if (data.scores && data.scores.length > 0) {
            data.scores.forEach(function(score, idx) {
                // 第一回战用浅灰色背景，第二回战用浅蓝色背景
                var bgColor = idx === 0 ? '#f5f5f5' : '#e8f4fc';
                var headerColor = idx === 0 ? '#666' : '#3498db';
                
                html += '<div class="col-md-6">';
                html += '<div style="background: ' + bgColor + '; border-radius: 10px; padding: 15px; margin-bottom: 10px;">';
                html += '<h5 style="text-align: center; color: ' + headerColor + '; margin-bottom: 20px; font-weight: bold; border-bottom: 2px solid ' + headerColor + '; padding-bottom: 10px;">' + score.game_text + '</h5>';
                
                score.results.forEach(function(result, rank) {
                    var rankColors = ['#e74c3c', '#27ae60', '#3498db', '#666'];
                    var rankBgs = ['#fff3cd', '#d4edda', '#d6eaf8', '#e2e3e5'];
                    html += '<div style="display: flex; align-items: center; padding: 10px; margin-bottom: 8px; background: ' + rankBgs[rank] + '; border-radius: 8px;">';
                    html += '<div style="width: 30px; height: 30px; background: ' + rankColors[rank] + '; color: #fff; display: flex; align-items: center; justify-content: center; border-radius: 5px; font-weight: bold;">' + (rank + 1) + '</div>';
                    html += '<div style="width: 50px; height: 50px; margin: 0 15px; border-radius: 50%; overflow: hidden; background: #fff; border: 2px solid #ddd;">';
                    if (result.player_avatar) {
                        html += '<img src="' + result.player_avatar + '" style="width: 100%; height: 100%; object-fit: cover;">';
                    }
                    html += '</div>';
                    html += '<div style="width: 45px; height: 35px; margin-right: 10px; overflow: hidden;">';
                    if (result.team_logo) {
                        html += '<img src="' + result.team_logo + '" style="width: 100%; height: 100%; object-fit: contain;">';
                    }
                    html += '</div>';
                    html += '<div style="flex: 1;">';
                    html += '<div style="font-weight: bold; color: #333;">' + result.player_name + '</div>';
                    html += '</div>';
                    html += '<div style="font-size: 18px; font-weight: bold; color: ' + (result.score >= 0 ? '#00a550' : '#e74c3c') + ';">';
                    html += (result.score >= 0 ? '' : '▲') + Math.abs(result.score).toFixed(1) + 'pt';
                    html += '</div>';
                    html += '</div>';
                });
                
                html += '</div>';
                html += '</div>';
            });
        } else {
            html += '<div class="col-12 text-center" style="padding: 50px; color: #666;">比赛尚未开始或暂无成绩</div>';
        }
        
        html += '</div>';
        
        $('#scheduleModalBody').html(html);
        $('#scheduleModal').modal('show');
    });
}
</script>
