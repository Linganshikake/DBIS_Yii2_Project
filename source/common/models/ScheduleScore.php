<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the ActiveRecord model for table "schedule_score" (比赛成绩记录)
 */

namespace common\models;

use Yii;

/**
 *
 * @property int $id 成绩ID
 * @property int $schedule_id 日程ID
 * @property int $game_number 场次
 * @property int|null $team_id1 队伍1 ID
 * @property int|null $team_id2 队伍2 ID
 * @property int|null $team_id3 队伍3 ID
 * @property int|null $team_id4 队伍4 ID
 * @property int|null $team1_player_id 队伍1出战选手ID
 * @property int|null $team2_player_id 队伍2出战选手ID
 * @property int|null $team3_player_id 队伍3出战选手ID
 * @property int|null $team4_player_id 队伍4出战选手ID
 * @property float $team1_score 队伍1得分
 * @property float $team2_score 队伍2得分
 * @property float $team3_score 队伍3得分
 * @property float $team4_score 队伍4得分
 * @property int $display_status 展示状态
 *
 * @property Schedule $schedule
 * @property Team $team1
 * @property Team $team2
 * @property Team $team3
 * @property Team $team4
 * @property Player $team1Player
 * @property Player $team2Player
 * @property Player $team3Player
 * @property Player $team4Player
 */
class ScheduleScore extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'schedule_score';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['schedule_id'], 'required'],
            [['schedule_id', 'game_number', 'team_id1', 'team_id2', 'team_id3', 'team_id4', 'team1_player_id', 'team2_player_id', 'team3_player_id', 'team4_player_id', 'display_status'], 'integer'],
            [['team1_score', 'team2_score', 'team3_score', 'team4_score'], 'number'],
            ['display_status', 'default', 'value' => 1],
            ['game_number', 'default', 'value' => 0],
            ['game_number', 'in', 'range' => [0, 1]],
            
            // 验证分数总和为0
            ['team1_score', 'validateScoreSum'],
            
            // 外键验证
            [['schedule_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schedule::class, 'targetAttribute' => ['schedule_id' => 'id']],
            [['team_id1'], 'exist', 'skipOnEmpty' => true, 'targetClass' => Team::class, 'targetAttribute' => ['team_id1' => 'id']],
            [['team_id2'], 'exist', 'skipOnEmpty' => true, 'targetClass' => Team::class, 'targetAttribute' => ['team_id2' => 'id']],
            [['team_id3'], 'exist', 'skipOnEmpty' => true, 'targetClass' => Team::class, 'targetAttribute' => ['team_id3' => 'id']],
            [['team_id4'], 'exist', 'skipOnEmpty' => true, 'targetClass' => Team::class, 'targetAttribute' => ['team_id4' => 'id']],
            [['team1_player_id'], 'exist', 'skipOnEmpty' => true, 'targetClass' => Player::class, 'targetAttribute' => ['team1_player_id' => 'id']],
            [['team2_player_id'], 'exist', 'skipOnEmpty' => true, 'targetClass' => Player::class, 'targetAttribute' => ['team2_player_id' => 'id']],
            [['team3_player_id'], 'exist', 'skipOnEmpty' => true, 'targetClass' => Player::class, 'targetAttribute' => ['team3_player_id' => 'id']],
            [['team4_player_id'], 'exist', 'skipOnEmpty' => true, 'targetClass' => Player::class, 'targetAttribute' => ['team4_player_id' => 'id']],
            
            // 唯一性验证
            [['schedule_id', 'game_number'], 'unique', 'targetAttribute' => ['schedule_id', 'game_number'], 'message' => '该日程的此场次成绩已存在'],
        ];
    }
    
    /**
     * 验证四个分数之和为0
     */
    public function validateScoreSum($attribute, $params)
    {
        $sum = $this->team1_score + $this->team2_score + $this->team3_score + $this->team4_score;
        if (abs($sum) > 0.01) { // 允许小误差
            $this->addError($attribute, '四个队伍的得分之和必须为0，当前总和为: ' . $sum);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'schedule_id' => '比赛日程',
            'game_number' => '场次',
            'team_id1' => '队伍1',
            'team_id2' => '队伍2',
            'team_id3' => '队伍3',
            'team_id4' => '队伍4',
            'team1_player_id' => '队伍1选手',
            'team2_player_id' => '队伍2选手',
            'team3_player_id' => '队伍3选手',
            'team4_player_id' => '队伍4选手',
            'team1_score' => '队伍1得分',
            'team2_score' => '队伍2得分',
            'team3_score' => '队伍3得分',
            'team4_score' => '队伍4得分',
            'display_status' => '展示状态',
        ];
    }

    // Relations
    public function getSchedule()
    {
        return $this->hasOne(Schedule::class, ['id' => 'schedule_id']);
    }

    // Team relations (使用schedule_score自己的team_id字段)
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

    // Player relations
    public function getTeam1Player()
    {
        return $this->hasOne(Player::class, ['id' => 'team1_player_id']);
    }

    public function getTeam2Player()
    {
        return $this->hasOne(Player::class, ['id' => 'team2_player_id']);
    }

    public function getTeam3Player()
    {
        return $this->hasOne(Player::class, ['id' => 'team3_player_id']);
    }

    public function getTeam4Player()
    {
        return $this->hasOne(Player::class, ['id' => 'team4_player_id']);
    }

    /**
     * 获取场次文本
     */
    public function getGameNumberText()
    {
        return $this->game_number == 0 ? '第一回战' : '第二回战';
    }

    /**
     * 获取排名后的分数数据
     * 使用schedule_score表自己的team_id字段来匹配选手和队伍
     */
    public function getRankedScores()
    {
        $scores = [
            ['team' => $this->team1, 'player' => $this->team1Player, 'score' => $this->team1_score],
            ['team' => $this->team2, 'player' => $this->team2Player, 'score' => $this->team2_score],
            ['team' => $this->team3, 'player' => $this->team3Player, 'score' => $this->team3_score],
            ['team' => $this->team4, 'player' => $this->team4Player, 'score' => $this->team4_score],
        ];
        
        // 按分数降序排列
        usort($scores, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        return $scores;
    }
}
