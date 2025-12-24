<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the frontend profile controller (用户个人中心控制器)
 */

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\User;
use common\models\Team;
use common\models\Comment;
use common\models\Schedule;
use common\models\Season;
use common\models\TeamSeasonStat;

/**
 * ProfileController 处理用户个人主页
 */
class ProfileController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'set-favorite-team', 'upload-avatar'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'set-favorite-team' => ['post'],
                    'upload-avatar' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 个人主页
     * @return string
     */
    public function actionIndex()
    {
        $user = User::findOne(Yii::$app->user->id);
        $teams = Team::find()->where(['display_status' => 1])->orderBy(['name' => SORT_ASC])->all();
        
        // 获取用户的评论
        $userComments = Comment::find()
            ->where(['user_id' => $user->id, 'display_status' => 1])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(10)
            ->all();

        // 喜欢战队的相关信息
        $favoriteTeamPlayers = [];
        $favoriteTeamSeasonStat = null;
        $favoriteTeamSchedules = [];
        
        if ($user->favoriteTeam) {
            // 获取喜欢战队的选手
            $favoriteTeamPlayers = $user->favoriteTeam->getPlayers()
                ->where(['display_status' => 1])
                ->all();
            
            // 获取喜欢战队本赛季的成绩
            $currentSeason = Season::findOne(['is_current' => 1]);
            if ($currentSeason) {
                $favoriteTeamSeasonStat = TeamSeasonStat::findOne([
                    'team_id' => $user->favoriteTeam->id,
                    'season_id' => $currentSeason->id,
                ]);
            }
            
            // 获取涉及该队伍的最近三个比赛日的日程
            $favoriteTeamSchedules = Schedule::getUpcomingSchedulesByTeam($user->favoriteTeam->id, 3);
        }

        return $this->render('index', [
            'user' => $user,
            'teams' => $teams,
            'userComments' => $userComments,
            'favoriteTeamPlayers' => $favoriteTeamPlayers,
            'favoriteTeamSeasonStat' => $favoriteTeamSeasonStat,
            'favoriteTeamSchedules' => $favoriteTeamSchedules,
        ]);
    }

    /**
     * 更新个人信息
     * @return string|\yii\web\Response
     */
    public function actionUpdate()
    {
        $user = User::findOne(Yii::$app->user->id);
        
        if ($user->load(Yii::$app->request->post())) {
            // 处理头像上传
            $user->avatarFile = UploadedFile::getInstance($user, 'avatarFile');
            if ($user->avatarFile) {
                $user->uploadAvatar();
            }
            
            if ($user->save(false)) {
                Yii::$app->session->setFlash('success', '个人资料更新成功！');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'user' => $user,
        ]);
    }

    /**
     * 设置喜欢的战队 (AJAX)
     * @return array
     */
    public function actionSetFavoriteTeam()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $teamId = Yii::$app->request->post('team_id');
        $user = User::findOne(Yii::$app->user->id);
        
        if (!$user) {
            return ['success' => false, 'message' => '用户不存在'];
        }
        
        // 验证战队是否存在
        if ($teamId) {
            $team = Team::findOne($teamId);
            if (!$team) {
                return ['success' => false, 'message' => '战队不存在'];
            }
        }
        
        $user->favorite_team_id = $teamId ?: null;
        
        if ($user->save(false)) {
            return [
                'success' => true, 
                'message' => $teamId ? '已设置为喜欢的战队！' : '已取消喜欢的战队',
                'team_name' => $teamId ? $team->name : null,
            ];
        }
        
        return ['success' => false, 'message' => '设置失败'];
    }

    /**
     * 上传头像 (AJAX)
     * @return array
     */
    public function actionUploadAvatar()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $user = User::findOne(Yii::$app->user->id);
        $user->avatarFile = UploadedFile::getInstance($user, 'avatarFile');
        
        if ($user->avatarFile && $user->uploadAvatar()) {
            if ($user->save(false)) {
                return [
                    'success' => true,
                    'message' => '头像上传成功！',
                    'avatar_url' => '/uploads/avatars/' . $user->avatar,
                ];
            }
        }
        
        return ['success' => false, 'message' => '头像上传失败'];
    }
}
