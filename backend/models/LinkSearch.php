<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Links;

/**
 * LinkSearch represents the model behind the search form about `common\models\Links`.
 */
class LinkSearch extends Links
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['linkid', 'link_sort'], 'integer'],
            [['link_title', 'link_type', 'link_url', 'link_icon'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Links::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'linkid' => $this->linkid,
            'link_sort' => $this->link_sort,
        ]);

        $query->andFilterWhere(['like', 'link_title', $this->link_title])
            ->andFilterWhere(['like', 'link_type', $this->link_type])
            ->andFilterWhere(['like', 'link_url', $this->link_url])
            ->andFilterWhere(['like', 'link_icon', $this->link_icon]);

        return $dataProvider;
    }
}
