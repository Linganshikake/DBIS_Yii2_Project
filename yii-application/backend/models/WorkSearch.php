<?php
namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Work;

class WorkSearch extends Work
{
    public $global;

    public function rules(): array
    {
        return [
            [['id', 'publication_year'], 'integer'],
            [['title', 'title_alt', 'work_type', 'language', 'abstract', 'source_name', 'doi', 'url'], 'safe'],
             [['global'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Work::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 15],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => ['id','title','work_type','publication_year','source_name','doi','created_at','updated_at'],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['publication_year' => $this->publication_year]);
        $query->andFilterWhere(['work_type' => $this->work_type]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'source_name', $this->source_name]);
        $query->andFilterWhere(['like', 'doi', $this->doi]);

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


        return $dataProvider;
    }
}
