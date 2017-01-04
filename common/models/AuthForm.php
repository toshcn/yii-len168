<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */


namespace common\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use common\models\Auth;
use common\models\User;
use common\models\UserInfo;
use common\models\UserLogin;

/**
 * 第三方登录注册 form
 */
class AuthForm extends Model
{
    const YES = 1;
    const NO = 0;

    public $username; //会员名
    public $nickname; //昵称
    public $email; //注册邮箱
    public $avatar; //会员头像
    public $password; //登录密码
    public $repassword; //确认登录密码
    public $sex = -1; //性别 1男，0女，-1保密
    public $captcha; //验证码
    public $agreement; //同意注册协议
    public $os; //客户操作系统，版本
    public $browser; //客户浏览器, 版本
    public $source; //第三方平台名称
    public $source_id; //第三方平台帐户

    /**
     * 属性标签
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('common/label', 'User Name'),
            'nickname' => Yii::t('common/label', 'Nick Name'),
            'email' => Yii::t('common/label', 'Email Address'),
            'password' => Yii::t('common/label', 'Login Password'),
            'repassword' => Yii::t('common/label', 'Confirm Password'),
            'sex' => Yii::t('common/label', 'Sex'),
            'captcha' => Yii::t('common/label', 'Captcha'),
            'os' => Yii::t('common/label', 'Login OS'),
            'browser' => Yii::t('common/label', 'Login Browser'),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'nickname', 'password', 'os', 'browser'], 'trim'],
            [['nickname', 'email', 'password', 'repassword', 'captcha'], 'required'],
            ['username', 'string', 'min' => 2, 'max' => 15],
            ['email', 'string', 'max' => 64],
            ['email', 'email'],
            [['password', 'repassword'], 'string', 'min' => 6, 'max' => 20],
            [['password','repassword'], 'string', 'min' => 6, 'max' => 20],
            ['repassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('common/sentence', 'Password is not the same as the input.')],

            ['password', 'match', 'pattern' => '/^[A-Za-z]\w+$/', 'message' => Yii::t('common/sentence', 'English begin and English numeral underline combination.')],

            [['os', 'browser'], 'string', 'max' => 32],
            [['os', 'browser'], 'safe'],
            ['captcha', 'captcha', 'captchaAction' => 'ucenter/auth/captcha'],
            ['agreement', 'compare', 'compareValue' => self::YES, 'message' => Yii::t('common/sentence', 'Must agree to the membership agreement.')],

            ['username', 'unique', 'skipOnError' => true, 'targetClass' => '\common\models\User', 'targetAttribute' => 'username', 'message' => Yii::t('common/sentence', 'This username has already been taken.')],

            ['nickname', 'unique', 'skipOnError' => true, 'targetClass' => '\common\models\User', 'targetAttribute' => 'nickname', 'message' => Yii::t('common/sentence', 'This nickname has already been taken.')],

            ['email', 'unique', 'skipOnError' => true, 'targetClass' => '\common\models\User', 'targetAttribute' => 'email', 'message' => Yii::t('common/sentence', 'This email has already been taken.')],

        ];
    }

    /**
     * 会员注册
     *
     * 启用事务方式同时写入会员登录表，会员资料表
     * member register
     * @return obejct or null
     */
    public function signup()
    {
        if ($this->validate()) {
            $session = Yii::$app->session;
            $session->set('loginOS', $this->os);
            $session->set('loginBrowser', explode(',', $this->browser));
            $transaction = Yii::$app->db->beginTransaction(); //启用事务

            try {
                //创建会员模型对象
                $user = new User(['scenario' => 'signup']);
                $user->username = $this->username;
                $user->nickname = $this->username;
                $user->email = $this->email;
                $user->group = Yii::$app->params['user.defaultRoleGroupId'];
                $user->sex = -1;
                $user->head = Html::encode($this->avatar);
                $user->created_at = date('Y-m-d H:i:s');
                $user->updated_at = $user->created_at;
                $user->setPassword($this->password);
                $user->generateAuthKey();
                $user->save(false);

                //添加到会员登录表
                $login = new UserLogin(['scenario' => 'signup']);
                $login->user_id = $user->uid;
                $login->ipv4 = bindec(decbin(ip2long(Yii::$app->getRequest()->getUserIP())));
                $login->login_at = $user->created_at;
                $login->save(false);

                //添加到会员资料表
                $userInfo = new UserInfo();
                $userInfo->user_id = $user->uid;
                $userInfo->realname = $user->username;
                $userInfo->birthday = date('Y-m-d');
                $userInfo->created_at = $user->created_at;
                $userInfo->updated_at = $user->created_at;
                //绑定第三方帐号
                $auth = new Auth();
                $auth->user_id = $user->uid;
                $auth->source = $this->source;
                $auth->source_id = $this->source_id;
                $auth->nickname = $this->nickname;
                $auth->created_at = $user->created_at;

                if ($login->save(false) && $userInfo->save(false) && $auth->save()) {
                    $transaction->commit(); //提交事务
                    //头像文件夹
                    $headImgPath = '.' . Yii::$app->params['image.relativePath'] . $user->uid . Yii::$app->params['avatar.dirName'];
                    if (!is_dir($headImgPath)) {
                        mkdir($headImgPath, 0764, true);
                    }
                    return $user;
                } else {
                    $transaction->rollback(); //事务回滚
                }
            } catch (Exception $e) {
                $transaction->rollback(); //事务回滚
                throw $e;
            }
        }
        return null;
    }
}
