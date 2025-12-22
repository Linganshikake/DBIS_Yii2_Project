<?php
/**
 * Team: DBIS_Yii2_Project
 * This is the model class for table "comment_likes".
 */

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment_likes".
 *
 * @property int $id
 * @property int $comment_id 评论ID
 * @property int $user_id 点赞用户ID
 * @property string $created_at
 *
 * @property Comment $comment
 * @property User $user
 */
class CommentLike extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment_likes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment_id', 'user_id'], 'required'],
            [['comment_id', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['comment_id', 'user_id'], 'unique', 'targetAttribute' => ['comment_id', 'user_id'], 'message' => '您已经点赞过了'],
            [['comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comment::class, 'targetAttribute' => ['comment_id' => 'id']],
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
            'comment_id' => '评论',
            'user_id' => '用户',
            'created_at' => '点赞时间',
        ];
    }

    /**
     * Gets query for [[Comment]].
     */
    public function getComment()
    {
        return $this->hasOne(Comment::class, ['id' => 'comment_id']);
    }

    /**
     * Gets query for [[User]].
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
