<?php

namespace backend\controllers;

use Yii;
use common\models\ScheduleScore;
use common\models\Schedule;
use common\models\Player;
use backend\models\ScheduleScoreSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * ScheduleScoreController implements the CRUD actions for ScheduleScore model.
 */
class ScheduleScoreController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ScheduleScore models.
     */
    public function actionIndex()
    {
        $searchModel = new ScheduleScoreSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ScheduleScore model.
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ScheduleScore model.
     */
    public function actionCreate($schedule_id = null)
    {
        $model = new ScheduleScore();
        
        if ($schedule_id) {
            $model->schedule_id = $schedule_id;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // 更新日程状态为已结束
            $schedule = $model->schedule;
            if ($schedule) {
                $schedule->match_status = 2;
                // 计算首位队伍
                $scores = [
                    $schedule->team_id1 => 0,
                    $schedule->team_id2 => 0,
                    $schedule->team_id3 => 0,
                    $schedule->team_id4 => 0,
                ];
                
                foreach ($schedule->scheduleScores as $score) {
                    $scores[$schedule->team_id1] += $score->team1_score;
                    $scores[$schedule->team_id2] += $score->team2_score;
                    $scores[$schedule->team_id3] += $score->team3_score;
                    $scores[$schedule->team_id4] += $score->team4_score;
                }
                
                arsort($scores);
                $schedule->top_team_id = array_key_first($scores);
                $schedule->save(false);
            }
            
            Yii::$app->session->setFlash('success', '成绩录入成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'schedules' => $this->getScheduleList(),
            'players' => $this->getPlayerList(),
        ]);
    }

    /**
     * Updates an existing ScheduleScore model.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '成绩更新成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'schedules' => $this->getScheduleList(),
            'players' => $this->getPlayerList(),
        ]);
    }

    /**
     * Soft deletes an existing ScheduleScore model.
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->display_status = 0;
        
        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', '成绩记录已删除');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScheduleScore model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = ScheduleScore::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * 获取日程列表
     */
    protected function getScheduleList()
    {
        $schedules = Schedule::find()
            ->where(['display_status' => 1])
            ->orderBy(['match_date' => SORT_DESC])
            ->all();
            
        $list = [];
        foreach ($schedules as $schedule) {
            $label = $schedule->match_date . ' ' . $schedule->day_of_week . ' - ' . 
                     $schedule->team1->name . ' / ' . $schedule->team2->name . ' / ' . 
                     $schedule->team3->name . ' / ' . $schedule->team4->name;
            $list[$schedule->id] = $label;
        }
        return $list;
    }
    
    /**
     * 获取选手列表
     */
    protected function getPlayerList()
    {
        return ArrayHelper::map(
            Player::find()
                ->where(['display_status' => 1])
                ->orderBy(['team_id' => SORT_ASC, 'name' => SORT_ASC])
                ->all(),
            'id',
            function($model) {
                return $model->name . ' (' . ($model->team ? $model->team->name : '无队伍') . ')';
            }
        );
    }
    
    /**
     * AJAX获取队伍选手
     */
    public function actionGetTeamPlayers($schedule_id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $schedule = Schedule::findOne($schedule_id);
        if (!$schedule) {
            return ['error' => '日程不存在'];
        }
        
        $result = [];
        $teamIds = [$schedule->team_id1, $schedule->team_id2, $schedule->team_id3, $schedule->team_id4];
        
        foreach ($teamIds as $index => $teamId) {
            $players = Player::find()
                ->where(['team_id' => $teamId, 'display_status' => 1])
                ->all();
            
            $result['team' . ($index + 1)] = ArrayHelper::map($players, 'id', 'name');
        }
        
        return $result;
    }
}
