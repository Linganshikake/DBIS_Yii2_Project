<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use common\models\Comment;
use common\models\CommentLike;

/**
 * CommentController 处理评论广场
 */
class CommentController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'like', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create', 'like', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post'],
                    'like' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 评论广场首页
     * @param string $sort 排序方式: latest/hot
     * @return string
     */
    public function actionIndex($sort = 'latest')
    {
        $query = Comment::find()
            ->where(['display_status' => 1])
            ->with(['user', 'likes']);
        
        if ($sort === 'hot') {
            $query->orderBy(['like_count' => SORT_DESC, 'created_at' => SORT_DESC]);
        } else {
            $query->orderBy(['created_at' => SORT_DESC]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'sort' => $sort,
        ]);
    }

    /**
     * 发表评论 (AJAX)
     * @return array
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $content = trim(Yii::$app->request->post('content', ''));
        
        if (empty($content)) {
            return ['success' => false, 'message' => '评论内容不能为空'];
        }
        
        if (mb_strlen($content) > 500) {
            return ['success' => false, 'message' => '评论内容不能超过500字'];
        }
        
        $comment = new Comment();
        $comment->user_id = Yii::$app->user->id;
        $comment->content = $content;
        $comment->created_at = time();
        $comment->like_count = 0;
        $comment->display_status = 1;
        
        if ($comment->save()) {
            // 返回新评论的HTML
            $user = Yii::$app->user->identity;
            return [
                'success' => true,
                'message' => '评论发表成功！',
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_at' => date('Y-m-d H:i', $comment->created_at),
                    'user' => [
                        'nickname' => $user->nickname ?: $user->username,
                        'avatar' => $user->avatar ? '/uploads/avatars/' . $user->avatar : null,
                    ],
                    'like_count' => 0,
                    'is_liked' => false,
                ],
            ];
        }
        
        return ['success' => false, 'message' => '评论发表失败'];
    }

    /**
     * 点赞/取消点赞 (AJAX)
     * @return array
     */
    public function actionLike()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $commentId = Yii::$app->request->post('comment_id');
        $userId = Yii::$app->user->id;
        
        $comment = Comment::findOne(['id' => $commentId, 'display_status' => 1]);
        if (!$comment) {
            return ['success' => false, 'message' => '评论不存在'];
        }
        
        // 检查是否已点赞
        $existingLike = CommentLike::findOne([
            'comment_id' => $commentId,
            'user_id' => $userId,
        ]);
        
        if ($existingLike) {
            // 取消点赞
            $existingLike->delete();
            $comment->like_count = max(0, $comment->like_count - 1);
            $comment->save(false);
            
            return [
                'success' => true,
                'action' => 'unliked',
                'message' => '已取消点赞',
                'like_count' => $comment->like_count,
            ];
        } else {
            // 点赞
            $like = new CommentLike();
            $like->comment_id = $commentId;
            $like->user_id = $userId;
            $like->created_at = time();
            
            if ($like->save()) {
                $comment->like_count = $comment->like_count + 1;
                $comment->save(false);
                
                return [
                    'success' => true,
                    'action' => 'liked',
                    'message' => '点赞成功',
                    'like_count' => $comment->like_count,
                ];
            }
        }
        
        return ['success' => false, 'message' => '操作失败'];
    }

    /**
     * 删除自己的评论 (AJAX)
     * @return array
     */
    public function actionDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $commentId = Yii::$app->request->post('comment_id');
        $userId = Yii::$app->user->id;
        
        $comment = Comment::findOne([
            'id' => $commentId,
            'user_id' => $userId,
            'display_status' => 1,
        ]);
        
        if (!$comment) {
            return ['success' => false, 'message' => '评论不存在或无权删除'];
        }
        
        // 软删除
        $comment->display_status = 0;
        if ($comment->save(false)) {
            return ['success' => true, 'message' => '评论已删除'];
        }
        
        return ['success' => false, 'message' => '删除失败'];
    }
}
