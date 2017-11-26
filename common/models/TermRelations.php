<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */

namespace common\models;

use Yii;
use common\models\Terms;

/**
 * This is the model class for table "{{%term_relations}}".
 *
 * @property integer $objectid
 * @property integer $term_cateid
 * @property integer $sort
 */
class TermRelations extends \yii\db\ActiveRecord
{
    const OBJECT_TYPE_POST = 'post';
    const OBJECT_TYPE_LINK = 'link';
    const OBJECT_TYPE_MENU = 'menu';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%term_relations}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['objectid', 'term'], 'required'],
            ['sort', 'default', 'value' => 0],
            [['objectid', 'term', 'sort'], 'integer'],
            ['type', 'in', 'range' => [self::OBJECT_TYPE_POST, self::OBJECT_TYPE_LINK, self::OBJECT_TYPE_MENU]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'objectid' => Yii::t('common/terms', 'Objectid'),
            'term' => Yii::t('common/terms', 'Termid'),
            'sort' => Yii::t('common/terms', 'Sort'),
            'type' => Yii::t('common/terms', 'Object Type'),
        ];
    }

    public function getTerm()
    {
        return $this->hasOne(Terms::className, ['termid' => 'term']);
    }

    public function getMenu()
    {
        $this->hasOne(Menus::className, ['menuid' => 'objectid']);
    }

    public function getPost()
    {
        $this->hasOne(Posts::className, ['postid' => 'objectid']);
    }

    public function getPostViews()
    {
        $this->hasMany(PostViews::className, ['postid' => 'objectid']);
    }

    /**
     * 判断关系是否存在
     * @param  integer $object   关系id, 文章id, 键接id, 菜单id
     * @param  integer $term     名称termid
     * @return boolean         true存在，false不存在
     */
    public function getExistRelation($object, $term)
    {
        if ($object && $term) {
            return (boolean) $this->findOne(['objectid' => intval($object), 'term' => intval($term)]);
        }

        return false;
    }
}
