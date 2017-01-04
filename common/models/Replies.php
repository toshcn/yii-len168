<?php

namespace common\models;

use Yii;
use common\models\User;
use yii\helpers\Html;

/**
 * 评论回复活动记录
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class Replies extends \yii\db\ActiveRecord
{
    /** @var integer 是否回复评论，1是，0回复评论的回复 */
    public $iscomment = 0;

    public $hp = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%replies}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_id', 'content', 'user_id', 'reply_to', 'reply_at'], 'required'],
            [['comment_id', 'user_id', 'reply_to', 'stand'], 'integer'],
            [['content', 'os'], 'string'],
            [['reply_at'], 'safe'],
            [['comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comments::className(), 'targetAttribute' => ['comment_id' => 'commentid']],
            [['reply_to'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['reply_to' => 'uid']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'uid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'replyid' => Yii::t('common/comment', 'Replyid'),
            'comment_id' => Yii::t('common/comment', 'Comment'),
            'content' => Yii::t('common/comment', 'Content'),
            'user_id' => Yii::t('common/comment', 'User ID'),
            'reply_to' => Yii::t('common/comment', 'Reply To'),
            'stand' => Yii::t('common/comment', 'Stand'),
            'reply_at' => Yii::t('common/comment', 'Reply At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasOne(Comments::className(), ['commentid' => 'comment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReplyTo()
    {
        return $this->hasOne(User::className(), ['uid' => 'reply_to']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['uid' => 'user_id']);
    }

    public function addReply($reply)
    {
        $this->user_id      = Yii::$app->user->getId();
        $this->comment_id   = $reply['comment'];
        $this->content   = $reply['content'];
        $this->stand     = $reply['stand'];
        $this->os        = Yii::$app->session->get('loginOS');
        $this->reply_to  = $reply['to'];
        $this->reply_at  = date('Y-m-d H:i:s');
        $this->iscomment = intval($reply['iscomment']);
        $result          = $this->save();

        if ($result && $this->iscomment) {
            if ($this->stand == 1) {
                $this->hp = 1;
                Comments::increaseApps($reply['comment'], $this->hp);
            } else if ($this->stand == -1) {
                $this->hp = -1;
                Comments::increaseOpps($reply['comment'], $this->hp);
            }
        }
        return $result;
    }
}
