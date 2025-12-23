<?php

namespace backend\controllers;

use Yii;
use common\models\Player;
use backend\models\PlayerSearch;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * PlayerController implements the CRUD actions for Player model.
 */
class PlayerController extends BaseController
{

    /**
     * Lists all Player models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlayerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Player model.
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
     * Creates a new Player model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Player();

        if ($model->load(Yii::$app->request->post())) {
            
            // 1. 获取上传实例
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            $model->videoFile = UploadedFile::getInstance($model, 'videoFile');
            $model->coverFile = UploadedFile::getInstance($model, 'coverFile'); // ★★★ 获取封面实例 ★★★
            
            // 2. 上传并保存 (save(false) 避免二次验证报错)
            // upload() 方法现在处理 imageFile, videoFile, 和 coverFile
            if ($model->upload() && $model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Player model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $oldAvatar = $model->avatar; // 记录旧头像
        $oldVideo = $model->intro_video; // 记录旧视频
        $oldCover = $model->cover; // ★★★ 记录旧封面 ★★★

        if ($model->load(Yii::$app->request->post())) {
            
            // 获取上传文件实例
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            $model->videoFile = UploadedFile::getInstance($model, 'videoFile');
            $model->coverFile = UploadedFile::getInstance($model, 'coverFile'); // ★★★ 获取封面实例 ★★★
            
            // 逻辑处理：如果没上传新文件，保持旧文件
            
            // A. 处理图片
            if (!$model->imageFile) {
                $model->avatar = $oldAvatar;
            }

            // B. 处理视频
            if (!$model->videoFile) {
                $model->intro_video = $oldVideo;
            }

            // C. 处理封面 ★★★
            if (!$model->coverFile) {
                $model->cover = $oldCover;
            }
            
            // 执行上传 (upload() 会检查 imageFile, videoFile, coverFile 是否存在并保存)
            // 即使没有新文件上传，upload() 也会返回 true (只要验证通过)
            $model->upload();

            // save(false) 关键！
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
     * Finds the Player model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Player the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Player::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}