<?php

namespace frontend\controllers;

use common\models\Team;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TeamController extends Controller
{
    /**
     * 队伍一览页面 (Index)
     * 只显示 display_status = 1 的队伍
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Team::find()->where(['display_status' => 1]),
            'pagination' => false, // 不分页，在一页显示所有队伍
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC], // 按ID顺序排列
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 队伍详情页面 (View)
     * 显示单个队伍的信息
     */
    public function actionView($id)
    {
        // 查找队伍，同时确保只能查看 display_status = 1 的队伍
        $model = Team::find()
            ->where(['id' => $id, 'display_status' => 1])
            ->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }
}