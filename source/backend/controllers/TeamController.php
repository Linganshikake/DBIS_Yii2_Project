<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 您的姓名 (学号), 2025xxxx
 * This is the controller class for table "teams".
 */

namespace backend\controllers;

use Yii;
use common\models\Team;
use backend\models\TeamSearch;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * TeamController implements the CRUD actions for Team model.
 */
class TeamController extends BaseController
{

    /**
     * Lists all Team models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TeamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Team model.
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
     * Creates a new Team model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Team();

        if ($model->load(Yii::$app->request->post())) {
            
            // 1. 获取图片实例
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            
            // 2. 先处理上传 (upload 方法里包含了 validate)
            // 如果上传成功（或者没上传图片但数据验证通过），再保存
            if ($model->upload()) {
                
                // ★★★ 关键点：使用 save(false) ★★★
                // 因为 upload() 已经验证过了，而且可能移动了文件
                // 如果这里不加 false，它会再次验证，导致找不到文件报错
                if ($model->save(false)) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Team model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        // 记录旧logo，防止没传新图时把旧图覆盖没了
        $oldLogo = $model->logo;

        if ($model->load(Yii::$app->request->post())) {
            
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            
            if ($model->imageFile) {
                // 如果有新图，执行上传逻辑（验证 + 移动文件）
                $model->upload();
            } else {
                // 如果没传新图，把旧图名字赋回去
                $model->logo = $oldLogo;
            }

            // ★★★ 关键点：使用 save(false) ★★★
            // 同样，这里必须跳过二次验证
            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
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
     * Finds the Team model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Team the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Team::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
