<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the ActiveRecord model for table "comment_replies" (评论回复模型)
 */

namespace common\models;

use Yii;

/**
 *
 * @property int $id
 * @property int $comment_id
 * @property int $user_id
 * @property string $content
 * @property string $created_at
 * @property int $display_status
 *
 * @property Comment $comment
 * @property User $user
 */
class CommentReply extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment_replies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment_id', 'user_id', 'content'], 'required'],
            [['comment_id', 'user_id', 'display_status'], 'integer'],
            [['content'], 'string', 'min' => 1, 'max' => 500],
            [['created_at'], 'safe'],
            ['display_status', 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comment_id' => '评论ID',
            'user_id' => '用户ID',
            'content' => '回复内容',
            'created_at' => '创建时间',
            'display_status' => '显示状态',
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
