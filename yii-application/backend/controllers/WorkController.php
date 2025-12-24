<?php

namespace backend\controllers;

use Yii;
use common\models\Work;
use backend\models\WorkSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class WorkController extends Controller
{
    public function behaviors()
    {
        return [
            // ✅ 后台默认要求登录
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /** ✅ 文献展示（只读） */
    public function actionDisplay()
    {
        $searchModel = new \backend\models\WorkSearch();   
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('display', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /** ✅ 文献管理（CRUD 列表） */
    public function actionIndex()
    {
        $searchModel = new WorkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 20;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionCreate()
    {
        $model = new Work();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '创建成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '保存成功');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', '已删除');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        $m = Work::findOne($id);
        if ($m === null) throw new NotFoundHttpException('文献不存在');
        return $m;
    }
}
