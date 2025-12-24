<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the ActiveRecord model for table "organizations" (麻将团体模型)
 */

namespace common\models;

use Yii;

/**
 * This is the model class for table "organizations".
 *
 * @property int $id 团体ID
 * @property string $name 团体名称
 * @property string|null $top_title_name 该团体的最高头衔名称
 * @property int $display_status 展示状态 1:显示 0:隐藏
 *
 * @property Players[] $players
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organizations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['display_status'], 'integer'],
            [['name', 'top_title_name'], 'string', 'max' => 100],
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
            'name' => '团体名称',
            'top_title_name' => '最高头衔名称',
            'display_status' => 'Display Status',
        ];
    }

    /**
     * Gets query for [[Players]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayers()
    {
        return $this->hasMany(Player::className(), ['org_id' => 'id']);
    }
}
