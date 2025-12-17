<?php
/**
 * Team: DBIS_Yii2_Project (您的团队名称)
 * Coding by: 您的姓名 (您的学号), 2025xxxx (日期)
 * This is the model class for table "此处填表名".
 */

namespace common\models;

use Yii;

/**
 * This is the model class for table "team_season_stats".
 *
 * @property int $id
 * @property int $team_id
 * @property int $season_id
 * @property float|null $regular_score 常规赛分数
 * @property float|null $semifinal_score 半决赛分数
 * @property float|null $final_score 决赛分数
 * @property int|null $total_rank 赛季最终排名
 * @property int $display_status
 *
 * @property Seasons $season
 * @property Teams $team
 */
class TeamSeasonStat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'team_season_stats';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['team_id', 'season_id'], 'required'],
            [['team_id', 'season_id', 'total_rank', 'display_status'], 'integer'],
            [['regular_score', 'semifinal_score', 'final_score'], 'number'],
            [['team_id', 'season_id'], 'unique', 'targetAttribute' => ['team_id', 'season_id']],
            [['season_id'], 'exist', 'skipOnError' => true, 'targetClass' => Seasons::className(), 'targetAttribute' => ['season_id' => 'id']],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teams::className(), 'targetAttribute' => ['team_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'team_id' => 'Team ID',
            'season_id' => 'Season ID',
            'regular_score' => 'Regular Score',
            'semifinal_score' => 'Semifinal Score',
            'final_score' => 'Final Score',
            'total_rank' => 'Total Rank',
            'display_status' => 'Display Status',
        ];
    }

    /**
     * Gets query for [[Season]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeason()
    {
        return $this->hasOne(Seasons::className(), ['id' => 'season_id']);
    }

    /**
     * Gets query for [[Team]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Teams::className(), ['id' => 'team_id']);
    }
}
