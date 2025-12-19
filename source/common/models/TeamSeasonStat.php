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
            [['season_id'], 'exist', 'skipOnError' => true, 'targetClass' => Season::className(), 'targetAttribute' => ['season_id' => 'id']],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => Team::className(), 'targetAttribute' => ['team_id' => 'id']],
            [['total_score'], 'number'],
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

        /**
     * 获取当前展示分数
     */
    public function getDisplayScore()
    {
        return $this->total_score;
    }


    /**
     * ★★★ 核心逻辑：在保存进数据库之前，自动计算总分 ★★★
     * M联赛规则：晋级下一轮时，当前总分折半，再加上新阶段的分数
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            
            // 1. 基础分：常规赛成绩
            // 如果常规赛没填，默认为0
            $score = $this->regular_score ?: 0;

            // 2. 判断是否有半决赛成绩 (不为 null 说明进入了半决赛)
            if ($this->semifinal_score !== null && $this->semifinal_score !== '') {
                // 逻辑：常规赛 × 0.5 + 半决赛
                $score = ($score * 0.5) + $this->semifinal_score;
            }

            // 3. 判断是否有决赛成绩 (不为 null 说明进入了决赛)
            if ($this->final_score !== null && $this->final_score !== '') {
                // 逻辑：(常规赛×0.5 + 半决赛) × 0.5 + 决赛
                // 此时 $score 已经是(常规赛×0.5 + 半决赛)了，所以直接 * 0.5 + 决赛即可
                $score = ($score * 0.5) + $this->final_score;
            }

            // 4. 将计算结果赋值给 total_score 字段
            // round($val, 1) 用于保留一位小数，防止浮点数精度问题
            $this->total_score = round($score, 1);

            return true;
        }
        return false;
    }
}


