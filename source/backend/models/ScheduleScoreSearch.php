<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the backend search model for ScheduleScore (比赛成绩搜索模型)
 */

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ScheduleScore;

/**
 * ScheduleScoreSearch represents the model behind the search form of `common\models\ScheduleScore`.
 */
class ScheduleScoreSearch extends ScheduleScore
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'schedule_id', 'game_number', 'team1_player_id', 'team2_player_id', 'team3_player_id', 'team4_player_id', 'display_status'], 'integer'],
            [['team1_score', 'team2_score', 'team3_score', 'team4_score'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     */
    public function search($params)
    {
        $query = ScheduleScore::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'schedule_id' => $this->schedule_id,
            'game_number' => $this->game_number,
            'team1_player_id' => $this->team1_player_id,
            'team2_player_id' => $this->team2_player_id,
            'team3_player_id' => $this->team3_player_id,
            'team4_player_id' => $this->team4_player_id,
            'team1_score' => $this->team1_score,
            'team2_score' => $this->team2_score,
            'team3_score' => $this->team3_score,
            'team4_score' => $this->team4_score,
            'display_status' => $this->display_status,
        ]);

        return $dataProvider;
    }
}
