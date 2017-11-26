<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'group', 'mobile', 'sex', 'hp', 'golds', 'crystal', 'posts', 'comments', 'friends', 'followers', 'isauth', 'status', 'safe_level'], 'integer'],
            [['username', 'nickname', 'email', 'head', 'auth_key', 'password', 'reset_token', 'reset_token_expire', 'motto', 'os', 'browser', 'created_at', 'updated_at'], 'safe'],
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
        $query = User::find();

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
            'uid' => $this->uid,
            'group' => $this->group,
            'mobile' => $this->mobile,
            'sex' => $this->sex,
            'reset_token_expire' => $this->reset_token_expire,
            'hp' => $this->hp,
            'golds' => $this->golds,
            'crystal' => $this->crystal,
            'posts' => $this->posts,
            'comments' => $this->comments,
            'friends' => $this->friends,
            'followers' => $this->followers,
            'isauth' => $this->isauth,
            'status' => $this->status,
            'safe_level' => $this->safe_level,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'head', $this->head])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'reset_token', $this->reset_token])
            ->andFilterWhere(['like', 'motto', $this->motto])
            ->andFilterWhere(['like', 'os', $this->os])
            ->andFilterWhere(['like', 'browser', $this->browser]);

        $query->orderBy(['uid' => SORT_DESC]);
        return $dataProvider;
    }
}
