<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the backend controller for News CRUD (新闻管理后台控制器)
 */

namespace backend\controllers;

use Yii;
use common\models\News;
use backend\models\NewsSearch;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends BaseController
{

    /**
     * Lists all News models.
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new News model.
     */
    public function actionCreate()
    {
        $model = new News();
        $model->publish_time = date('Y-m-d H:i:s');

        if ($model->load(Yii::$app->request->post())) {
            $model->coverFile = UploadedFile::getInstance($model, 'coverFile');
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            
            if ($model->upload() && $model->save(false)) {
                Yii::$app->session->setFlash('success', '新闻创建成功');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing News model.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldCover = $model->cover;
        $oldImages = $model->images;

        if ($model->load(Yii::$app->request->post())) {
            $model->coverFile = UploadedFile::getInstance($model, 'coverFile');
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            
            if (!$model->coverFile) {
                $model->cover = $oldCover;
            }
            if (!$model->imageFiles) {
                $model->images = $oldImages;
            }
            
            $model->upload();
            
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', '新闻更新成功');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Soft deletes an existing News model.
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->display_status = 0;
        
        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', '新闻已删除');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the News model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
