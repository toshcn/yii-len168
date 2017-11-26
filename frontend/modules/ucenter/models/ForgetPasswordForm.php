<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license
 */

namespace frontend\modules\ucenter\models;

use Yii;
use yii\helpers\Url;
use yii\base\Model;
use common\models\User;
use common\models\Tokens;

/**
 * 申请重置密码表单
 */
class ForgetPasswordForm extends Model
{
    public $username;
    /** @var string 验证码 */
    public $captcha;
    /** @var object common\models\User */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'captcha'], 'trim'],
            [['username', 'captcha'],'required'],
            ['captcha', 'captcha', 'captchaAction' => '/ucenter/account/captcha'],
            ['username', 'validateUsername'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('common/label', 'User Name'),
            'captcha' => Yii::t('common/label', 'Captcha'),
        ];
    }

    /**
     * Validates the username.
     * This method serves as the inline validation for username.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = new User();
            $this->_user = $user->findByUsername($this->$attribute);
            if (!$this->_user) {
                $this->addError($attribute, Yii::t('common/sentence', 'This account is not exists!'));
            }
        }
    }
    /**
     * @inheritdoc
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * 发送重置密码链接到会员注册邮箱
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        if ($this->_user && $this->_user->generatePasswordResetToken()) {
            $data = [
                'template' => [
                    'html' => 'passwordResetToken-html',
                    'text' => 'passwordResetToken-text'
                ],
                'nickname' => $this->_user->nickname,
                'resetLink' => Url::to(['/ucenter/account/reset-password', 'user' => $this->_user->uid, 'token' => $this->_user->reset_token], Yii::$app->params['httpProtocol']),
                'sendTo' => $this->_user->email,
                'subject' => Yii::$app->params['siteDomain'] . '-' . Yii::t('common/sentence', 'Reset login password link, This is an system mail do not reply!')
            ];
            return \Yii::$app->beanstalk->putInTube('tubeForgetPwdEmail', $data);
        }

        return false;
    }
}
