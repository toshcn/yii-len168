<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%links}}".
 *
 * @property integer $linkid
 * @property string $link_title
 * @property string $link_type
 * @property string $link_url
 * @property string $link_icon
 * @property integer $link_sort
 */
class Links extends \yii\db\ActiveRecord
{
    const LINK_TYPE_LOCAL = 'local';
    const LINK_TYPE_FRIENDLY = 'friendly';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%links}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link_title', 'link_type'], 'required'],
            [['link_sort'], 'integer'],
            [['link_title'], 'string', 'max' => 64],
            [['link_type'], 'string', 'max' => 8],
            [['link_url', 'link_icon'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'linkid' => 'Linkid',
            'link_title' => '链接名称',
            'link_type' => '链接类型local, friendly',
            'link_url' => 'URL地址',
            'link_icon' => '链接图标',
            'link_sort' => '排序',
        ];
    }

    /**
     * 获取链接
     * @param  integer $linkid 主键
     * @return array
     */
    public function getLink($linkid = null)
    {
        if (ctype_digit($linkid)) {
            return $this->find()->where(['linkid' => $linkid])->asArray()->one();
        } else {
            return $this->find()->asArray()->all();
        }

    }
    /**
     * 获取最近的链接
     * @param  integer $limit数量
     * @return array
     */
    public function getLinkRecent($column = '*', $limit = 10)
    {
        return $this->find()->select($column)->limit($limit)->asArray()->all();
    }

}
