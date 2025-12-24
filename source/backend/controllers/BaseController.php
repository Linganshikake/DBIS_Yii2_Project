<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the base controller for backend application (后台基础控制器，统一访问控制)
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * 后台基础控制器
 * 所有后台控制器都应继承此类以获得统一的访问控制
 */
class BaseController extends Controller
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
                        'allow' => true,
                        'roles' => ['@'], // 只允许已登录用户访问
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * 在动作执行前检查用户是否为管理员
     * @param \yii\base\Action $action
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        
        // 检查用户是否已登录且为管理员
        $user = Yii::$app->user->identity;
        if ($user && !$user->isAdmin()) {
            throw new ForbiddenHttpException('您没有权限访问后台管理系统。只有管理员才能访问。');
        }
        
        return true;
    }
}
