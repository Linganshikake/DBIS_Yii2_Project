<?php

namespace backend\controllers;

use Yii;
use common\models\Company;
use common\models\Team;
use backend\models\CompanySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
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
     * Lists all Company models.
     */
    public function actionIndex()
    {
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Company model.
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Company model.
     */
    public function actionCreate()
    {
        $model = new Company();

        if ($model->load(Yii::$app->request->post())) {
            $model->logoFile = UploadedFile::getInstance($model, 'logoFile');
            
            if ($model->upload() && $model->save(false)) {
                Yii::$app->session->setFlash('success', '企业信息创建成功');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'teams' => $this->getTeamList(),
        ]);
    }

    /**
     * Updates an existing Company model.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldLogo = $model->logo;

        if ($model->load(Yii::$app->request->post())) {
            $model->logoFile = UploadedFile::getInstance($model, 'logoFile');
            
            if (!$model->logoFile) {
                $model->logo = $oldLogo;
            }
            
            $model->upload();
            
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', '企业信息更新成功');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'teams' => $this->getTeamList(),
        ]);
    }

    /**
     * Soft deletes an existing Company model.
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->display_status = 0;
        
        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', '企业信息已删除');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Company model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
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
}
