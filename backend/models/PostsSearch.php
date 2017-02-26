<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Posts;

/**
 * PostsSearch represents the model behind the search form about `common\models\Posts`.
 */
class PostsSearch extends Posts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['postid', 'user_id', 'content_len', 'copyright', 'spend', 'paytype', 'posttype', 'parent', 'status', 'islock', 'iscomment', 'isstick', 'isnice', 'isopen', 'ispay', 'isforever', 'isdie'], 'integer'],
            [['title', 'author', 'categorys', 'image', 'image_suffix', 'content', 'description', 'original_url', 'os', 'browser', 'created_at', 'updated_at'], 'safe'],
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
        $query = Posts::find();

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
            'postid' => $this->postid,
            'user_id' => $this->user_id,
            'content_len' => $this->content_len,
            'copyright' => $this->copyright,
            'spend' => $this->spend,
            'paytype' => $this->paytype,
            'posttype' => $this->posttype,
            'parent' => $this->parent,
            'status' => $this->status,
            'islock' => $this->islock,
            'iscomment' => $this->iscomment,
            'isstick' => $this->isstick,
            'isnice' => $this->isnice,
            'isopen' => $this->isopen,
            'ispay' => $this->ispay,
            'isforever' => $this->isforever,
            'isdie' => $this->isdie,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'categorys', $this->categorys])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'image_suffix', $this->image_suffix])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'original_url', $this->original_url])
            ->andFilterWhere(['like', 'os', $this->os])
            ->andFilterWhere(['like', 'browser', $this->browser]);

        $query->orderBy(['postid' => SORT_DESC]);

        return $dataProvider;
    }
}
