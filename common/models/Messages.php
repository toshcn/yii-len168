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
 * This is the model class for table "{{%messages}}".
 *
 * @property integer $id
 * @property integer $sendfrom
 * @property integer $sendto
 * @property string $content
 * @property integer $isread
 * @property integer $isforever
 * @property string $send_at
 *
 * @property User $sendfrom0
 * @property User $sendto0
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
            [['sendfrom', 'sendto', 'isread', 'isforever'], 'integer'],
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
            'id' => Yii::t('common/label', 'ID'),
            'sendfrom' => Yii::t('common/label', 'Sendfrom'),
            'sendto' => Yii::t('common/label', 'Sendto'),
            'content' => Yii::t('common/label', 'Content'),
            'isread' => Yii::t('common/label', 'Isread'),
            'isforever' => Yii::t('common/label', 'Isforever'),
            'send_at' => Yii::t('common/label', 'Send At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSendfrom()
    {
        return $this->hasOne(User::className(), ['uid' => 'sendfrom']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSendto()
    {
        return $this->hasOne(User::className(), ['uid' => 'sendto']);
    }
}
