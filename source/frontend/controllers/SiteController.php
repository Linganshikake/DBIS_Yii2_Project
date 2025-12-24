<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\TeamSeasonStat;
use common\models\PlayerSeasonStat;
use common\models\Season;
use common\models\News;
use yii\db\Expression;

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
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
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
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $currentSeason = Season::findOne(['is_current' => 1]);
        if (!$currentSeason) {
            $currentSeason = Season::find()->orderBy(['id' => SORT_DESC])->one();
        }

        $rankings = [];
        $playerRankings = [];
        $latestNews = [];
        
        if ($currentSeason) {
            $rankings = TeamSeasonStat::find()
                ->where(['season_id' => $currentSeason->id, 'display_status' => 1])
                // 意思：优先按 final_score 排，没有就按 semi，再没有按 regular。全部降序（分高的在前）
                //->orderBy(new Expression('COALESCE(final_score, semifinal_score, regular_score) DESC'))
                
                // 如果您坚持要用 total_rank 字段排序，请注释上一行，用下面这一行：
                ->orderBy(['total_rank' => SORT_ASC]) // 排名 1 在最上面
                ->all();
            
            // 获取选手排行榜 - 4个维度
            // 1. 总得点排行 - 首先获取前5名用于预览，同时获取所有数据用于展开
            $playerRankings['total_score'] = PlayerSeasonStat::find()
                ->alias('pss')
                ->joinWith(['player', 'player.team'])
                ->where(['pss.season_id' => $currentSeason->id, 'pss.display_status' => 1])
                ->orderBy(['pss.total_score' => SORT_DESC])
                ->limit(5)
                ->all();
            
            $playerRankings['total_score_all'] = PlayerSeasonStat::find()
                ->alias('pss')
                ->joinWith(['player', 'player.team'])
                ->where(['pss.season_id' => $currentSeason->id, 'pss.display_status' => 1])
                ->orderBy(['pss.total_score' => SORT_DESC])
                ->all();
            
            // 2. 平均顺位排行 (数值越小越好)
            $playerRankings['avg_rank'] = PlayerSeasonStat::find()
                ->alias('pss')
                ->joinWith(['player', 'player.team'])
                ->where(['pss.season_id' => $currentSeason->id, 'pss.display_status' => 1])
                ->andWhere(['>', 'pss.games_count', 0])
                ->orderBy(['pss.avg_rank' => SORT_ASC])
                ->limit(5)
                ->all();
            
            $playerRankings['avg_rank_all'] = PlayerSeasonStat::find()
                ->alias('pss')
                ->joinWith(['player', 'player.team'])
                ->where(['pss.season_id' => $currentSeason->id, 'pss.display_status' => 1])
                ->andWhere(['>', 'pss.games_count', 0])
                ->orderBy(['pss.avg_rank' => SORT_ASC])
                ->all();
            
            // 3. 1位率排行
            $playerRankings['first_rate'] = PlayerSeasonStat::find()
                ->alias('pss')
                ->joinWith(['player', 'player.team'])
                ->where(['pss.season_id' => $currentSeason->id, 'pss.display_status' => 1])
                ->andWhere(['>', 'pss.games_count', 0])
                ->orderBy(['pss.top_rate' => SORT_DESC])
                ->limit(5)
                ->all();
            
            $playerRankings['first_rate_all'] = PlayerSeasonStat::find()
                ->alias('pss')
                ->joinWith(['player', 'player.team'])
                ->where(['pss.season_id' => $currentSeason->id, 'pss.display_status' => 1])
                ->andWhere(['>', 'pss.games_count', 0])
                ->orderBy(['pss.top_rate' => SORT_DESC])
                ->all();
            
            // 4. 避四率排行
            $playerRankings['avoid_rate'] = PlayerSeasonStat::find()
                ->alias('pss')
                ->joinWith(['player', 'player.team'])
                ->where(['pss.season_id' => $currentSeason->id, 'pss.display_status' => 1])
                ->orderBy(['pss.last_avoid_rate' => SORT_DESC])
                ->limit(5)
                ->all();
            
            $playerRankings['avoid_rate_all'] = PlayerSeasonStat::find()
                ->alias('pss')
                ->joinWith(['player', 'player.team'])
                ->where(['pss.season_id' => $currentSeason->id, 'pss.display_status' => 1])
                ->orderBy(['pss.last_avoid_rate' => SORT_DESC])
                ->all();
        }
        
        // 获取最新新闻
        $latestNews = News::getLatestNews(4);
        
        // 获取所有队伍（用于TEAMS展示）
        $allTeams = \common\models\Team::find()
            ->where(['display_status' => 1])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        return $this->render('index', [
            'rankings' => $rankings,
            'seasonName' => $currentSeason ? $currentSeason->name : '未定义赛季',
            'playerRankings' => $playerRankings,
            'latestNews' => $latestNews,
            'allTeams' => $allTeams,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays course documents download page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        // 获取个人作业目录
        $personalPath = Yii::getAlias('@frontend') . '/../data/personal';
        $teamPath = Yii::getAlias('@frontend') . '/../data/team';
        
        $personalWorks = [];
        $teamWorks = [];
        
        // 扫描个人作业文件夹
        if (is_dir($personalPath)) {
            $students = scandir($personalPath);
            foreach ($students as $student) {
                if ($student === '.' || $student === '..') continue;
                $studentPath = $personalPath . '/' . $student;
                if (is_dir($studentPath)) {
                    $files = scandir($studentPath);
                    $works = [];
                    foreach ($files as $file) {
                        if ($file === '.' || $file === '..') continue;
                        $works[] = [
                            'name' => $file,
                            'path' => ['site/download', 'type' => 'personal', 'folder' => $student, 'file' => $file],
                        ];
                    }
                    if (!empty($works)) {
                        $personalWorks[] = [
                            'student' => $student,
                            'works' => $works,
                        ];
                    }
                }
            }
        }
        
        // 扫描团队作业文件夹
        if (is_dir($teamPath)) {
            $files = scandir($teamPath);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                $teamWorks[] = [
                    'name' => $file,
                    'path' => ['site/download', 'type' => 'team', 'file' => $file],
                ];
            }
        }
        
        return $this->render('contact', [
            'personalWorks' => $personalWorks,
            'teamWorks' => $teamWorks,
        ]);
    }
    
    /**
     * 下载课程文档
     * @param string $type 文件类型: team/personal
     * @param string $file 文件名
     * @param string|null $folder 子文件夹（个人作业用）
     * @return mixed
     */
    public function actionDownload($type, $file, $folder = null)
    {
        $basePath = Yii::getAlias('@frontend') . '/../data';
        
        // 安全检查：防止目录遍历攻击
        $file = basename($file);
        if ($folder) {
            $folder = basename($folder);
        }
        
        if ($type === 'team') {
            $filePath = $basePath . '/team/' . $file;
        } elseif ($type === 'personal' && $folder) {
            $filePath = $basePath . '/personal/' . $folder . '/' . $file;
        } else {
            throw new \yii\web\NotFoundHttpException('文件不存在');
        }
        
        if (!file_exists($filePath) || !is_file($filePath)) {
            throw new \yii\web\NotFoundHttpException('文件不存在');
        }
        
        return Yii::$app->response->sendFile($filePath, $file);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
