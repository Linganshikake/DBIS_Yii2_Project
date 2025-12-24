<?php

/**
 * Team: DBIS_Yii_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the frontend controller for News display (新闻展示前台控制器)
 */

namespace frontend\controllers;

use common\models\News;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * NewsController handles the display of news in frontend
 */
class NewsController extends Controller
{
    /**
     * 新闻列表页
     */
    public function actionIndex()
    {
        // 获取最新新闻（前3条）作为置顶展示
        $hotNews = News::getLatestNews(3);
        
        // 获取所有新闻
        $dataProvider = new ActiveDataProvider([
            'query' => News::find()->where(['display_status' => 1])->orderBy(['publish_time' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'hotNews' => $hotNews,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 新闻详情页
     */
    public function actionView($id)
    {
        $model = News::findOne(['id' => $id, 'display_status' => 1]);
        
        if ($model === null) {
            throw new NotFoundHttpException('新闻不存在');
        }
        
        // 增加浏览次数
        $model->incrementViewCount();

        return $this->render('view', [
            'model' => $model,
        ]);
    }
}
