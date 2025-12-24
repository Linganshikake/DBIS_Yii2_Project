<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the ActiveRecord model for table "seasons" (赛季模型)
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
            'name' => '赛季名称',
            'start_date' => '开始日期',
            'end_date' => '结束日期',
            'is_current' => '是否当前赛季',
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
        return $this->hasMany(PlayerSeasonStat::className(), ['season_id' => 'id']);
    }

    /**
     * Gets query for [[Players]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayers()
    {
        return $this->hasMany(Player::className(), ['id' => 'player_id'])->viaTable('player_season_stats', ['season_id' => 'id']);
    }

    /**
     * Gets query for [[SeasonTitles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeasonTitles()
    {
        return $this->hasMany(SeasonTitle::className(), ['season_id' => 'id']);
    }

    /**
     * Gets query for [[TeamSeasonStats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeamSeasonStats()
    {
        return $this->hasMany(TeamSeasonStat::className(), ['season_id' => 'id']);
    }

    /**
     * Gets query for [[Teams]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeams()
    {
        return $this->hasMany(Team::className(), ['id' => 'team_id'])->viaTable('team_season_stats', ['season_id' => 'id']);
    }
}
