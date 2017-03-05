<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\models;

use Yii;
use yii\helpers\Html;
use common\models\User;
use common\models\Posts;
use common\models\PostAttributes;

/**
 * 评论模型
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class Comments extends \yii\db\ActiveRecord
{
    const TEXT_MAX_LENGHT = 10000;
    const DEFAULT_HP = 100;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comments}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'user_id', 'content', 'comment_at'], 'required'],
            [['post_id', 'user_id', 'replies', 'stand', 'hp', 'isforever', 'isdie', 'apps', 'opps', 'neutrals', 'status'], 'integer'],
            [['content'], 'string'],
            [['apps', 'opps', 'neutrals'], 'default', 'value' => 0],
            [['stand'], 'in', 'range' => [-1, 0, 1]],
            ['hp', 'default', 'value' => Comments::DEFAULT_HP],
            [['post_id', 'user_id'], 'unique', 'targetAttribute' => ['post_id', 'user_id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Posts::className(), 'targetAttribute' => ['post_id' => 'postid']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'uid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'commentid' => Yii::t('common/comment', 'Commentid'),
            'post_id' => Yii::t('common/comment', 'Post ID'),
            'user_id' => Yii::t('common/comment', 'User ID'),
            'content' => Yii::t('common/comment', 'Content'),
            'replies' => Yii::t('common/comment', 'Replies'),
            'stand' => Yii::t('common/comment', 'Stand'),
            'status' => Yii::t('common/comment', 'Status'),
            'hp' => Yii::t('common/comment', 'HP'),
            'isforever' => Yii::t('common/comment', 'Isforever'),
            'isdie' => Yii::t('common/comment', 'Isdie'),
            'os' => Yii::t('common/post', 'User Login OS'),
            'apps' => Yii::t('common/post', 'Applaud number of people'),
            'opps' => Yii::t('common/post', 'Opposed number of people'),
            'neutrals' => Yii::t('common/post', 'Post Neutrals'),
            'comment_at' => Yii::t('common/comment', 'Comment At'),
            'updated_at' => Yii::t('common/comment', 'Updated At'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['uid' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Replies::className(), ['comment_id' => 'commentid'])
        ->with([
            'user' => function ($query) {
                $query->select(['uid', 'nickname', 'head', 'sex', 'isauth']);
            },
            'replyTo' => function ($query) {
                $query->select(['uid', 'nickname']);
            },
        ]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserComment($postid, $uid)
    {
        if ($postid && $uid) {
            return $this->find()->where(['post_id' => intval($postid), 'user_id' => intval($uid)]);
        }
        return null;
    }

    /**
     * 获取评论立场的字符表达
     * @param  [type] $stand [description]
     * @return [type]        [description]
     */
    public function transformCommentStand($stand)
    {
        switch ($stand) {
            case 1:
                $str = '点赞';
                break;
            case -1:
                $str = '吐槽';
                break;
            default:
                $str = '点评';
        }
        return $str;
    }
    /**
     * 添加评论
     * @param array $content 评论表单
     */
    public function addComment($content)
    {
        $this->post_id    = (int) $content['postid'];
        $this->user_id    = Yii::$app->user->getId();
        $this->content    = $content['comment'];
        $this->stand      = (int) $content['stand'];
        $this->os         = Yii::$app->getSession()->get('loginOS');
        $this->comment_at = date('Y-m-d H:i:s');
        $this->updated_at = $this->comment_at;

        if ($this->save()) {
            if ($this->stand == 1) {
                PostAttributes::increaseApps($content['postid'], Yii::$app->params['commentWealth']['apps_hp']);
            } else if ($this->stand == -1) {
                PostAttributes::increaseOpps($content['postid'], Yii::$app->params['commentWealth']['opps_hp']);
            }
            User::increaseComment($this->user_id);

            return true;
        }

        return false;
    }
    /**
     * 增加正方评论数
     * @param  integer $commentid 评论ID
     * @param  integer $hp        改变的生命值
     * @return boolean|integer            成功或失败
     */
    public static function increaseApps($commentid, $hp)
    {
        if ($commentid) {
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET apps = `apps` + 1, replies = `replies` + 1, hp = `hp` + ' .intval($hp). ' WHERE commentid =:commentid', [':commentid' => intval($commentid)])->execute();
        }
        return false;
    }
    /**
     * 增加反方评论数
     * @param  integer $commentid 评论ID
     * @param  integer $hp        改变的生命值
     * @return boolean|integer            成功或失败
     */
    public static function increaseOpps($commentid, $hp)
    {
        if ($commentid) {
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET opps = `opps` + 1, replies = `replies` + 1, hp = `hp` + ' .intval($hp). ' WHERE commentid =:commentid', [':commentid' => intval($commentid)])->execute();
        }
        return false;
    }

    /**
     * 改变生命值
     * @param  integer $commentid 评论ID
     * @param  integer $hp        改变的生命值
     * @return boolean|integer            成功或失败
     */
    public static function changeHp($commentid, $hp)
    {
        if ($commentid) {
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET hp = `hp` + ' .intval($hp). ' WHERE commentid =:commentid', [':commentid' => intval($commentid)])->execute();
        }
        return false;
    }

    /**
     * 检测是否可以发表点评
     * @param  integer $postid 文章id
     * @return boolean|string true可以回复，string不可以回复的提示文字
     */
    public static function canComment($postid)
    {
        $user = Yii::$app->getUser()->getIdentity();
        if ($user->status >= User::STATUS_BAN_COMMENT) {
            return '您被禁止发言!';
        }
        $post = Posts::findOne(['postid' => (int) $postid, 'static' => Posts::STATUS_ONLINE]);
        if (!$post) {
            return '此文章不存在或未发表。';
        }
        if ($post->islock || !$post->iscomment || $post->isdie) {
            return '此文章评论暂时被关闭。';
        }
        $myComment = Comments::findOne(['post_id' => $post->postid, 'user_id' => $user->id]);
        if ($myComment) {
            return '您已点评过此文章了。';
        }

        return true;
    }
}
