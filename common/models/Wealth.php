<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%wealth}}".
 *
 * @property integer $id
 * @property integer $user
 * @property integer $points
 * @property integer $golds
 * @property integer $crystal
 * @property integer $posts
 * @property integer $friends
 * @property integer $followers
 *
 * @property User $u
 */
class Wealth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wealth}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user'], 'required'],
            [['user', 'golds', 'crystal', 'posts', 'friends', 'followers'], 'integer'],
            [['user'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common/wealth', 'ID'),
            'user' => Yii::t('common/wealth', 'User ID'),
            'golds' => Yii::t('common/wealth', 'Gold'),
            'crystal' => Yii::t('common/wealth', 'Crystal'),
            'posts' => Yii::t('common/wealth', 'Post'),
            'friends' => Yii::t('common/wealth', 'Friend'),
            'followers' => Yii::t('common/wealth', 'Follower'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['uid' => 'user']);
    }

    /**
     * 增加文章发表数量
     * @param  integer $user 会员id
     * @return boolean
     */
    public static function increasePost($user)
    {
        if ($user) {
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET posts = `posts` + 1 WHERE user =:user', [':user' => intval($user)])->execute();
        }
        return false;
    }
    /**
     * 减少文章发表数量
     * @param  integer $user 会员id
     * @return boolean
     */
    public static function decreasePost($user)
    {
        if ($user) {
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET posts = `posts` - 1 WHERE user =:user', [':user' => intval($user)])->execute();
        }
        return false;
    }

    /**
     * 增加会员评论数评论
     * @param  integer $user 会员id
     * @return boolean
     */
    public static function increaseComment($user)
    {
        if ($user) {
            $hp = intval(Yii::$app->params['userWealth']['comment_hp']);
            $gold = intval(Yii::$app->params['userWealth']['comment_gold']);
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET hp = `hp` + ' . $hp . ', golds = `golds` + '. $gold .', comments = `comments` + ' .intval($hp). ' WHERE user =:user', [':user' => intval($user)])->execute();
        }
        return false;
    }

    /**
     * 增加关注数量
     * @param  integer $user 会员id
     * @return boolean
     */
    public static function increaseFriend($user)
    {
        if ($user) {
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET friends = `friends` + 1 WHERE user =:user', [':user' => intval($user)])->execute();
        }
        return false;
    }

    /**
     * 增加粉丝数量
     * @param  integer $user 会员id
     * @return boolean
     */
    public static function increaseFollower($user)
    {
        if ($user) {
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET followers = `followers` + 1 WHERE user =:user', [':user' => intval($user)])->execute();
        }
        return false;
    }

    /**
     * 减少关注数量
     * @param  integer $user 会员id
     * @return boolean
     */
    public static function decreaseFriend($user)
    {
        if ($user) {
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET friends = `friends` - 1 WHERE user =:user', [':user' => intval($user)])->execute();
        }
        return false;
    }

    /**
     * 减少粉丝数量
     * @param  integer $user 会员id
     * @return boolean
     */
    public static function decreaseFollower($user)
    {
        if ($user) {
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET followers = `followers` - 1 WHERE user =:user', [':user' => intval($user)])->execute();
        }
        return false;
    }
}
