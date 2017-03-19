<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\Login;
use common\models\Posts;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property integer $group
 * @property string $username
 * @property string $nickname
 * @property integer $mobile
 * @property string $email
 * @property integer $sex
 * @property string $auth_key
 * @property string $password
 * @property string $reset_token
 * @property integer $reset_token_expire
 * @property string $motto
 * @property integer $hp
 * @property integer $golds
 * @property integer $crystal
 * @property integer $posts
 * @property integer $comments
 * @property integer $friends
 * @property integer $followers
 * @property integer $isauth
 * @property integer $status
 * @property integer $status
 * @property string $os
 * @property string $brower
 * @property string $created_at
 * @property string $updated_at
 *
 * @property UserInfo[] $userInfos
 * @property UserLogin[] $userLogins
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETE = -1;
    const STATUS_LOCK = 0;//登录锁定
    const STATUS_ACTIVE = 1;//正常
    const STATUS_BAN_COMMENT = 10;//禁止评论
    const STATUS_BAN_POST = 20;//禁止发表

    const GROUP_ADMIN = 10;
    const GROUP_AUTHOR = 20;

    const SEX_SECRECY = -1;//性别保密
    const SEX_MAN = 1;//性别男
    const SEX_WOMAN = 0;//性别女
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
        ];
    }

    /**
     * 场景应用
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['login'] = ['uid', 'pwd', 'os', 'browser'];
        $scenarios['signup'] = ['username', 'nickname', 'group', 'sex', 'email', 'password', 'auth_key', 'head', 'created_at'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group', 'username', 'nickname', 'email', 'auth_key', 'password', 'created_at'], 'required'],
            [['golds', 'crystal', 'posts', 'comments', 'friends', 'followers', 'isauth', 'safe_level'], 'default', 'value' => 0],
            [['group', 'mobile', 'sex', 'hp', 'golds', 'crystal', 'posts', 'comments', 'friends', 'followers', 'isauth', 'status', 'safe_level'], 'integer'],
            [['username', 'nickname'], 'string', 'max' => 15],
            [['email', 'reset_token'], 'string', 'max' => 64],
            [['auth_key', 'motto'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 128],
            [['head', 'pay_qrcode'], 'string', 'max' => 255],

            [['username'], 'unique', 'skipOnError' => true, 'targetClass' => '\common\models\User', 'targetAttribute' => 'username', 'message' => Yii::t('common', 'This username has already been taken.')],

            [['nickname'], 'unique', 'skipOnError' => true, 'targetClass' => '\common\models\User', 'targetAttribute' => 'nickname', 'message' => Yii::t('common', 'This nickname has already been taken.')],

            [['email'], 'unique', 'skipOnError' => true, 'targetClass' => '\common\models\User', 'targetAttribute' => 'email', 'message' => Yii::t('common', 'This email has already been taken.')],

            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid'                 => Yii::t('common', 'User ID'),
            'group'              => Yii::t('common', 'User Group'),
            'username'           => Yii::t('common', 'User Name'),
            'nickname'           => Yii::t('common', 'Nick Name'),
            'mobile'             => Yii::t('common', 'Mobile'),
            'email'              => Yii::t('common', 'Email'),
            'sex'                => Yii::t('common', 'Sex'),
            'auth_key'           => Yii::t('common', 'Auth Key'),
            'password'           => Yii::t('common', 'Password'),
            'reset_token'        => Yii::t('common', 'Reset Token'),
            'reset_token_expire' => Yii::t('common', 'Reset Token Expire'),
            'motto'              => Yii::t('common', 'Motto'),
            'hp'                 => Yii::t('common', 'Hp'),
            'golds'              => Yii::t('common', 'Golds'),
            'crystal'            => Yii::t('common', 'Crystal'),
            'posts'              => Yii::t('common', 'Posts'),
            'comments'           => Yii::t('common', 'Comments'),
            'friends'            => Yii::t('common', 'Friends'),
            'followers'          => Yii::t('common', 'Followers'),
            'isauth'             => Yii::t('common', 'Isauth'),
            'status'             => Yii::t('common', 'Status'),
            'safe_level'         => Yii::t('common', 'Safe Level'),
            'created_at'         => Yii::t('common', 'Created At'),
            'updated_at'         => Yii::t('common', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::find()
                ->where(['uid' => $id])
                ->andWhere(['not in', 'status', [self::STATUS_DELETE]])
                ->one();
    }

    /**
     * 通过会员ID或邮箱或昵称或手机号查找会员
     *
     * @param string|integer $username 会员ID或邮箱或昵称
     * @return static|null
     */
    public static function findByUsername($username)
    {
        if (is_numeric($username)) {
            return self::findIdentity(intval($username));
        } elseif (filter_var($username, FILTER_VALIDATE_EMAIL)) {
             return static::find()
                    ->where(['email' => $username])
                    ->andWhere(['not in', 'status', [self::STATUS_DELETE]])
                    ->one();
        } else {
            return static::find()
                    ->where(['username' => $username])
                    ->andWhere(['not in', 'status', [self::STATUS_DELETE]])
                    ->one();
        }
    }

    /**
     * Finds user by password reset token
     *
     * @param integer  $user user id
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByResetToken($user, $token)
    {
        $model =  static::find()
            ->where(['uid' => $user, 'reset_token' => $token])
            ->one();

        if ($model && $model->reset_token === $token && static::validateResetTokenExpire($model->reset_token_expire)) {
            return $model;
        }

        return null;
    }

    /**
     * 重置密码token有效期验证
     *
     * @param string $reset_token_expire password reset token expire time
     * @return bool
     */
    public static function validateResetTokenExpire($reset_token_expire)
    {
        return strtotime($reset_token_expire) >= time();
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === (string) $authKey;
    }

    /**
     * 生成密码的hash值
     *
     * @param string $password 密码
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->getSecurity()->generatePasswordHash(md5($password));
    }

    /**
     * 生成重置密码随机令牌
     * @return boolean       是否成功生成令牌
     */
    public function generatePasswordResetToken()
    {
        $this->reset_token = sha1(Yii::$app->getSecurity()->generateRandomString(32) . time() . $this->uid);
        $this->reset_token_expire = date('Y-m-d H:i:s', time() + Yii::$app->params['resetTokenExpire'] * 60);
        if ($this->save(false)) {
            return true;
        }

        return false;
    }


    /**
     * 设置重置令牌过期
     * expire password reset token
     */
    public function expirePasswordResetToken()
    {
        $this->reset_token_expire = date('Y-m-d H:i:s');
    }

    /**
     * 验证密码
     *
     * @param string $password
     * @return boolean 验证密码成功或失败
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword(md5($password), $this->password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * 通过令牌查找会员 接口IdentityInterface
     *
     * @param  string $token 会员登录成功时生成的令牌
     * @return object        会员模型
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()
                    ->where(['auth_key' => $token])
                    ->andWhere(['not in', 'status', [self::STATUS_DELETE, self::STATUS_LOCK]])
                    ->one();
    }

    /**
     * 获得会员uid字段
     *
     * @return integer 返回会员uid属性
     */
    public function getId()
    {
        return $this->uid;
    }
    /**
     * 获得会员nickname字段
     *
     * @return integer 返回会员nickname属性
     */
    public function getNickname()
    {
        return $this->nickname;
    }
     /**
     * 获得会员token字段
     *
     * @return string 返回会员token属性
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserInfos()
    {
        return $this->hasMany(UserInfo::className(), ['user_id' => 'uid']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserInfo()
    {
        return $this->hasOne(UserInfo::className(), ['user_id' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLogins()
    {
        return $this->hasMany(UserLogin::className(), ['user_id' => 'uid']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLogin()
    {
        return $this->hasOne(UserLogin::className(), ['user_id' => 'uid']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['user_id' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostComment($postid)
    {
        if ($postid) {
            return $this->hasOne(Comments::className(), ['user_id' => 'uid'])->where('postid=:postid', [':postid' => intval($postid)]);
        }
        return null;
    }

    /**
     * 获得会员发送的信息
     * @return \yii\db\ActiveQuery
     */
    public function getMessagesSendFrom()
    {
        return $this->hasMany(Messages::className(), ['sendfrom' => 'uid']);
    }

    /**
     * 获得会员收到的信息
     * @return \yii\db\ActiveQuery
     */
    public function getMessagesSendTo()
    {
        return $this->hasMany(Messages::className(), ['sendto' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvites()
    {
        return $this->hasMany(Invites::className(), ['user_id' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Log::className(), ['user_id' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogin()
    {
        return $this->hasOne(UserLogin::className(), ['user_id' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailboxes()
    {
        return $this->hasMany(Mailbox::className(), ['sendfrom' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotices()
    {
        return $this->hasMany(Notice::className(), ['user_id' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Posts::className(), ['user_id' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Posts::className(), ['user_id' => 'uid']);
    }

    /**
     * 关注
     * @return \yii\db\ActiveQuery
     */
    public function getFriends()
    {
        return $this->hasOne(Followers::className(), ['follower_id' => 'uid']);
    }

    /**
     * 粉丝
     * @return \yii\db\ActiveQuery
     */
    public function getFollowers()
    {
        return $this->hasOne(Followers::className(), ['user_id' => 'uid']);
    }


    public function getSearchPosts()
    {
        return $this->hasMany(Posts::className(), ['user_id' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDrafts()
    {
        return $this->hasMany(Posts::className(), ['user_id' => 'uid'])->where(['status' => self::STATUS_DRAFT]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Replies::className(), ['reply_to' => 'uid']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastPost()
    {
        return $this->hasOne(Posts::className(), ['user_id' => 'uid']);
    }

    /**
     * 获取会员信息 for 弹出框小部件
     * @param  integer $uid    会员ID
     * @return array|null      会员信息
     */
    public function getAuthorWidget($uid)
    {
        $author = $this->getUserWidget($uid);
        $post = new Posts();
        $author['isfollower'] = false;
        if (! Yii::$app->user->isGuest) {
            $author['isfollower'] = Followers::getFollowerStatus([$uid, Yii::$app->user->getId()]);
        }

        $author['post'] = $post->find()
            ->select(['postid', 'title'])
            ->where(['user_id' => $uid, 'status' => Posts::STATUS_ONLINE])
            ->orderBy(['postid' => SORT_DESC])
            ->asArray()
            ->one();

        return $author;
    }

    /**
     * 获取会员widget
     * @param  integer $id 会员id
     * @return array|null
     */
    public static function getUserWidget($id)
    {

        return static::find()->where(['uid' => $id])
            ->andWhere(['not in', 'status', [self::STATUS_DELETE]])
            ->select(['uid', 'username', 'nickname', 'email', 'sex', 'motto', 'head', 'hp', 'golds', 'crystal', 'posts', 'comments', 'friends', 'followers', 'isauth'])
            ->asArray()
            ->one();
    }

    /**
     * 获取会员个人资料
     * @param  integer $id 会员id
     * @return array|null
     */
    public static function getUserDetail($id)
    {

        return static::find()->where(['uid' => $id])
            ->andWhere(['not in', 'status', [self::STATUS_DELETE]])
            ->select([
                'uid', 'username', 'nickname', 'email', 'sex', 'motto', 'head', 'pay_qrcode', 'hp', 'golds', 'crystal', 'posts', 'comments', 'friends', 'followers', 'isauth', 'safe_level', '{{%user}}.created_at'
            ])
            ->joinWith([
                'userLogin' => function ($query) {
                    return $query->select(['login_at', 'ipv4', 'last_ipv4']);
                }
            ])
            ->asArray()
            ->one();
    }
    /**
     * 获取会员个人详细资料
     * @param  integer $id 会员id
     * @return \yii\db\ActiveQuery
     */
    public static function getUserDetailWithInfo($id)
    {

        return static::find()->where(['uid' => $id])
            ->andWhere(['not in', 'status', [self::STATUS_DELETE]])
            ->select([
                'uid', 'username', 'nickname', 'email', 'sex', 'motto', 'head', 'hp', 'golds', 'crystal', 'posts', 'comments', 'friends', 'followers', 'isauth', 'safe_level', '{{%user}}.created_at'])
            ->joinWith(['userLogin' => function ($query) {
                return $query->select(['login_at', 'ipv4', 'last_ipv4']);
            }])
            ->joinWith('userInfo')
            ->asArray()
            ->one();
    }
    /**
     * 返回会员性别对应名称
     * @param  integer $sex 性别
     * @return string      性别对应名称
     */
    public static function getSexName($sex)
    {
        $name = '未知';
        switch ($sex) {
            case self::SEX_SECRECY:
                $name = '保密';
                break;
            case self::SEX_MAN:
                $name = '男';
                break;
            case self::SEX_WOMAN:
                $name = '女';
                break;
        }
        return $name;
    }

    public static function getGroupName($gid)
    {
        switch ($gid) {
            case self::GROUP_AUTHOR:
                $text = '作者';
                break;
            case self::GROUP_ADMIN:
                $text = '管理员';
                break;
            default:
                $text = '';
                break;
        }
        return $text;
    }


    /**
     * 增加文章发表数量
     * @param  integer $user 会员id
     * @return boolean
     */
    public static function increasePost($user)
    {
        if ($user) {
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET posts = `posts` + 1 WHERE uid =:user', [':user' => intval($user)])->execute();
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
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET posts = `posts` - 1 WHERE uid =:user', [':user' => intval($user)])->execute();
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
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET hp = `hp` + ' . $hp . ', golds = `golds` + '. $gold .', comments = `comments` + ' .intval($hp). ' WHERE uid =:user', [':user' => intval($user)])->execute();
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
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET friends = `friends` + 1 WHERE uid =:user', [':user' => intval($user)])->execute();
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
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET followers = `followers` + 1 WHERE uid =:user', [':user' => intval($user)])->execute();
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
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET friends = `friends` - 1 WHERE uid =:user', [':user' => intval($user)])->execute();
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
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET followers = `followers` - 1 WHERE uid =:user', [':user' => intval($user)])->execute();
        }
        return false;
    }

    /**
     * 角色 map
     * @return array
     */
    public static function getGroupMap()
    {
        return ['' => '请选择', self::GROUP_ADMIN => '管理员', self::GROUP_AUTHOR => '作者'];
    }

    /**
     * 状态 map
     * @return array
     */
    public static function getStatusMap()
    {
        return [
            '' => '请选择',
            self::STATUS_ACTIVE => '正常',
            self::STATUS_LOCK => '登录锁定',
            self::STATUS_BAN_COMMENT => '评论锁定',
            self::STATUS_BAN_POST => '发表锁定',
            self::STATUS_DELETE => '被删除'
        ];
    }

    /**
     * 性别 map
     * @return array
     */
    public static function getSexMap()
    {
        return ['' => '请选择', self::SEX_SECRECY => '保密', self::SEX_MAN => '男', self::SEX_WOMAN => '女'];
    }


    /**
     * 更改状态
     * @param  integer $status 推荐状态0,1
     * @param  array|integer $id    文章id
     * @return boolean
     */
    public static function updateStatus($status, $id)
    {
        $map = [
            self::STATUS_ACTIVE,
            self::STATUS_LOCK,
            self::STATUS_BAN_COMMENT,
            self::STATUS_BAN_POST,
            self::STATUS_DELETE
        ];

        if (!in_array($status, $map)) {
            return false;
        }

        if (!is_array($id)) {
            $id = [$id];
        }
        $temp = [];
        foreach ($id as $key => $value) {
            $temp[] = (int) $value;
        }
        return (boolean) static::updateAll(
            ['status' => $status],
            ['uid' => $temp, 'group' => [self::GROUP_AUTHOR]]
        );
    }
}
