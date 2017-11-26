<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\models;

use Yii;
use common\models\Posts;

class PostAttributes extends \yii\db\ActiveRecord
{
    const DEFAULT_HP = 100;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_attributes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id'], 'required'],
            ['hp', 'default', 'value' => self::DEFAULT_HP],
            [['post_id', 'golds', 'crystal', 'views', 'comments', 'apps', 'opps', 'neutrals'], 'default', 'value' => 0],
            [['post_id', 'hp', 'golds', 'crystal', 'views', 'comments', 'apps', 'opps', 'neutrals'], 'integer'],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Posts::className(), 'targetAttribute' => ['post_id' => 'postid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common/post', 'Post Attribute ID'),
            'post_id' => Yii::t('common/post', 'Post ID'),
            'hp' => Yii::t('common/post', 'Post HP'),
            'points' => Yii::t('common/post', 'Post Points'),
            'golds' => Yii::t('common/post', 'Post Golds'),
            'crystal' => Yii::t('common/post', 'Post Crystal'),
            'views' => Yii::t('common/post', 'Post Views'),
            'comments' => Yii::t('common/post', 'Post Comments'),
            'apps' => Yii::t('common/post', 'Applaud number of people'),
            'opps' => Yii::t('common/post', 'Opposed number of people'),
            'neutrals' => Yii::t('common/post', 'Post Neutrals'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Posts::className(), ['postid' => 'post_id']);
    }
    /**
     * 正方评论数加一
     * @param  integer $postid 文章ID
     * @param  integer $hp     改变的生命值
     * @return integer|boolean the number of rows affected, or false update
     */
    public static function increaseApps($postid, $hp)
    {
        if ($postid) {
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET apps = `apps` + 1, comments = `comments` + 1, hp = `hp` + '. intval($hp) . ' WHERE post_id =:post', [':post' => intval($postid)])->execute();
        }
        return false;
    }

    /**
     * 反方评论数加一
     * @param  integer $postid 文章ID
     * @param  integer $hp     改变的生命值
     * @return integer|boolean the number of rows affected, or false update
     */
    public static function increaseOpps($postid, $hp)
    {
        if ($postid) {
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET opps = `opps` + 1, comments = `comments` + 1, hp = `hp` + '. intval($hp) . ' WHERE post_id =:post', [':post' => intval($postid)])->execute();
        }
        return false;
    }


    /**
     * 增加文章浏览量
     * @param  integer $postid    文章ID
     * @return boolean|integer            成功或失败
     */
    public static function increaseViews($postid)
    {
        if ($postid) {
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET views = `views` + 1  WHERE post_id =:post', [':post' => intval($postid)])->execute();
        }
        return false;
    }
}
