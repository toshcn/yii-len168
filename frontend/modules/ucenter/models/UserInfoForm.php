<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license
 */


namespace frontend\modules\ucenter\models;

use Yii;
use yii\base\Model;
use common\models\UserInfo;

/**
 * 会员资料修改表单
 */
class UserInfoForm extends Model
{
    public $nickname;
    public $realname;
    public $mobile;
    public $sex;
    public $birthday;
    public $idcode;
    public $motto;

    public function attributeLabels()
    {
        return [
            'realname' => Yii::t('commom/label', 'Realy Name'),
            'nickname' => Yii::t('commom/label', 'Nick Name'),
            'mobile' => Yii::t('commom/label', 'Mobile'),
            'sex' => Yii::t('commom/label', 'Sexuality'),
            'birthday' => Yii::t('commom/label', 'Birthday'),
            'idcode' => Yii::t('commom/label', 'Identity Code'),
            'motto' => Yii::t('commom/label', 'Motto')
        ];
    }
    public function rules()
    {
        return [
            [['nickname', 'realname', 'motto'], 'trim'],
            [['nickname', 'realname'], 'required'],
            ['realname', 'string', 'max' => 12],
            ['sex', 'in', 'range' => [0, 1, -1]],
            ['mobile', 'match', 'pattern' => '/^1[3-8][0-9]{9}$/'],
            ['birthday', 'date', 'format' => 'yyyy-mm-dd'],
            ['idcode', 'string', 'min' => 20, 'max' => 20],
            ['motto', 'string', 'max' => 32],
            ['nickname', 'unique', 'skipOnError' => true, 'targetClass' => '\common\models\User', 'targetAttribute' => 'nickname', 'filter' => ['not in', 'uid', [Yii::$app->getUser()->getId()]], 'message' => Yii::t('common', 'This nickname has already been taken.')],
            ['mobile', 'safe']
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $user = Yii::$app->user->getIdentity();
            $userInfo = UserInfo::findOne(['user_id' => $user->getId()]);
            $userInfo->realname = $this->realname;
            $userInfo->birthday = $this->birthday;
            $userInfo->mobile = $this->mobile;
            $userInfo->idcode = $this->idcode;
            $userInfo->updated_at = date('Y-m-d H:i:s');

            $user->sex = $this->sex;
            $user->nickname = $this->nickname;
            $user->motto = $this->motto;

            $userInfo->save(false);
            $user->save(false);
            return true;
        }
        return false;
    }
}
