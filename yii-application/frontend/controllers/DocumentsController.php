<?php
namespace frontend\controllers;

use yii\web\Controller;
use frontend\models\Documents;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class DocumentsController extends Controller
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Documents::find()->orderBy(['created_at' => SORT_DESC]),
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionView($id)
    {
        $model = Documents::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('文献不存在');
        }
        return $this->render('view', ['model' => $model]);
    }
}
