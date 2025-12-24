<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the frontend profile index view (前端个人资料页)
 */

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $teams common\models\Team[] */
/* @var $userComments common\models\Comment[] */
/* @var $favoriteTeamPlayers common\models\Player[] */
/* @var $favoriteTeamSeasonStat common\models\TeamSeasonStat|null */
/* @var $favoriteTeamSchedules common\models\Schedule[] */

$this->title = '个人主页';
?>

<div class="profile-index">

    <!-- 顶部头像区域 -->
    <div class="profile-header text-center" style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); padding: 50px 20px; border-radius: 10px; margin-bottom: 40px; position: relative;">
        
        <!-- 头像 -->
        <div class="avatar-wrapper" style="position: relative; display: inline-block;">
            <div style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; border: 4px solid #d4af37; margin: 0 auto;">
                <?php if ($user->avatar): ?>
                    <img src="/uploads/avatars/<?= $user->avatar ?>" style="width: 100%; height: 100%; object-fit: cover;" id="user-avatar">
                <?php else: ?>
                    <div style="width: 100%; height: 100%; background: #333; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-user" style="font-size: 50px; color: #666;"></i>
                    </div>
                <?php endif; ?>
            </div>
            <label for="avatar-upload" style="position: absolute; bottom: 5px; right: 5px; width: 30px; height: 30px; background: #d4af37; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                <i class="fa fa-camera" style="color: #000; font-size: 14px;"></i>
            </label>
            <input type="file" id="avatar-upload" style="display: none;" accept="image/*">
        </div>
        
        <!-- 用户名/昵称 -->
        <h2 style="color: #fff; margin-top: 20px; font-weight: bold;">
            <?= Html::encode($user->nickname ?: $user->username) ?>
        </h2>
        
        <!-- 个人简介 -->
        <?php if ($user->bio): ?>
        <p style="color: #888; max-width: 500px; margin: 15px auto 0;">
            <?= Html::encode($user->bio) ?>
        </p>
        <?php endif; ?>
        
        <!-- 注册时间 -->
        <div style="color: #666; font-size: 12px; margin-top: 15px;">
            <i class="fa fa-clock-o"></i> 注册于 <?= is_numeric($user->created_at) ? date('Y-m-d', $user->created_at) : (is_string($user->created_at) ? substr($user->created_at, 0, 10) : $user->created_at) ?>
        </div>
        
        <!-- 编辑和退出按钮 -->
        <div style="position: absolute; top: 20px; right: 20px; display: flex; flex-direction: column; gap: 10px;">
            <a href="<?= Url::to(['profile/update']) ?>" class="btn btn-outline-warning" style="font-size: 15px; padding: 8px 16px;">
                <i class="fa fa-edit"></i> 编辑资料
            </a>
            <?= Html::a('<i class="fa fa-sign-out"></i> 退出登录', ['/site/logout'], [
                'data-method' => 'post',
                'class' => 'btn btn-outline-danger',
                'style' => 'font-size: 15px; padding: 8px 16px;'
            ]) ?>
        </div>
    </div>

    <div class="row">
        <!-- 左侧：个人信息卡片 -->
        <div class="col-lg-4">
            
            <!-- 喜欢的战队 -->
            <div class="card mb-4" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px;">
                <div class="card-header" style="background: #222; border-bottom: 1px solid #333; padding: 15px 20px;">
                    <h5 style="color: #d4af37; margin: 0; font-weight: bold;">
                        <i class="fa fa-heart"></i> 我喜欢的战队
                    </h5>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <?php if ($user->favoriteTeam): ?>
                    <div class="favorite-team" style="display: flex; align-items: center;">
                        <div style="width: 60px; height: 60px; background: #fff; border-radius: 10px; overflow: hidden; margin-right: 15px;">
                            <?php if ($user->favoriteTeam->logo): ?>
                                <img src="/uploads/teams/<?= $user->favoriteTeam->logo ?>" style="width: 100%; height: 100%; object-fit: contain; padding: 5px;">
                            <?php endif; ?>
                        </div>
                        <div>
                            <h5 style="color: #fff; margin: 0;"><?= Html::encode($user->favoriteTeam->name) ?></h5>
                            <div style="color: #888; font-size: 12px;"><?= Html::encode($user->favoriteTeam->company) ?></div>
                        </div>
                    </div>
                    <button class="btn btn-sm mt-3" style="background: linear-gradient(135deg, #FFD700, #FFA500); color: #000; border: none; font-weight: bold;" onclick="clearFavoriteTeam()">
                        <i class="fa fa-times"></i> 取消喜欢
                    </button>
                    <?php else: ?>
                    <p style="color: #666; margin-bottom: 15px;">还没有选择喜欢的战队</p>
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#teamSelectModal">
                        <i class="fa fa-plus"></i> 选择战队
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 账户信息 -->
            <div class="card mb-4" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px;">
                <div class="card-header" style="background: #222; border-bottom: 1px solid #333; padding: 15px 20px;">
                    <h5 style="color: #d4af37; margin: 0; font-weight: bold;">
                        <i class="fa fa-user"></i> 账户信息
                    </h5>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div class="info-item" style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #333;">
                        <span style="color: #888;">用户名</span>
                        <span style="color: #fff;"><?= Html::encode($user->username) ?></span>
                    </div>
                    <div class="info-item" style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #333;">
                        <span style="color: #888;">邮箱</span>
                        <span style="color: #fff;"><?= Html::encode($user->email) ?></span>
                    </div>
                    <div class="info-item" style="display: flex; justify-content: space-between; padding: 10px 0;">
                        <span style="color: #888;">账户状态</span>
                        <span style="color: #00a550;"><?= $user->status == 10 ? '正常' : '待验证' ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 右侧：我的评论 -->
        <div class="col-lg-8">
            <div class="card" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px;">
                <div class="card-header" style="background: #222; border-bottom: 1px solid #333; padding: 15px 20px;">
                    <h5 style="color: #d4af37; margin: 0; font-weight: bold;">
                        <i class="fa fa-comments"></i> 我的评论
                    </h5>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <?php if ($userComments): ?>
                        <?php foreach ($userComments as $comment): ?>
                        <div class="comment-item" style="padding: 15px 0; border-bottom: 1px solid #333;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div style="flex: 1;">
                                    <p style="color: #fff; margin-bottom: 10px; line-height: 1.6;">
                                        <?= Html::encode($comment->content) ?>
                                    </p>
                                    <div style="font-size: 12px; color: #666;">
                                        <i class="fa fa-clock-o"></i> <?= is_numeric($comment->created_at) ? date('Y-m-d H:i', $comment->created_at) : $comment->created_at ?>
                                        <span style="margin-left: 15px;"><i class="fa fa-heart"></i> <?= $comment->like_count ?> 赞</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center" style="padding: 50px; color: #666;">
                            <i class="fa fa-comment-o" style="font-size: 40px; margin-bottom: 15px; display: block;"></i>
                            暂无评论，去<a href="<?= Url::to(['comment/index']) ?>" style="color: #d4af37;">评论广场</a>发表你的第一条评论吧！
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 喜欢战队的详细信息 -->
    <?php if ($user->favoriteTeam): ?>
    <div class="favorite-team-details" style="margin-top: 40px;">
        
        <!-- 战队选手卡片 -->
        <?php if ($favoriteTeamPlayers): ?>
        <div class="card mb-4" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px;">
            <div class="card-header" style="background: #222; border-bottom: 1px solid #333; padding: 15px 20px;">
                <h5 style="color: #d4af37; margin: 0; font-weight: bold;">
                    <i class="fa fa-users"></i> <?= Html::encode($user->favoriteTeam->name) ?> - TEAM MEMBERS
                </h5>
            </div>
            <div class="card-body" style="padding: 20px;">
                <div class="row">
                    <?php foreach ($favoriteTeamPlayers as $player): ?>
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
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- 战队本赛季成绩 -->
        <?php if ($favoriteTeamSeasonStat): ?>
        <div class="card mb-4" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px;">
            <div class="card-header" style="background: #222; border-bottom: 1px solid #333; padding: 15px 20px;">
                <h5 style="color: #d4af37; margin: 0; font-weight: bold;">
                    <i class="fa fa-trophy"></i> <?= Html::encode($user->favoriteTeam->name) ?> - SEASON STATISTICS
                </h5>
            </div>
            <div class="card-body" style="padding: 25px;">
                <div style="display: flex; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 140px;">
                        <div style="background: #222; border-radius: 10px; padding: 25px 15px; text-align: center;">
                            <div style="color: #888; font-size: 12px; margin-bottom: 12px;">REGULAR SCORE</div>
                            <div style="color: #fff; font-size: 28px; font-weight: bold;">
                                <?= $favoriteTeamSeasonStat->regular_score !== null ? ($favoriteTeamSeasonStat->regular_score >= 0 ? '+' : '') . $favoriteTeamSeasonStat->regular_score : '-' ?>
                            </div>
                        </div>
                    </div>
                    <div style="flex: 1; min-width: 140px;">
                        <div style="background: #222; border-radius: 10px; padding: 25px 15px; text-align: center;">
                            <div style="color: #888; font-size: 12px; margin-bottom: 12px;">SEMIFINAL SCORE</div>
                            <div style="color: #fff; font-size: 28px; font-weight: bold;">
                                <?= $favoriteTeamSeasonStat->semifinal_score !== null ? ($favoriteTeamSeasonStat->semifinal_score >= 0 ? '+' : '') . $favoriteTeamSeasonStat->semifinal_score : '-' ?>
                            </div>
                        </div>
                    </div>
                    <div style="flex: 1; min-width: 140px;">
                        <div style="background: #222; border-radius: 10px; padding: 25px 15px; text-align: center;">
                            <div style="color: #888; font-size: 12px; margin-bottom: 12px;">FINAL SCORE</div>
                            <div style="color: #fff; font-size: 28px; font-weight: bold;">
                                <?= $favoriteTeamSeasonStat->final_score !== null ? ($favoriteTeamSeasonStat->final_score >= 0 ? '+' : '') . $favoriteTeamSeasonStat->final_score : '-' ?>
                            </div>
                        </div>
                    </div>
                    <div style="flex: 1; min-width: 140px;">
                        <div style="background: linear-gradient(135deg, #d4af37, #b8860b); border-radius: 10px; padding: 25px 15px; text-align: center;">
                            <div style="color: #000; font-size: 12px; margin-bottom: 12px;">TOTAL SCORE</div>
                            <div style="color: #000; font-size: 28px; font-weight: bold;">
                                <?= $favoriteTeamSeasonStat->total_score !== null ? ($favoriteTeamSeasonStat->total_score >= 0 ? '+' : '') . $favoriteTeamSeasonStat->total_score : '-' ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($favoriteTeamSeasonStat->total_rank): ?>
                <div class="text-center" style="margin-top: 25px;">
                    <span style="background: #333; color: #d4af37; padding: 10px 30px; border-radius: 20px; font-size: 16px; font-weight: bold;">
                        CURRENT RANK: #<?= $favoriteTeamSeasonStat->total_rank ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- 战队近期日程 -->
        <?php if ($favoriteTeamSchedules): ?>
        <div class="card mb-4" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px;">
            <div class="card-header" style="background: #222; border-bottom: 1px solid #333; padding: 15px 20px;">
                <h5 style="color: #d4af37; margin: 0; font-weight: bold;">
                    <i class="fa fa-calendar"></i> <?= Html::encode($user->favoriteTeam->name) ?> - UPCOMING MATCHES
                </h5>
            </div>
            <div class="card-body" style="padding: 20px;">
                <?php foreach ($favoriteTeamSchedules as $schedule): ?>
                <div class="schedule-item" style="display: flex; align-items: center; padding: 15px; margin-bottom: 10px; background: #222; border-radius: 10px; border-left: 4px solid #d4af37;">
                    <div style="width: 100px; text-align: center;">
                        <div style="font-size: 28px; font-weight: bold; color: #00a550;">
                            <?= date('n/j', strtotime($schedule->match_date)) ?>
                        </div>
                        <div style="color: #888; font-size: 12px;">（<?= $schedule->day_of_week ?>）</div>
                    </div>
                    <div style="flex: 1; display: flex; justify-content: center; gap: 20px;">
                        <?php 
                        $teams = [$schedule->team1, $schedule->team2, $schedule->team3, $schedule->team4];
                        foreach ($teams as $team): 
                            if (!$team) continue;
                            $isFavorite = $team->id == $user->favoriteTeam->id;
                        ?>
                        <div style="text-align: center; opacity: <?= $isFavorite ? '1' : '0.6' ?>;">
                            <?php if ($team->logo): ?>
                                <img src="/uploads/teams/<?= $team->logo ?>" style="width: 50px; height: 40px; object-fit: contain; <?= $isFavorite ? 'border: 2px solid #d4af37; border-radius: 5px; padding: 2px;' : '' ?>">
                            <?php else: ?>
                                <div style="width: 50px; height: 40px; background: #333; display: flex; align-items: center; justify-content: center; color: #666; font-size: 10px;">LOGO</div>
                            <?php endif; ?>
                            <div style="font-size: 10px; color: <?= $isFavorite ? '#d4af37' : '#888' ?>; margin-top: 5px; font-weight: <?= $isFavorite ? 'bold' : 'normal' ?>;">
                                <?= Html::encode($team->name) ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div style="width: 100px; text-align: center;">
                        <?php if ($schedule->match_status == 2): ?>
                            <span style="color: #666; font-size: 12px;">已结束</span>
                        <?php elseif ($schedule->match_status == 1): ?>
                            <span style="color: #00a550; font-weight: bold;">进行中</span>
                        <?php else: ?>
                            <span style="color: #d4af37; font-size: 12px;">即将开始</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <div class="text-center mt-3">
                    <a href="<?= Url::to(['schedule/index']) ?>" style="color: #d4af37; text-decoration: none;">
                        查看完整赛程 →
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
    <?php endif; ?>

