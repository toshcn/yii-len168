<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Terms;

/**
 * TermsSearch represents the model behind the search form about `common\models\Terms`.
 */
class PostCategorySearch extends Terms
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['termid', 'parent', 'counts'], 'integer'],
            [['title', 'slug'], 'trim'],
            [['title', 'slug'], 'string', 'max' => 128],
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
        $query = Terms::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'termid' => $this->termid,
            'slug' => $this->slug,
            'parent' => $this->parent,
            'counts' => $this->counts,
            'catetype' => Terms::CATEGORY_CATE,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);
        return $dataProvider;
    }
}
