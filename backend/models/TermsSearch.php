<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Terms;

/**
 * TermsSearch represents the model behind the search form about `common\models\Terms`.
 */
class TermsSearch extends Terms
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['termid', 'integer'],
            [['title', 'slug'], 'trim'],
            [['title', 'slug'], 'string', 'max' => 128],
            [['catetype'], 'string', 'max' => 32],
            [['catetype'], 'in', 'range' => [self::CATEGORY_CATE, self::CATEGORY_MENU, self::CATEGORY_TAG, self::CATEGORY_LINK]],
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
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'catetype', $this->catetype])
            ->andFilterWhere(['like', 'description', $this->description]);
        return $dataProvider;
    }
}
