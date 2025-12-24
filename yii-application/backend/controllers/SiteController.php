<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use common\models\Work;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        // ✅ 文献统计
        $totalWorks = (int)Work::find()->count();

        // created_at 如果是 int 时间戳（你前面 format date 用的是 int，基本就是这个）
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $worksToday = (int)Work::find()->where(['>=', 'created_at', $todayStart])->count();

        // ✅ 类型分布（TOP）
        $typeCounts = Work::find()
            ->select(['work_type', 'c' => 'COUNT(*)'])
            ->groupBy('work_type')
            ->orderBy(['c' => SORT_DESC])
            ->asArray()
            ->all();

        // ✅ 最近新增
        $latestWorks = Work::find()
            ->orderBy(['id' => SORT_DESC])
            ->limit(6)
            ->all();

        return $this->render('index', [
            'totalWorks' => $totalWorks,
            'worksToday' => $worksToday,
            'typeCounts' => $typeCounts,
            'latestWorks' => $latestWorks,
        ]);
    }


    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
