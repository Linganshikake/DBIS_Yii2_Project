<?php
/**
 * Team: DBIS_Yii2_Project (您的团队名称)
 * Coding by: 您的姓名 (您的学号), 2025xxxx (日期)
 * This is the model class for table "此处填表名".
 */

namespace common\models;

use Yii;

/**
 * This is the model class for table "seasons".
 *
 * @property int $id 赛季ID
 * @property string $name 赛季名称，如 2023-24 Season
 * @property string|null $start_date 开始日期
 * @property string|null $end_date 结束日期
 * @property int|null $is_current 是否为当前赛季 1:是 0:否
 * @property int $display_status 展示状态 1:显示 0:隐藏
 *
 * @property PlayerSeasonStats[] $playerSeasonStats
 * @property Players[] $players
 * @property SeasonTitles[] $seasonTitles
 * @property TeamSeasonStats[] $teamSeasonStats
 * @property Teams[] $teams
 */
class Season extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'seasons';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['is_current', 'display_status'], 'integer'],
            [['name'], 'string', 'max' => 50],
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
            'name' => 'Name',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'is_current' => 'Is Current',
            'display_status' => 'Display Status',
        ];
    }

    /**
     * Gets query for [[PlayerSeasonStats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerSeasonStats()
    {
        return $this->hasMany(PlayerSeasonStats::className(), ['season_id' => 'id']);
    }

    /**
     * Gets query for [[Players]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayers()
    {
        return $this->hasMany(Players::className(), ['id' => 'player_id'])->viaTable('player_season_stats', ['season_id' => 'id']);
    }

    /**
     * Gets query for [[SeasonTitles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeasonTitles()
    {
        return $this->hasMany(SeasonTitles::className(), ['season_id' => 'id']);
    }

    /**
     * Gets query for [[TeamSeasonStats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeamSeasonStats()
    {
        return $this->hasMany(TeamSeasonStats::className(), ['season_id' => 'id']);
    }

    /**
     * Gets query for [[Teams]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeams()
    {
        return $this->hasMany(Teams::className(), ['id' => 'team_id'])->viaTable('team_season_stats', ['season_id' => 'id']);
    }
}
