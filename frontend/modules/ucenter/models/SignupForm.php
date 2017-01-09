<?php
namespace frontend\modules\ucenter\models;

use Yii;
use yii\helpers\Html;
use yii\base\Model;
use common\models\User;
use common\models\UserLogin;
use common\models\UserInfo;
use common\models\Invites;
use common\models\Followers;

/**
 * Signup form
 */
class SignupForm extends Model
{
    const YES = 1; //状态真
    const NO  = 0; //状态

    public $username; //会员名
    public $nickname; //注册昵称
    public $email; //注册邮箱
    public $inviteCode; //注册邀请码
    public $password; //登录密码
    public $repassword; //确认登录密码
    public $ask; //登录认证问题（可选）
    public $cdkey; //登录认证识别码（可选）
    public $sex = -1; //性别 1男，0女，-1保密
    public $captcha; //验证码
    public $agreement; //同意注册协议
    public $os;//客户操作系统，版本
    public $browser;//客户浏览器, 版本
    private $_inviter = 0;//邀请者会员ID

    /**
     * 属性标签
     */
    public function attributeLabels()
    {
        return [
            'username'   => Yii::t('common/label', 'User Name'),
            'nickname'   => Yii::t('common/label', 'Nick Name'),
            'email'      => Yii::t('common/label', 'Email Address'),
            'inviteCode' => Yii::t('common/label', 'Invitation Code'),
            'password'   => Yii::t('common/label', 'Login Password'),
            'repassword' => Yii::t('common/label', 'Confirm Password'),
            'sex'        => Yii::t('common/label', 'Sexuality'),
            'captcha'    => Yii::t('common/label', 'Captcha'),
            'os'         => Yii::t('common/label', 'Login OS'),
            'browser'    => Yii::t('common/label', 'Login Browser'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'nickname', 'inviteCode', 'password', 'os', 'browser'], 'trim'],
            [['username', 'nickname', 'sex', 'inviteCode', 'password', 'repassword', 'captcha'], 'required'],
            ['captcha', 'captcha', 'captchaAction' => 'ucenter/account/captcha'],

            [['username', 'nickname'], 'string', 'min' => 2, 'max' => 15],

            ['sex', 'in', 'range' => [0, 1, -1]],

            ['inviteCode', 'string', 'min' => 32, 'max' => 32],
            [['password','repassword'], 'string', 'min' => 8, 'max' => 20],
            ['repassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('common/sentence', 'Password is not the same as the input.')],

            ['password', 'match', 'pattern' => '/^[A-Za-z]\w+$/', 'message' => Yii::t('common/sentence', 'English begin and English numeral underline combination.')],

            ['agreement', 'compare', 'compareValue' => self::YES, 'message' => Yii::t('common/sentence', 'Must agree to the membership agreement.')],

            ['username', 'unique', 'skipOnError' => true, 'targetClass' => '\common\models\User', 'targetAttribute' => 'username', 'message' => Yii::t('common/sentence', 'This username has already been taken.')],
            ['nickname', 'unique', 'skipOnError' => true, 'targetClass' => '\common\models\User', 'targetAttribute' => 'nickname', 'message' => Yii::t('common/sentence', 'This nickname has already been taken.')],

            ['inviteCode', 'exist', 'skipOnError' => true, 'targetClass' => '\common\models\Invites', 'targetAttribute' => 'code', 'filter' => ['isuse' => self::NO], 'message' => Yii::t('common/sentence', 'This invitation code has already been taken.')],
            [['os', 'browser'], 'string'],
        ];
    }

    /**
     * 设置email为邀请的邮箱
     * @return boolean
     */
    public function validateEmail()
    {
        if (!$this->hasErrors()) {
            $model = new Invites();
            $row = $model->find()->select(['user_id', 'email'])->where(['code' => $this->inviteCode, 'isuse' => self::NO])->asArray()->one();
            if ($row['email']) {
                $this->email = $row['email'];
                $this->_inviter = $row['user_id'];
                return true;
            } else {
                $this->addError('inviteCode', Yii::t('common/sentence', 'This invitation code has already been taken.'));
            }
        }
        return false;
    }

    /**
     * 会员注册
     *
     * 必需同时写入会员登录表，会员资料表，会员财富表才会注册成功
     * member register
     * @return obejct or null
     */
    public function signup()
    {
        if ($this->validate() && $this->validateEmail()) {
            $session = Yii::$app->getSession();
            $session->set('loginOS', Html::encode($this->os));
            $session->set('loginBrowser', explode(',', Html::encode($this->browser)));

            $transaction = Yii::$app->db->beginTransaction();//启用事务

            try {
                //创建会员模型对象
                $user           = new User(['scenario' => 'signup']);
                $user->username = $this->username;
                $user->nickname = $this->nickname;
                $user->group    = Yii::$app->params['user.defaultRoleGroupId'];
                $user->email    = $this->email;
                $user->sex      = $this->sex;
                $user->head     = Yii::$app->params['user.defaultAvatar'];
                $user->setPassword($this->password);
                $user->generateAuthKey();
                $user->created_at = date('Y-m-d H:i:s');
                $user->save(false);

                //添加到会员登录表
                $login           = new UserLogin();
                $login->user_id  = $user->uid;
                $login->ipv4  = bindec(decbin(ip2long(Yii::$app->getRequest()->getUserIP())));
                $login->login_at = $user->created_at;
                //添加到会员个人资料表
                $userInfo      = new UserInfo();
                $userInfo->user_id = $user->uid;
                $userInfo->realname = $user->username;
                $userInfo->birthday = date('Y-m-d');
                $userInfo->created_at = $user->created_at;
                $userInfo->updated_at = $user->created_at;

                //更新邀请码表
                $invitate = new Invites();
                $invitate = $invitate->findByCode($this->inviteCode);
                $invitate->isuse = Invites::YES;

                //加关注邀请者
                $follower = new Followers();
                $follower->user_id = $this->_inviter;
                $follower->follower_id = $user->uid;
                $follower->status = Followers::YES;
                $follower->created_at = $user->created_at;
                $follower->updated_at = $user->created_at;
                //必需同时写入会员登录表，会员资料表，会员财富表, 关注表才会注册成功
                if ($invitate->update(false) && $login->save(false) && $userInfo->save(false) && $follower->save(false)) {
                    //头像文件夹
                    $path = Yii::getAlias(Yii::$app->params['image.basePath']) . Yii::$app->params['image.relativePath'] . $user->id . '/' . Yii::$app->params['avatar.dirName'];
                    if (!is_dir($path)) {
                        @mkdir($path, 0764, true);
                    }

                    $transaction->commit();//提交事务
                    User::increaseFollower($this->_inviter);
                    return $user;
                } else {
                    $transaction->rollback();//事务回滚
                }
            } catch (Exception $e) {
                $transaction->rollback();//事务回滚
                throw $e;
            }
        }

        return null;
    }
}
