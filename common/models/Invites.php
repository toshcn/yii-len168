<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\models;

use Yii;
use yii\helpers\Url;
use commont\models\User;

/**
 * 邀请码
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class Invites extends \yii\db\ActiveRecord
{
    const YES = 1; //状态真
    const NO  = 0; //状态
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%invites}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'send_at'], 'required'],
            [['user_id', 'isuse', 'isbind'], 'integer'],
            [['email'], 'string', 'max' => 64],
            [['code'], 'string', 'max' => 20],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'uid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common/user', 'ID'),
            'user_id' => Yii::t('common/user', 'User'),
            'email' => Yii::t('common/user', 'Email'),
            'code' => Yii::t('common/user', 'Code'),
            'isuse' => Yii::t('common/user', 'Isuse'),
            'isbind' => Yii::t('common/user', 'Isbind'),
            'send_at' => Yii::t('common/user', 'Send At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['uid' => 'user_id']);
    }

    /**
     * 通过邀请码查询记录
     * @param  string $code 32位邀请码
     * @return \yii\db\ActiveQuery
     */
    public function findByCode($code)
    {
        if (strlen($code) === 32) {
            return $this->find()->where(['code' => $code])->one();
        }
        return null;
    }

    public function getSelfInvite($id = 0)
    {
        if ($id = intval($id)) {
            return $this->find()->where(['id' => $id, 'user_id' => Yii::$app->user->getId()])->one();
        }
        return null;
    }


    /**
     * 发送邀请码到邀请邮箱
     *
     * @param  string $name 邀请者名称
     * @param  string $title 邮件标题
     * @return boolean whether the email has been sent successfully
     */
    public function sendEmail($name = '', $title = '')
    {
        $data = [
            'template' => [
                'html' => 'invitateGuest-html',
                'text' => 'invitateGuest-text',
            ],
            'invitateCode' => $this->code,
            'invitateUrl' => Url::to(['/ucenter/account/signup', 'code' => $this->code], Yii::$app->params['httpProtocol']),
            'inviter' => $name,
            'sendTo' => $this->email,
            'subject' => $title ? $title : $name.'邀您注册' . Yii::$app->params['siteDomain'] . '会员'
        ];

        return \Yii::$app->beanstalk->putInTube('tubeInvitEmail', $data);
    }

    /**
     * 重发邀请码
     *
     * @param  string $name 邀请者名称
     * @return boolean     是否发送成功
     */
    public function resendEmail($name = '')
    {
        if ($this->isuse == 0 && $this->sendEmail($name)) {
            $this->send_at = date('Y-m-d H:i:s');
            return $this->save(false);
        }
        return false;
    }
}
