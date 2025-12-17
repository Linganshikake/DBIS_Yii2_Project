<?php
/**
 * Team: DBIS_Yii2_Project (您的团队名称)
 * Coding by: 您的姓名 (您的学号), 2025xxxx (日期)
 * This is the model class for table "此处填表名".
 */

namespace common\models;

use Yii;

/**
 * This is the model class for table "players".
 *
 * @property int $id 选手ID
 * @property string $name 真实姓名
 * @property string $register_name 注册名/比赛用名
 * @property string $gender 性别
 * @property string|null $nickname 绰号
 * @property int|null $team_id 当前所属队伍ID (外键)
 * @property int|null $org_id 所属团体ID (外键)
 * @property string|null $org_rank 团体段位/等级
 * @property string|null $join_date 加入M联赛时间
 * @property int $display_status 展示状态 1:显示 0:隐藏
 *
 * @property PlayerSeasonStats[] $playerSeasonStats
 * @property Seasons[] $seasons
 * @property Organizations $org
 * @property Teams $team
 * @property SeasonTitles[] $seasonTitles
 */
class Player extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}s
     */
    public static function tableName()
    {
        return 'players';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'register_name', 'gender'], 'required'],
            [['gender'], 'string'],
            [['team_id', 'org_id', 'display_status'], 'integer'],
            [['join_date'], 'safe'],
            [['name', 'register_name', 'nickname'], 'string', 'max' => 100],
            [['org_rank'], 'string', 'max' => 50],
            [['org_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['org_id' => 'id']],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['team_id' => 'id']],
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
            'register_name' => 'Register Name',
            'gender' => 'Gender',
            'nickname' => 'Nickname',
            'team_id' => 'Team ID',
            'org_id' => 'Org ID',
            'org_rank' => 'Org Rank',
            'join_date' => 'Join Date',
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
        return $this->hasMany(PlayerSeasonStats::className(), ['player_id' => 'id']);
    }

    /**
     * Gets query for [[Seasons]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeasons()
    {
        return $this->hasMany(Seasons::className(), ['id' => 'season_id'])->viaTable('player_season_stats', ['player_id' => 'id']);
    }

    /**
     * Gets query for [[Org]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrg()
    {
        return $this->hasOne(Organizations::className(), ['id' => 'org_id']);
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

    /**
     * Gets query for [[SeasonTitles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeasonTitles()
    {
        return $this->hasMany(SeasonTitles::className(), ['player_id' => 'id']);
    }
}