</div>

<!-- 战队选择弹窗 -->
<div class="modal fade" id="teamSelectModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: #1a1a1a; color: #fff;">
            <div class="modal-header" style="border-bottom: 1px solid #333;">
                <h5 class="modal-title">选择你喜欢的战队</h5>
                <button type="button" class="close" data-dismiss="modal" style="color: #fff;">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($teams as $team): ?>
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="team-select-item" style="background: #222; border: 2px solid #333; border-radius: 10px; padding: 15px; cursor: pointer; text-align: center; transition: all 0.3s;"
                             onclick="setFavoriteTeam(<?= $team->id ?>)"
                             onmouseover="this.style.borderColor='#d4af37'"
                             onmouseout="this.style.borderColor='#333'">
                            <div style="width: 80px; height: 60px; background: #fff; border-radius: 5px; margin: 0 auto 10px; overflow: hidden;">
                                <?php if ($team->logo): ?>
                                    <img src="/uploads/teams/<?= $team->logo ?>" style="width: 100%; height: 100%; object-fit: contain; padding: 5px;">
                                <?php endif; ?>
                            </div>
                            <div style="color: #fff; font-weight: bold; font-size: 14px;"><?= Html::encode($team->name) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// 头像上传
document.getElementById('avatar-upload').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (!file) return;
    
    var formData = new FormData();
    formData.append('User[avatarFile]', file);
    formData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->csrfToken ?>');
    
    $.ajax({
        url: '<?= Url::to(['profile/upload-avatar']) ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        }
    });
});

// 设置喜欢的战队
function setFavoriteTeam(teamId) {
    $.post('<?= Url::to(['profile/set-favorite-team']) ?>', {
        team_id: teamId,
        '<?= Yii::$app->request->csrfParam ?>': '<?= Yii::$app->request->csrfToken ?>'
    }, function(data) {
        if (data.success) {
            $('#teamSelectModal').modal('hide');
            location.reload();
        } else {
            alert(data.message);
        }
    });
}

// 取消喜欢的战队
function clearFavoriteTeam() {
    if (!confirm('确定要取消喜欢的战队吗？')) return;
    
    $.post('<?= Url::to(['profile/set-favorite-team']) ?>', {
        team_id: '',
        '<?= Yii::$app->request->csrfParam ?>': '<?= Yii::$app->request->csrfToken ?>'
    }, function(data) {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    });
}
</script>
