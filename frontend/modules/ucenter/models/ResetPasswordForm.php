<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license
 */


namespace frontend\modules\ucenter\models;

use Yii;
use yii\helpers\Url;
use yii\base\InvalidParamException;
use yii\base\Model;
use common\models\User;
use common\models\Tokens;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $repassword;

    /** @var object commom\models\User */
    private $_user;
    private $_token;


    /**
     * Creates a form model given a token.
     *
     * @param  string   $token
     * @param  integer  $user
     * @param  array    $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $user, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException(Yii::t('common/sentence', 'Invalid token'));
        }
        $this->_user = User::findByResetToken($user, $token);
        if (!$this->_user) {
            throw new InvalidParamException(Yii::t('common/sentence', 'This link has failed'));
        }
        $this->_token = $token;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'repassword'], 'required'],
            [['password', 'repassword'], 'string', 'min' => 8, 'max' => 20],
            ['password', 'match', 'pattern' => '/^[A-Za-z]\w+$/', 'message' => Yii::t('common/sentence', 'English begin and English numeral underline combination.')],
            ['repassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('common/sentence', 'Password is not the same as the input.')],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('common/label', 'New Password'),
            'repassword' => Yii::t('common/label', 'Confirm Password'),
        ];
    }

    public function getToken()
    {
        return $this->_token;
    }

    public function getUser()
    {
        return $this->_user;
    }


    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $this->_user->setPassword($this->password);
        $this->_user->generateAuthKey();//更新登录token
        $this->_user->expirePasswordResetToken();
        $this->_user->updated_at = date('Y-m-d H:i:s');
        return $this->_user->save(false);
    }
}
