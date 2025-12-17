<?php
/**
 * Team: DBIS_Yii2_Project (您的团队名称)
 * Coding by: 您的姓名 (您的学号), 2025xxxx (日期)
 * This is the model class for table "此处填表名".
 */

namespace common\models;

use Yii;

/**
 * This is the model class for table "teams".
 *
 * @property int $id 队伍ID
 * @property string $name 队伍名称
 * @property string|null $supervisor 监督/教练姓名
 * @property string|null $company 所属企业
 * @property string|null $description 队伍简介
 * @property int $display_status 展示状态 1:显示 0:隐藏
 *
 * @property PlayerSeasonStats[] $playerSeasonStats
 * @property Players[] $players
 * @property TeamSeasonStats[] $teamSeasonStats
 * @property Seasons[] $seasons
 */
class Team extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teams';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['display_status'], 'integer'],
            ['display_status', 'default', 'value' => 1],
            [['name', 'supervisor', 'company'], 'string', 'max' => 100],
            [['name'], 'unique'],
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
            'supervisor' => 'Supervisor',
            'company' => 'Company',
            'description' => 'Description',
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
        return $this->hasMany(PlayerSeasonStats::className(), ['team_id' => 'id']);
    }

    /**
     * Gets query for [[Players]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayers()
    {
        return $this->hasMany(Players::className(), ['team_id' => 'id']);
    }

    /**
     * Gets query for [[TeamSeasonStats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeamSeasonStats()
    {
        return $this->hasMany(TeamSeasonStats::className(), ['team_id' => 'id']);
    }

    /**
     * Gets query for [[Seasons]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeasons()
    {
        return $this->hasMany(Seasons::className(), ['id' => 'season_id'])->viaTable('team_season_stats', ['team_id' => 'id']);
    }
}
