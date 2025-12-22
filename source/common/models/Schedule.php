<?php
/**
 * Team: DBIS_Yii2_Project
 * This is the model class for table "schedule".
 */

namespace common\models;

use Yii;

/**
 * This is the model class for table "schedule".
 *
 * @property int $id 日程ID
 * @property string $match_date 比赛日期
 * @property string $day_of_week 星期几
 * @property int $team_id1 参赛队伍1
 * @property int $team_id2 参赛队伍2
 * @property int $team_id3 参赛队伍3
 * @property int $team_id4 参赛队伍4
 * @property int|null $top_team_id 首位队伍ID
 * @property int $season_id 赛季ID
 * @property int $match_status 比赛状态
 * @property int $display_status 展示状态
 *
 * @property Team $team1
 * @property Team $team2
 * @property Team $team3
 * @property Team $team4
 * @property Team $topTeam
 * @property Season $season
 * @property ScheduleScore[] $scheduleScores
 */
class Schedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['match_date', 'day_of_week', 'team_id1', 'team_id2', 'team_id3', 'team_id4', 'season_id'], 'required'],
            [['match_date'], 'safe'],
            [['team_id1', 'team_id2', 'team_id3', 'team_id4', 'top_team_id', 'season_id', 'match_status', 'display_status'], 'integer'],
            [['day_of_week'], 'string', 'max' => 10],
            ['display_status', 'default', 'value' => 1],
            ['match_status', 'default', 'value' => 0],
            [['team_id1'], 'exist', 'skipOnError' => true, 'targetClass' => Team::class, 'targetAttribute' => ['team_id1' => 'id']],
            [['team_id2'], 'exist', 'skipOnError' => true, 'targetClass' => Team::class, 'targetAttribute' => ['team_id2' => 'id']],
            [['team_id3'], 'exist', 'skipOnError' => true, 'targetClass' => Team::class, 'targetAttribute' => ['team_id3' => 'id']],
            [['team_id4'], 'exist', 'skipOnError' => true, 'targetClass' => Team::class, 'targetAttribute' => ['team_id4' => 'id']],
            [['season_id'], 'exist', 'skipOnError' => true, 'targetClass' => Season::class, 'targetAttribute' => ['season_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'match_date' => '比赛日期',
            'day_of_week' => '星期',
            'team_id1' => '队伍1',
            'team_id2' => '队伍2',
            'team_id3' => '队伍3',
            'team_id4' => '队伍4',
            'top_team_id' => '首位队伍',
            'season_id' => '赛季',
            'match_status' => '比赛状态',
            'display_status' => '展示状态',
        ];
    }

    // Relations
    public function getTeam1()
    {
        return $this->hasOne(Team::class, ['id' => 'team_id1']);
    }

    public function getTeam2()
    {
        return $this->hasOne(Team::class, ['id' => 'team_id2']);
    }

    public function getTeam3()
    {
        return $this->hasOne(Team::class, ['id' => 'team_id3']);
    }

    public function getTeam4()
    {
        return $this->hasOne(Team::class, ['id' => 'team_id4']);
    }

    public function getTopTeam()
    {
        return $this->hasOne(Team::class, ['id' => 'top_team_id']);
    }

    public function getSeason()
    {
        return $this->hasOne(Season::class, ['id' => 'season_id']);
    }

    public function getScheduleScores()
    {
        return $this->hasMany(ScheduleScore::class, ['schedule_id' => 'id']);
    }

    /**
     * 获取所有参赛队伍
     */
    public function getTeams()
    {
        return [$this->team1, $this->team2, $this->team3, $this->team4];
    }

    /**
     * 获取最近的比赛日程（按比赛日数量，非场次数量）
     * @param int $days 比赛日数量
     * @return array
     */
    public static function getUpcomingSchedules($days = 3)
    {
        // 先获取不重复的比赛日期
        $dates = self::find()
            ->select(['match_date'])
            ->distinct()
            ->where(['display_status' => 1])
            ->andWhere(['>=', 'match_date', date('Y-m-d')])
            ->orderBy(['match_date' => SORT_ASC])
            ->limit($days)
            ->column();
        
        if (empty($dates)) {
            return [];
        }
        
        // 获取这些日期的所有比赛
        return self::find()
            ->where(['display_status' => 1])
            ->andWhere(['in', 'match_date', $dates])
            ->orderBy(['match_date' => SORT_ASC])
            ->all();
    }

    /**
     * 按月份获取日程
     */
    public static function getSchedulesByMonth($year, $month)
    {
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));
        
        return self::find()
            ->where(['display_status' => 1])
            ->andWhere(['between', 'match_date', $startDate, $endDate])
            ->orderBy(['match_date' => SORT_ASC])
            ->all();
    }

    /**
     * 获取涉及特定队伍的日程
     */
    public static function getSchedulesByTeam($teamId, $limit = null)
    {
        $query = self::find()
            ->where(['display_status' => 1])
            ->andWhere([
                'or',
                ['team_id1' => $teamId],
                ['team_id2' => $teamId],
                ['team_id3' => $teamId],
                ['team_id4' => $teamId],
            ])
            ->orderBy(['match_date' => SORT_ASC]);
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->all();
    }
    
    /**
     * 获取涉及特定队伍的未来日程
     */
    public static function getUpcomingSchedulesByTeam($teamId, $limit = 3)
    {
        return self::find()
            ->where(['display_status' => 1])
            ->andWhere(['>=', 'match_date', date('Y-m-d')])
            ->andWhere([
                'or',
                ['team_id1' => $teamId],
                ['team_id2' => $teamId],
                ['team_id3' => $teamId],
                ['team_id4' => $teamId],
            ])
            ->orderBy(['match_date' => SORT_ASC])
            ->limit($limit)
            ->all();
    }

    /**
     * 获取比赛状态文本
     */
    public function getStatusText()
    {
        $statusMap = [
            0 => '未开始',
            1 => '进行中',
            2 => '已结束',
        ];
        return $statusMap[$this->match_status] ?? '未知';
    }
    
    /**
     * 检查队伍是否为首位
     */
    public function isTopTeam($teamId)
    {
        return $this->top_team_id == $teamId;
    }
}
