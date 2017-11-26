<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_info}}".
 *
 * @property integer $id
 * @property integer $user
 * @property string $realname
 * @property string $birthday
 * @property integer $country
 * @property integer $province
 * @property integer $city
 * @property string $mobile
 * @property string $idcode
 * @property string $updated_at
 *
 * @property User $u
 */
class UserInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user'], 'required'],
            [['user', 'country', 'province', 'city', 'idcode'], 'integer'],
            [['birthday', 'updated_at'], 'safe'],
            [['realname'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('common/userInfo', 'ID'),
            'user'        => Yii::t('common/userInfo', 'User ID'),
            'realname'   => Yii::t('common/userInfo', 'Realname'),
            'birthday'   => Yii::t('common/userInfo', 'Birthday'),
            'country'    => Yii::t('common/userInfo', 'Country'),
            'province'   => Yii::t('common/userInfo', 'Province'),
            'city'       => Yii::t('common/userInfo', 'City'),
            'idcode'     => Yii::t('common/userInfo', 'Idcode'),
            'updated_at' => Yii::t('common/userInfo', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['uid' => 'user']);
    }
}
