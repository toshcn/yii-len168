<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */



namespace common\models;

use Yii;
use yii\base\Model;
use common\models\Invites;

/**
 * This is the model class for table "{{%invitate}}" form.
 *
 * @property integer $user
 * @property string $email
 * @property string $code
 * @property integer $isuse
 * @property string $send_at
 *
 * @property Users $user
 */
class InviteForm extends Model
{
    const YES = 1;
    const NO = 0;

    public $id;
    public $user;
    public $email;
    public $code;
    public $isuse = self::NO;
    /** @var integer 邮件是否发送成功 1成功，0失败 */
    public $issend = self::NO;
    public $sendAt;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            [['user', 'email', 'code'], 'required'],
            [['user'], 'integer'],
            [['email'], 'string', 'max' => 64],
            [['email'], 'email'],
            [['code'], 'string', 'max' => 32],
            [['isuse'], 'in', 'range' => [self::NO, self::YES]],
            ['email', 'unique', 'skipOnError' => true, 'targetClass' => '\common\models\Invites', 'targetAttribute' => 'email', 'message' => '此邮箱已邀请过了！'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user' => Yii::t('common/user', 'User'),
            'email' => Yii::t('common/user', 'Email'),
            'code' => Yii::t('common/user', 'Code'),
            'isuse' => Yii::t('common/user', 'Isuse')
        ];
    }
    /**
     * 生成邀请码
     * @return string 邀请码32位 md5
     */
    public function generateInviteCode()
    {
        return $this->code = md5(Yii::$app->getSecurity()->generateRandomString(20) . (string) (Yii::$app->user->getId() + time()));
    }

    /**
     * 生成邀请码，并保存到数据库邀请码表
     * @return boolean 是否保存成功
     */
    public function invite()
    {
        $this->generateInviteCode();
        if ($this->validate()) {
            $model          = new Invites();
            $model->user_id = $this->user;
            $model->email   = $this->email;
            $model->code    = $this->code;
            $model->isuse   = self::NO;
            $model->send_at = date('Y-m-d H:i:s');
            if ($model->save(false)) {
                $this->id       = $model->id;
                $this->issend   = $model->sendEmail(Yii::$app->user->identity->getNickname()) ? 1 : 0;
                $this->sendAt   = date('Y-m-d');
                return true;
            }
        }
        return false;
    }
}
