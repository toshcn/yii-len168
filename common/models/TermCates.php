<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license 
 */

namespace common\models;

use Yii;
use common\models\Menus;

/**
 * This is the model class for table "{{%term_cates}}".
 *
 * @property integer $term_cateid
 * @property integer $termid
 * @property string $catetype
 * @property string $description
 * @property integer $parent
 * @property integer $counts
 */
class TermCates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%term_cates}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['termid'], 'required'],
            [['termid', 'parent', 'counts'], 'integer'],
            [['parent', 'counts'], 'default', 'value' => 0],
            [['catetype'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 255],
            [['description'], 'default', 'value' => ''],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'term_cateid' => Yii::t('common/terms', 'Term Cateid'),
            'termid' => Yii::t('common/terms', 'Termid'),
            'catetype' => Yii::t('common/terms', 'Categroy Type'),
            'description' => Yii::t('common/terms', 'Description'),
            'parent' => Yii::t('common/terms', 'Parent'),
            'counts' => Yii::t('common/terms', 'Counts'),
        ];
    }

    public function findNavMenu($menu)
    {
        if (ctype_digit($menu)) {//如果是整数，查找此菜单ID
            return $this->find()->where(['term_cateid' => intval($menu), 'catetype' => 'nav_menu']);
        } else {
            return $this->find()->where(['catetype' => 'nav_menu']);
        }
    }

    /**
     * term_cates表对应的term表关系 一对一
     * @return object ActiveQuery object
     */
    public function getTerm()
    {
        return $this->hasOne(Terms::className(), ['termid' => 'termid']);
    }

    public function getTermRelations()
    {
        return $this->hasMany(TermRelations::className(), ['term_cateid' => 'term_cateid']);
    }

    /**
     * term_cates表对应的menus表关系 一对多
     * @return object ActiveQuery object
     */
    public function getMenus()
    {
        return $this->hasMany(Menus::className(), ['menuid' => 'objectid'])
            ->via('termRelations');
    }

    /**
     * 获取分类
     * @param  integer $cateid term_cates主键
     * @return object         query对象
     */
    public function getCategory($cateid = null)
    {
        if (ctype_digit($cateid)) {
            return $this->find()->where(['term_cateid' => intval($cateid), 'catetype' => 'category'])->joinWith('term')->asArray()->one();
        } else {
            return $this->find()->where(['catetype' => 'category'])->joinWith('term')->asArray()->all();
        }
    }
}
