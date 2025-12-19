<?php

namespace frontend\controllers;

use common\models\Player;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PlayerController extends Controller
{
    /**
     * 选手一览 (名鉴)
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Player::find()->where(['display_status' => 1]),
            'pagination' => [
                'pageSize' => 12, // 每页显示12人
            ],
            'sort' => [
                'defaultOrder' => ['team_id' => SORT_ASC], // 默认按队伍排序
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 选手个人主页 (含生涯数据)
     */
    public function actionView($id)
    {
        $model = Player::find()
            ->where(['id' => $id, 'display_status' => 1])
            ->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested player does not exist.');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }
}