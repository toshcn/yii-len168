<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%wealth_records}}".
 *
 * @property integer $id
 * @property integer $user
 * @property integer $value
 * @property integer $type
 * @property string $description
 * @property string $change_at
 *
 * @property Users $user0
 */
class WealthRecords extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wealth_records}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user', 'change_at'], 'required'],
            [['user', 'value', 'type'], 'integer'],
            [['change_at'], 'safe'],
            [['description'], 'string', 'max' => 255],
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
            'user' => '会员ID',
            'value' => '变动的数值',
            'type' => '类型 0积分，1金币，2水晶',
            'description' => '描述',
            'change_at' => '变动的时间',
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
