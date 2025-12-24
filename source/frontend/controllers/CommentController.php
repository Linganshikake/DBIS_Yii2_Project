<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the frontend controller for Comments (评论前台控制器)
 */

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use common\models\Comment;
use common\models\CommentLike;
use common\models\CommentReply;

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
                'only' => ['create', 'like', 'delete', 'reply', 'delete-reply'],
                'rules' => [
                    [
                        'actions' => ['create', 'like', 'delete', 'reply', 'delete-reply'],
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
                    'reply' => ['post'],
                    'delete-reply' => ['post'],
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
            ->with(['user', 'commentLikes']);
        
        if ($sort === 'hot') {
            // 热门模式：只显示点赞数最多的前3条评论
            $query->orderBy(['like_count' => SORT_DESC, 'created_at' => SORT_DESC])
                  ->limit(3);
            
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => false, // 热门模式不分页，只显示3条
            ]);
        } else {
            $query->orderBy(['created_at' => SORT_DESC]);
            
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
        }

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
        // created_at 有默认值，不需要手动设置
        $comment->like_count = 0;
        $comment->display_status = 1;
        
        if ($comment->save(false)) {
            // 返回新评论的HTML
            $user = Yii::$app->user->identity;
            return [
                'success' => true,
                'message' => '评论发表成功！',
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_at' => date('Y-m-d H:i', strtotime($comment->created_at)),
                    'user' => [
                        'nickname' => $user->nickname ?: $user->username,
                        'avatar' => $user->avatar ? '/uploads/avatars/' . $user->avatar : null,
                    ],
                    'like_count' => 0,
                    'is_liked' => false,
                ],
            ];
        }
        
        // 返回具体的错误信息
        return ['success' => false, 'message' => '评论发表失败: ' . implode(', ', $comment->getFirstErrors())];
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

    /**
     * 回复评论 (AJAX)
     * @return array
     */
    public function actionReply()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $commentId = Yii::$app->request->post('comment_id');
        $content = trim(Yii::$app->request->post('content', ''));
        
        if (empty($content)) {
            return ['success' => false, 'message' => '回复内容不能为空'];
        }
        
        if (mb_strlen($content) > 500) {
            return ['success' => false, 'message' => '回复内容不能超过500字'];
        }
        
        $comment = Comment::findOne(['id' => $commentId, 'display_status' => 1]);
        if (!$comment) {
            return ['success' => false, 'message' => '评论不存在'];
        }
        
        $reply = new CommentReply();
        $reply->comment_id = $commentId;
        $reply->user_id = Yii::$app->user->id;
        $reply->content = $content;
        $reply->display_status = 1;
        
        if ($reply->save(false)) {
            $user = Yii::$app->user->identity;
            return [
                'success' => true,
                'message' => '回复成功',
                'reply' => [
                    'id' => $reply->id,
                    'content' => $reply->content,
                    'created_at' => date('Y-m-d H:i', strtotime($reply->created_at)),
                    'user' => [
                        'nickname' => $user->nickname ?: $user->username,
                    ],
                ],
            ];
        }
        
        return ['success' => false, 'message' => '回复失败'];
    }

    /**
     * 删除自己的回复 (AJAX)
     * @return array
     */
    public function actionDeleteReply()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $replyId = Yii::$app->request->post('reply_id');
        $userId = Yii::$app->user->id;
        
        $reply = CommentReply::findOne([
            'id' => $replyId,
            'user_id' => $userId,
            'display_status' => 1,
        ]);
        
        if (!$reply) {
            return ['success' => false, 'message' => '回复不存在或无权删除'];
        }
        
        $reply->display_status = 0;
        if ($reply->save(false)) {
            return ['success' => true, 'message' => '回复已删除'];
        }
        
        return ['success' => false, 'message' => '删除失败'];
    }
}
