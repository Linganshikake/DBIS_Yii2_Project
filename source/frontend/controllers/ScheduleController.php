<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the frontend controller for Schedule display (赛程前台控制器)
 */

namespace frontend\controllers;

use common\models\Schedule;
use common\models\ScheduleScore;
use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * ScheduleController handles the display of schedules in frontend
 */
class ScheduleController extends Controller
{
    /**
     * 日程-成绩页面
     */
    public function actionIndex()
    {
        // 获取未来3场比赛
        $upcomingSchedules = Schedule::getUpcomingSchedules(3);
        
        // 获取当前月份（可切换）
        $year = Yii::$app->request->get('year', date('Y'));
        $month = Yii::$app->request->get('month', date('n'));
        
        // 获取当月日程
        $monthlySchedules = Schedule::getSchedulesByMonth($year, $month);

        return $this->render('index', [
            'upcomingSchedules' => $upcomingSchedules,
            'monthlySchedules' => $monthlySchedules,
            'year' => $year,
            'month' => $month,
        ]);
    }

    /**
     * AJAX获取日程详情
     */
    public function actionDetail($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $schedule = Schedule::findOne(['id' => $id, 'display_status' => 1]);
        if (!$schedule) {
            return ['error' => '日程不存在'];
        }
        
        $scores = [];
        foreach ($schedule->scheduleScores as $score) {
            $rankedScores = $score->getRankedScores();
            $scores[] = [
                'game_number' => $score->game_number,
                'game_text' => $score->getGameNumberText(),
                'results' => array_map(function($item) {
                    return [
                        'team_name' => $item['team'] ? $item['team']->name : '-',
                        'team_logo' => $item['team'] && $item['team']->logo ? '/uploads/teams/' . $item['team']->logo : null,
                        'player_name' => $item['player'] ? $item['player']->name : '-',
                        'player_avatar' => $item['player'] && $item['player']->avatar ? '/uploads/players/' . $item['player']->avatar : null,
                        'score' => $item['score'],
                    ];
                }, $rankedScores),
            ];
        }
        
        return [
            'id' => $schedule->id,
            'match_date' => $schedule->match_date,
            'day_of_week' => $schedule->day_of_week,
            'teams' => [
                [
                    'name' => $schedule->team1 ? $schedule->team1->name : '-',
                    'logo' => $schedule->team1 && $schedule->team1->logo ? '/uploads/teams/' . $schedule->team1->logo : null,
                    'is_top' => $schedule->top_team_id == $schedule->team_id1,
                ],
                [
                    'name' => $schedule->team2 ? $schedule->team2->name : '-',
                    'logo' => $schedule->team2 && $schedule->team2->logo ? '/uploads/teams/' . $schedule->team2->logo : null,
                    'is_top' => $schedule->top_team_id == $schedule->team_id2,
                ],
                [
                    'name' => $schedule->team3 ? $schedule->team3->name : '-',
                    'logo' => $schedule->team3 && $schedule->team3->logo ? '/uploads/teams/' . $schedule->team3->logo : null,
                    'is_top' => $schedule->top_team_id == $schedule->team_id3,
                ],
                [
                    'name' => $schedule->team4 ? $schedule->team4->name : '-',
                    'logo' => $schedule->team4 && $schedule->team4->logo ? '/uploads/teams/' . $schedule->team4->logo : null,
                    'is_top' => $schedule->top_team_id == $schedule->team_id4,
                ],
            ],
            'status' => $schedule->match_status,
            'status_text' => $schedule->getStatusText(),
            'scores' => $scores,
        ];
    }
}
