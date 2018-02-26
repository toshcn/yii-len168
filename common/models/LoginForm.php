<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */

namespace common\models;

use Yii;
use yii\base\Model;
use common\events\LoginEvent;

/**
 * Login form
 */
class LoginForm extends Model
{
    const EVENT_LOGIN_SUCCESS = 'loginSuccess';
    const EVENT_LOGIN_FAILS = 'loginFalse';

    public $username;   //会员帐号or邮箱or昵称
    public $password;
    public $os;//客户操作系统，版本
    public $browser;//客户浏览器, 版本
    public $rememberMe = 1; //记住登录名
    public $interval = 0;//登录间隙分钟

    public $tipes = '';

    private $_user = false;
    private $_login;

    /**
     * 属性标签
     */
    public function attributeLabels()
    {
        return [
            'username'   => Yii::t('common/label', 'User Name'),
            'password'   => Yii::t('common/label', 'Login Password'),
            'os'         => Yii::t('common/label', 'Login OS'),
            'browser'    => Yii::t('common/label', 'Login Browser'),
            'rememberMe' => Yii::t('common/label', 'Remember Me'),
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['os', 'browser', 'username'], 'trim'],
            // username and password are both required
            [['username', 'password'], 'required'],
            [['os', 'browser'], 'default', 'value' => ''],
            [['os', 'browser'], 'string'],
            // username is validated by validateUsername()
            ['username', 'validateUsername'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
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
            $this->_user = User::findByUsername($this->$attribute);

            if (!$this->_user) {
                $this->addError('username', Yii::t('common/sentence', 'Account does not exist.'));
            } else {
                if ($this->_user->status == User::STATUS_LOCK) {
                    $this->addError('username', Yii::t('common/sentence', 'Account has been locked.'));
                }

                $this->_login = UserLogin::findOne(['user_id' => $this->_user->getId()]);
                $this->validateInterval();
            }
        }
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors() && $this->interval <= 0) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('username', Yii::t('common/sentence', 'Incorrect username or password.'));
            }
        }
    }
    /**
     * Validates the login interval.
     * This method serves as the inline validation for interval.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateInterval()
    {
        $login = $this->_login;
        //如果登录错误时间日期相隔一天，跳过登录时间限制
        if ((time() - strtotime(substr($login->failed_at, 0, 10))) > 24 * 3600) {
            return true;
        }
        $time = $login->interval * 60 - (time() - strtotime($login->failed_at));
        if ($time > 0) {
            $this->interval = $time;
            $this->tipes = Yii::t('common/sentence', 'Login failed multiple times, please try again later!');
            return false;
        }
        return true;
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        $validate = $this->validate();
        if ($this->_user) {
            $uid = $this->_user->uid;
            $this->on(self::EVENT_LOGIN_SUCCESS, [$this, 'loginSuccess'], $uid);
            $this->on(self::EVENT_LOGIN_FAILS, [$this, 'loginFalse'], $uid);

            if ($validate && $this->interval <= 0) {
                $session = Yii::$app->getSession();
                $session->set('loginOS', \yii\helpers\Html::encode($this->os));
                $session->set('loginBrowser', explode(',', \yii\helpers\Html::encode($this->browser)));

                if ($this->rememberMe) {
                    $cookie = Yii::$app->getResponse()->getCookies();
                    $cookie->add(new \yii\web\Cookie([
                        'name' => 'LOGINNAME_REMEMBER',
                        'value' => $this->username,
                        'expire' => time() + 3600*24*30,
                    ]));
                }

                if (Yii::$app->getUser()->login($this->getUser())) {
                    $this->trigger(self::EVENT_LOGIN_SUCCESS, new LoginEvent());
                    return true;
                }
            }
            $this->trigger(self::EVENT_LOGIN_FAILS, new LoginEvent());
            $this->validateInterval();
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return object User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
    /**
     * 登录成功事件处理器，记录本次登录详情
     * @param  [type] $event [description]
     * @return [type]        [description]
     */
    public function loginSuccess($event)
    {
        $model = UserLogin::findOne(['user_id' => $event->data]);
        $model->counts += 1;
        $model->failes = 0;
        $model->interval = 0;
        $model->ipv4 = bindec(decbin(ip2long(Yii::$app->getRequest()->getUserIP())));
        $model->last_ipv4 = $model->ipv4;
        $model->login_at = date('Y-m-d H:i:s');
        $model->save(false);
    }

    /**
     * 登录失败事件处理器，记录本次登录详情
     * @param  [type] $event [description]
     * @return [type]        [description]
     */
    public function loginFalse($event)
    {
        $model = $this->_login;
        /** @var integer second秒 */
        $time = $model->interval * 60 - (time() - strtotime($model->failed_at));
        if ($time <= 0) {
            $model->counts += 1;
            $model->failes += 1;
            if ($model->failes >= Yii::$app->params['login.maxFailes']) {
                $model->interval = $model->failes - Yii::$app->params['login.maxFailes'];
                $model->failed_at = date('Y-m-d H:i:s');
            }
            $model->last_ipv4 = $model->ipv4;
            $model->ipv4 = bindec(decbin(ip2long(Yii::$app->getRequest()->getUserIP())));
            $model->save(false);
        }
        $this->_login = $model;
    }
}
