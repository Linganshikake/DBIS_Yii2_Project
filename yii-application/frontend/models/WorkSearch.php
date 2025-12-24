<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Work;

/**
 * WorkSearch represents the model behind the search form of `common\models\Work`.
 */
class WorkSearch extends Work
{
    /**
     * {@inheritdoc}
     */
    public $global;
    public function rules(): array
    {
        return [
            [['id', 'publication_year'], 'integer'],
            [['title', 'title_alt', 'work_type', 'language', 'abstract', 'source_name', 'doi', 'openalex_id', 'crossref_id', 'url', 'license'], 'safe'],
            [['global'], 'safe'], // ✅ 新增：全局搜索
            [['work_type', 'publication_year'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params) 
    {
        $query = Work::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 15],
            'sort' => [
                'defaultOrder' => ['publication_year' => SORT_DESC, 'id' => SORT_DESC],
                'attributes' => [
                    'id',
                    'title',
                    'work_type',
                    'publication_year',
                    'source_name',
                    'doi',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $global = trim((string)$this->global);
        if ($global !== '') {
            $query->andFilterWhere(['or',
                ['like', 'title', $global],
                ['like', 'title_alt', $global],
                ['like', 'abstract', $global],
                ['like', 'source_name', $global],
                ['like', 'doi', $global],
            ]);
        }

        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['publication_year' => $this->publication_year])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'title_alt', $this->title_alt])
            ->andFilterWhere(['like', 'work_type', $this->work_type])
            ->andFilterWhere(['like', 'source_name', $this->source_name]);

        $query->andFilterWhere(['work_type' => $this->work_type]);
        $query->andFilterWhere(['publication_year' => $this->publication_year]);
    

        return $dataProvider;
    }

}
