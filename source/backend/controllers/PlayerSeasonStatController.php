<?php

namespace backend\controllers;

use Yii;
use common\models\PlayerSeasonStat;
use backend\models\PlayerSeasonStatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PlayerSeasonStatController implements the CRUD actions for PlayerSeasonStat model.
 */
class PlayerSeasonStatController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all PlayerSeasonStat models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlayerSeasonStatSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PlayerSeasonStat model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PlayerSeasonStat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PlayerSeasonStat();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PlayerSeasonStat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Delete (Soft Delete): 将 display_status 设为 0，而不是物理删除
     */
    public function actionDelete($id)
    {
        // 1. 找到该条数据
        $model = $this->findModel($id);

        // 2. 修改状态为隐藏
        $model->display_status = 0;

        // 3. 保存 (使用 save(false) 跳过验证，确保删除操作一定成功)
        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', '该数据已成功删除。');
        } else {
            Yii::$app->session->setFlash('error', '删除失败，请重试。');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the PlayerSeasonStat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlayerSeasonStat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlayerSeasonStat::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
