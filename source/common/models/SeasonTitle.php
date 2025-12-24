<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the ActiveRecord model for table "season_titles" (赛季头衔模型)
 */

namespace common\models;

use Yii;

/**
 * This is the model class for table "season_titles".
 *
 * @property int $id
 * @property int $season_id
 * @property int $player_id
 * @property string $title_name 头衔名称：MVP, 避四率, 最高打点
 * @property float|null $prize_money 奖金
 * @property int $display_status
 *
 * @property Players $player
 * @property Seasons $season
 */
class SeasonTitle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'season_titles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['season_id', 'player_id', 'title_name'], 'required'],
            [['season_id', 'player_id', 'display_status'], 'integer'],
            [['prize_money'], 'number'],
            [['title_name'], 'string', 'max' => 50],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => Players::className(), 'targetAttribute' => ['player_id' => 'id']],
            [['season_id'], 'exist', 'skipOnError' => true, 'targetClass' => Seasons::className(), 'targetAttribute' => ['season_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'season_id' => 'Season ID',
            'player_id' => 'Player ID',
            'title_name' => 'Title Name',
            'prize_money' => 'Prize Money',
            'display_status' => 'Display Status',
        ];
    }

    /**
     * Gets query for [[Player]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(Player::className(), ['id' => 'player_id']);
    }

    /**
     * Gets query for [[Season]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeason()
    {
        return $this->hasOne(Season::className(), ['id' => 'season_id']);
    }
}
