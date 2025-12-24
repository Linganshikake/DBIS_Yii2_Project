<?php

namespace frontend\controllers;

use common\models\Work;
use frontend\models\WorkSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class WorkController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new WorkSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = Work::find()
            ->with(['authors', 'keywords', 'files'])
            ->where(['id' => (int)$id])
            ->one();

        if (!$model) {
            throw new NotFoundHttpException('未找到该文献');
        }

        return $this->render('view', ['model' => $model]);
    }
}
