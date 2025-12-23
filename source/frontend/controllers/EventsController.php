<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

/**
 * EventsController
 * 抗战80周年主题活动
 */
class EventsController extends Controller
{
    /**
     * 抗战80周年首页
     */
    public function actionIndex()
    {
        $events = [
            [
                'title' => '抗战全面爆发纪念日',
                'date' => '1937-07-07',
                'desc' => '卢沟桥事变标志着全国性抗战的开始。'
            ],
            [
                'title' => '抗战胜利纪念日',
                'date' => '1945-09-03',
                'desc' => '中国人民抗日战争取得伟大胜利。'
            ],
        ];

        return $this->render('index', [
            'events' => $events,
        ]);
    }
}