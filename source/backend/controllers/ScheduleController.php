<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the backend controller for Schedule CRUD and management (赛程管理控制器)
 */

namespace backend\controllers;

use Yii;
use common\models\Schedule;
use common\models\Team;
use common\models\Season;
use backend\models\ScheduleSearch;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;

/**
 * ScheduleController implements the CRUD actions for Schedule model.
 */
class ScheduleController extends BaseController
{

    /**
     * Lists all Schedule models.
     */
    public function actionIndex()
    {
        $searchModel = new ScheduleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Schedule model.
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Schedule model.
     */
    public function actionCreate()
    {
        $model = new Schedule();
        
        // 自动设置当前赛季
        $currentSeason = Season::findOne(['is_current' => 1]);
        if ($currentSeason) {
            $model->season_id = $currentSeason->id;
        }

        if ($model->load(Yii::$app->request->post())) {
            // 自动设置星期
            if ($model->match_date) {
                $dayMap = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
                $model->day_of_week = $dayMap[date('w', strtotime($model->match_date))];
            }
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '日程创建成功');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'teams' => $this->getTeamList(),
            'seasons' => $this->getSeasonList(),
        ]);
    }

    /**
     * Updates an existing Schedule model.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            // 自动更新星期
            if ($model->match_date) {
                $dayMap = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
                $model->day_of_week = $dayMap[date('w', strtotime($model->match_date))];
            }
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '日程更新成功');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'teams' => $this->getTeamList(),
            'seasons' => $this->getSeasonList(),
        ]);
    }

    /**
     * Soft deletes an existing Schedule model.
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->display_status = 0;
        
        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', '日程已删除');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Schedule model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = Schedule::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * 获取队伍列表
     */
    protected function getTeamList()
    {
        return ArrayHelper::map(
            Team::find()->where(['display_status' => 1])->orderBy(['name' => SORT_ASC])->all(),
            'id',
            'name'
        );
    }
    
    /**
     * 获取赛季列表
     */
    protected function getSeasonList()
    {
        return ArrayHelper::map(
            Season::find()->where(['display_status' => 1])->orderBy(['id' => SORT_DESC])->all(),
            'id',
            'name'
        );
    }
}
