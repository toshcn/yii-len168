<?php

namespace common\models;

use Yii;
use common\models\Posts;

class PostViews extends \yii\db\ActiveRecord
{
    const YES = 1;
    const NO = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_views}}';
    }

    /**
     * 分类关系
     * @return [type] [description]
     */
    public function getTermRelations()
    {
        return $this->hasMany(TermRelations::className, ['objectid' => 'postid']);
    }

    /**
     * 获取文章
     * @return [type] [description]
     */
    public function getPostViews()
    {
        return $this->find()->where(['status' => Posts::STATUS_ONLINE]);
    }

    /**
     * 推荐文章
     * @return [type] [description]
     */
    public function getNicePostViews()
    {
        return $this->find()->where(['status' => Posts::STATUS_ONLINE, 'isnice' => Posts::YES]);
    }

    /**
     * 通过文章id获取
     * @param  [type] $postid [description]
     * @return [type]         [description]
     */
    public function getPostViewsByPost($postid)
    {
        return $this->find()->where(['postid' => intval($postid)])->andWhere(['!=', 'status', Posts::STATUS_DELETED]);
    }

    /**
     * 获取会员自己的文章视图
     * @return [type] [description]
     */
    public function getMyPostViews()
    {
        return $this->find()->where(['user_id' => Yii::$app->user->getId()])->andWhere(['not in', 'status', [Posts::STATUS_DELETED]]);
    }

    /**
     * 通过会员id获取会员的文章视图
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public function getPostViewsByUser($uid)
    {
        return $this->find()->where(['user_id' => intval($uid), 'status' => Posts::STATUS_ONLINE]);
    }

    /**
     * 首页顶部推荐文章
     * @return \yii\db\ActiveQuery
     */
    public function getTopPosts($limit = 10)
    {
        return $this->find()
            ->where(['isstick' => Posts::YES, 'status' => Posts::STATUS_ONLINE])
            ->limit(intval($limit));
    }
}
