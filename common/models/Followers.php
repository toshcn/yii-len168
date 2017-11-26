<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace common\models;

use Yii;

/**
 * 关注
 *
 * @author toshcn <toshcn@qq.com>
 * @since 0.1.0
 */
class Followers extends \yii\db\ActiveRecord
{
    const YES = 1;
    const NO = 0;
    /**
     * @会员间关注表
     */
    public static function tableName()
    {
        return '{{%followers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'follower_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id', 'follower_id'], 'unique', 'targetAttribute' => ['user_id', 'follower_id'], 'message' => 'The combination of User and Follower has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'follower_id' => 'Follower',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollower()
    {
        return $this->hasOne(User::className(), ['uid' => 'follower_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriend()
    {
        return $this->hasOne(User::className(), ['uid' => 'user_id']);
    }

    /**
     * 查找关注状态
     * @param  integer|array  $condition 查询条件 如果是integer查询主键，如果是数组，需要是[user, follower]形式
     * @return boolean            关注状态
     */
    public static function getFollowerStatus($condition)
    {
        if (is_array($condition)) {
            if ($condition[0] && $condition[1]) {
                $row = self::find()->select('status')
                        ->where(['user_id' => $condition[0], 'follower_id' => $condition[1]])
                        ->asArray()
                        ->one();

                return isset($row['status']) ? (boolean) $row['status'] : false;
            }
        } elseif ($condition) {
            $row = self::findOne($condition)->select('status')->asArray();
            return isset($row['status']) ? (boolean) $row['status'] : false;
        }
        return false;
    }
}
