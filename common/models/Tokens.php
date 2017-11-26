<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%token}}".
 *
 * @property integer $id
 * @property integer $user
 * @property string $token
 * @property integer $token_expire
 *
 * @property Users $user
 */
class Tokens extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tokens}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user', 'token'], 'required'],
            [['user', 'token_expire'], 'integer'],
            [['token'], 'string', 'max' => 32],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user' => 'uid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'User',
            'token' => 'Token',
            'token_expire' => 'Token Expire',
            'created_at' => 'Token Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['uid' => 'user']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findByToken($token)
    {
        if ($token && ($token = self::findOne(['token' => $token]))) {
            if (time() < $token->token_expire) {
                return $token;
            }
        }

        return false;

    }
}
