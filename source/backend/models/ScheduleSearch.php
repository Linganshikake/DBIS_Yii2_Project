<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Schedule;

/**
 * ScheduleSearch represents the model behind the search form of `common\models\Schedule`.
 */
class ScheduleSearch extends Schedule
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'team_id1', 'team_id2', 'team_id3', 'team_id4', 'top_team_id', 'season_id', 'match_status', 'display_status'], 'integer'],
            [['match_date', 'day_of_week'], 'safe'],
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
        $query = Schedule::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['match_date' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'match_date' => $this->match_date,
            'team_id1' => $this->team_id1,
            'team_id2' => $this->team_id2,
            'team_id3' => $this->team_id3,
            'team_id4' => $this->team_id4,
            'top_team_id' => $this->top_team_id,
            'season_id' => $this->season_id,
            'match_status' => $this->match_status,
            'display_status' => $this->display_status,
        ]);

        $query->andFilterWhere(['like', 'day_of_week', $this->day_of_week]);

        return $dataProvider;
    }
}
