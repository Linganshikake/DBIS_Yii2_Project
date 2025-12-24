<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the ActiveRecord model for table "player_season_stats" (选手赛季统计)
 */

namespace common\models;

use Yii;

/**
 * This is the model class for table "player_season_stats".
 *
 * @property int $id
 * @property int $player_id 选手ID
 * @property int $season_id 赛季ID
 * @property int $team_id 该赛季所属队伍ID (考虑转会情况)
 * @property int|null $games_count 试合数
 * @property float|null $total_score 通算得点
 * @property int|null $rank_1_count 1位次数
 * @property int|null $rank_2_count 2位次数
 * @property int|null $rank_3_count 3位次数
 * @property int|null $rank_4_count 4位次数
 * @property int|null $max_score 单局最高打点
 * @property float|null $avg_rank 平均顺位
 * @property float|null $top_rate 一位率
 * @property float|null $last_avoid_rate 避四率
 * @property int $display_status
 *
 * @property Players $player
 * @property Seasons $season
 * @property Teams $team
 */
class PlayerSeasonStat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'player_season_stats';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['player_id', 'season_id', 'team_id'], 'required'],
            [['player_id', 'season_id', 'team_id', 'games_count', 'rank_1_count', 'rank_2_count', 'rank_3_count', 'rank_4_count', 'max_score', 'display_status'], 'integer'],
            [['total_score', 'avg_rank', 'top_rate', 'last_avoid_rate'], 'number'],
            [['player_id', 'season_id'], 'unique', 'targetAttribute' => ['player_id', 'season_id']],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => Player::className(), 'targetAttribute' => ['player_id' => 'id']],
            [['season_id'], 'exist', 'skipOnError' => true, 'targetClass' => Season::className(), 'targetAttribute' => ['season_id' => 'id']],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['team_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'player_id' => 'Player ID',      
            'season_id' => 'Season ID',         
            'team_id' => '所属队伍',       
            'games_count' => '试合数',
            'total_score' => '通算得点',
            'rank_1_count' => '1位获得数',
            'rank_2_count' => '2位获得数',
            'rank_3_count' => '3位获得数',
            'rank_4_count' => '4位获得数',
            'max_score' => '最高打点',
            'avg_rank' => '平均顺位',
            'top_rate' => '一位率 (Top率)',
            'last_avoid_rate' => '避四率 (Last回避)',
            'display_status' => '展示状态',
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

    /**
     * Gets query for [[Team]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }
}
