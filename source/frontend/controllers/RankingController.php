<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the frontend controller for Rankings (排行榜前台控制器)
 */

namespace frontend\controllers;

use common\models\PlayerSeasonStat;
use common\models\Season;
use common\models\TeamSeasonStat;
use yii\db\Expression;
use yii\web\Controller;

class RankingController extends Controller
{
    public function actionIndex()
    {
        // 1. 获取当前赛季
        $currentSeason = Season::findOne(['is_current' => 1]);
        if (!$currentSeason) {
            $currentSeason = Season::find()->orderBy(['id' => SORT_DESC])->one();
        }

        $teamRankings = [];
        $playerRankings = [];
        
        // 定义头衔变量
        $mvpPlayer = null;
        $avoidPlayer = null;
        $maxScorePlayer = null;

        if ($currentSeason) {
            // ... (原有的队伍查询代码保持不变) ...
            $teamRankings = TeamSeasonStat::find()
            ->where(['season_id' => $currentSeason->id])
            // 优先按 rank 排序 (1在最前)，如果 rank 还没填(是null)，则按总分倒序作为保底
            ->orderBy([
                'total_rank' => SORT_ASC,  // 排名：1, 2, 3...
                'total_score' => SORT_DESC // 分数：高分在前 (当rank相同时)
            ])
            ->all();

            // ... (原有的个人查询代码保持不变) ...
            $playerRankings = PlayerSeasonStat::find()
                ->where(['season_id' => $currentSeason->id, 'display_status' => 1])
                ->orderBy(['total_score' => SORT_DESC])
                ->all();

            // ★★★ 新增：查询三个单项王 ★★★
            
            // 1. MVP (积分最高)
            $mvpPlayer = PlayerSeasonStat::find()
                ->where(['season_id' => $currentSeason->id, 'display_status' => 1])
                ->orderBy(['total_score' => SORT_DESC])
                ->limit(1)
                ->one();

            // 2. 避四王 (避四率最高) 
            // 注意：M联赛规则通常要求打满一定场次，这里简单起见先按 率降序 + 场次降序
            $avoidPlayer = PlayerSeasonStat::find()
                ->where(['season_id' => $currentSeason->id, 'display_status' => 1])
                ->andWhere(['>', 'games_count', 0]) // 至少打过一场
                ->orderBy(['last_avoid_rate' => SORT_DESC, 'games_count' => SORT_DESC]) 
                ->limit(1)
                ->one();

            // 3. 打点王 (单局最高分)
            $maxScorePlayer = PlayerSeasonStat::find()
                ->where(['season_id' => $currentSeason->id, 'display_status' => 1])
                ->orderBy(['max_score' => SORT_DESC])
                ->limit(1)
                ->one();
        }

        return $this->render('index', [
            'season' => $currentSeason,
            'teamRankings' => $teamRankings,
            'playerRankings' => $playerRankings,
            // 传递新变量
            'mvpPlayer' => $mvpPlayer,
            'avoidPlayer' => $avoidPlayer,
            'maxScorePlayer' => $maxScorePlayer,
        ]);
    }
}