<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_login}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $counts
 * @property integer $failes
 * @property integer $interval
 * @property integer $ipv4
 * @property integer $last_ipv4
 * @property string $failed_at
 * @property string $login_at
 *
 * @property User $user
 */
class UserLogin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_login}}';
    }

    /**
     * 场景应用
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['signup'] = ['user_id', 'login_ipv4', 'login_at'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'counts', 'failes', 'interval', 'ipv4', 'last_ipv4'], 'integer'],
            [['login_at'], 'required'],
            [['failed_at', 'login_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'uid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common\user', 'ID'),
            'user_id' => Yii::t('common\user', 'User ID'),
            'counts' => Yii::t('common\user', 'Login Counts'),
            'failes' => Yii::t('common\user', 'Login Failes'),
            'interval' => Yii::t('common\user', 'Login Interval'),
            'ipv4' => Yii::t('common\user', 'Login Ipv4'),
            'last_ipv4' => Yii::t('common\user', 'Login Last Ipv4'),
            'failed_at' => Yii::t('common\user', 'Login Failed At'),
            'login_at' => Yii::t('common\user', 'Login At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['uid' => 'user_id']);
    }
}
