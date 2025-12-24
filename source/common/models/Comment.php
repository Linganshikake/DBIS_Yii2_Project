<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the ActiveRecord model for table "comments" (评论模型)
 */

namespace common\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property int $id 评论ID
 * @property int $user_id 用户ID
 * @property string $content 评论内容
 * @property string $created_at 发布时间
 * @property int $like_count 点赞数
 * @property int $display_status 展示状态
 *
 * @property User $user
 * @property CommentLike[] $commentLikes
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'content'], 'required'],
            [['user_id', 'like_count', 'display_status'], 'integer'],
            [['content'], 'string'],
            [['content'], 'string', 'min' => 1, 'max' => 1000],
            [['created_at'], 'safe'],
            ['display_status', 'default', 'value' => 1],
            ['like_count', 'default', 'value' => 0],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户',
            'content' => '评论内容',
            'created_at' => '发布时间',
            'like_count' => '点赞数',
            'display_status' => '展示状态',
        ];
    }

    /**
     * Gets query for [[User]].
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[CommentLikes]].
     */
    public function getCommentLikes()
    {
        return $this->hasMany(CommentLike::class, ['comment_id' => 'id']);
    }

    /**
     * 检查用户是否已点赞
     */
    public function isLikedByUser($userId)
    {
        return CommentLike::find()
            ->where(['comment_id' => $this->id, 'user_id' => $userId])
            ->exists();
    }

    /**
     * 点赞
     */
    public function like($userId)
    {
        if ($this->isLikedByUser($userId)) {
            return false;
        }
        
        $like = new CommentLike();
        $like->comment_id = $this->id;
        $like->user_id = $userId;
        
        if ($like->save()) {
            $this->updateCounters(['like_count' => 1]);
            return true;
        }
        
        return false;
    }

    /**
     * 取消点赞
     */
    public function unlike($userId)
    {
        $like = CommentLike::findOne(['comment_id' => $this->id, 'user_id' => $userId]);
        
        if ($like && $like->delete()) {
            $this->updateCounters(['like_count' => -1]);
            return true;
        }
        
        return false;
    }

    /**
     * 获取最新评论
     */
    public static function getLatestComments($limit = 20, $offset = 0)
    {
        return self::find()
            ->where(['display_status' => 1])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit($limit)
            ->offset($offset)
            ->with(['user'])
            ->all();
    }

    /**
     * 格式化发布时间
     */
    public function getFormattedTime()
    {
        return date('Y-m-d H:i:s', strtotime($this->created_at));
    }
}
