<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PlayerSeasonStat;

/**
 * PlayerSeasonStatSearch represents the model behind the search form of `common\models\PlayerSeasonStat`.
 */
class PlayerSeasonStatSearch extends PlayerSeasonStat
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'player_id', 'season_id', 'team_id', 'games_count', 'rank_1_count', 'rank_2_count', 'rank_3_count', 'rank_4_count', 'max_score', 'display_status'], 'integer'],
            [['total_score', 'avg_rank', 'top_rate', 'last_avoid_rate'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PlayerSeasonStat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'total_score' => SORT_DESC, 
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'player_id' => $this->player_id,
            'season_id' => $this->season_id,
            'team_id' => $this->team_id,
            'games_count' => $this->games_count,
            'total_score' => $this->total_score,
            'rank_1_count' => $this->rank_1_count,
            'rank_2_count' => $this->rank_2_count,
            'rank_3_count' => $this->rank_3_count,
            'rank_4_count' => $this->rank_4_count,
            'max_score' => $this->max_score,
            'avg_rank' => $this->avg_rank,
            'top_rate' => $this->top_rate,
            'last_avoid_rate' => $this->last_avoid_rate,
            'display_status' => $this->display_status,
        ]);

        return $dataProvider;
    }
}
