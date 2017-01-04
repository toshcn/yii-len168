<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\models;

use Yii;
use common\models\User;

/**
 * description
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%messages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sendfrom', 'sendto', 'isread'], 'integer'],
            [['send_at'], 'required'],
            [['send_at'], 'safe'],
            [['content'], 'string', 'max' => 255],
            [['sendfrom'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sendfrom' => 'uid']],
            [['sendto'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sendto' => 'uid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sendfrom' => 'Sendfrom',
            'sendto' => 'Sendto',
            'content' => 'Content',
            'isread' => 'Isread',
            'send_at' => 'Send At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSendfrom0()
    {
        return $this->hasOne(Users::className(), ['uid' => 'sendfrom']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSendto0()
    {
        return $this->hasOne(Users::className(), ['uid' => 'sendto']);
    }
}
