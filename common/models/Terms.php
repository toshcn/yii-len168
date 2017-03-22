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
 * This is the model class for table "{{%terms}}".
 *
 * @property integer $termid
 * @property string $title
 * @property string $name
 */
class Terms extends \yii\db\ActiveRecord
{
    const CATEGORY_CATE = 'category';
    const CATEGORY_MENU = 'nav_menu';
    const CATEGORY_TAG  = 'post_tag';
    const CATEGORY_LINK = 'link_category';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%terms}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'required'],
            [['title', 'description'], 'trim'],
            [['title', 'slug'], 'string', 'max' => 128],
            [['parent', 'counts'], 'default', 'value' => 0],
            [['termid', 'parent', 'counts'], 'integer'],
            [['catetype'], 'string', 'max' => 32],
            [['catetype'], 'in', 'range' => [self::CATEGORY_CATE, self::CATEGORY_MENU, self::CATEGORY_TAG, self::CATEGORY_LINK]],
            [['description', 'slug'], 'default', 'value' => ''],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'termid'      => Yii::t('common/term', 'Termid'),
            'title'       => Yii::t('common/term', 'Title'),
            'slug'        => Yii::t('common/term', 'Slug'),
            'catetype'    => Yii::t('common/term', 'Category Type'),
            'description' => Yii::t('common/term', 'Description'),
            'parent'      => Yii::t('common/term', 'Parent'),
            'counts'      => Yii::t('common/term', 'Counts'),
        ];
    }

    public function findNavMenu($menu)
    {
        if ($menu > 0) {//如果是整数，查找此菜单ID
            return $this->find()->where(['termid' => intval($menu), 'catetype' => self::CATEGORY_MENU]);
        } else {
            return $this->find()->where(['catetype' => self::CATEGORY_MENU]);
        }
    }

    /**
     * 分类关系
     * @return [type] [description]
     */
    public function getTermRelations()
    {
        return $this->hasMany(TermRelations::className(), ['term' => 'termid']);
    }

    /**
     * terms表对应的menus表关系 一对多
     * @return object ActiveQuery object
     */
    public function getMenus()
    {
        return $this->hasMany(Menus::className(), ['menuid' => 'objectid'])
            ->via('termRelations')->orderBy(['menu_sort' => SORT_ASC]);
    }

    /**
     * 获取当前分类推荐文章
     * @return [type] [description]
     */
    public function getNicePostViews()
    {
        return $this->hasMany(PostViews::className(), ['postid' => 'objectid'])
            ->andWhere(['status' => Posts::STATUS_ONLINE, 'isnice' => Posts::YES])
            ->via('termRelations');
    }

    /**
     * 通过主键获取term
     * @param  integer $termid terms主键
     * @return array
     */
    public function getTerm($termid)
    {
        if ($termid) {
            return $this->find()->where(['termid' => intval($termid)])->one();
        }
        return null;
    }

    /**
     * 通过标题获取term
     * @param  string $title term标题
     * @return array
     */
    public function getTermByTitle($title = '', $type = '')
    {
        if ($title && $type) {
            return $this->find()->where(['title' => trim($title), 'catetype' => $type])->asArray()->one();
        }
        return null;
    }

    /**
     * 获取全部名称
     * @param  string $type terms category type
     * in['category', 'nav_menu', 'post_tag', 'link_category']
     * @return array
     */
    public function getTerms($type)
    {
        if (in_array($type, [self::CATEGORY_CATE, self::CATEGORY_MENU, self::CATEGORY_TAG, self::CATEGORY_LINK])) {
            return $this->find()->where(['catetype' => $type])->asArray()->all();
        }
        return null;
    }

    /**
     * 获取term全部子项
     * @param  integer $parent termid
     * @param  string  $type   in['category', 'nav_menu', 'post_tag', 'link_category']
     * @return array|null
     */
    public static function getTermChildrens($parent = 0, $type = '')
    {
        if ($type) {
            return static::find()->where(['parent' => intval($parent), 'catetype' => $type])->asArray()->all();
        }
        return null;
    }

    /**
     * 获取分类
     * @param  integer $termid terms主键
     * @return array
     */
    public function getCategory($termid = null)
    {
        if (ctype_digit($termid)) {
            return $this->find()->where(['termid' => $termid, 'catetype' => self::CATEGORY_CATE])->asArray()->one();
        } else {
            return $this->find()->where(['catetype' => self::CATEGORY_CATE])->asArray()->all();
        }
    }

    /**
     * 获取全部文章分类
     * @param  array  $orderBy [description]
     * @return [type]          [description]
     */
    public static function getCategorys($termid = '', $orderBy = ['counts' => SORT_DESC])
    {
        if ($termid !== '') {
            return static::find()
            ->where(['parent' => $termid, 'catetype' => self::CATEGORY_CATE])
            ->orderBy($orderBy);
        }
        return static::find()
            ->where(['catetype' => self::CATEGORY_CATE])
            ->orderBy($orderBy);
    }

    /**
     * 获取热门的标签
     * @param  integer $limit 条数
     * @return array|null         数据记录
     */
    public static function getHotTags($limit = 10)
    {
        return static::find()->where(['catetype' => Terms::CATEGORY_TAG])
            ->limit($limit)
            ->orderBy(['counts' => SORT_DESC])
            ->asArray()
            ->all();
    }
    /**
     * 获取最近分类
     * @param  string $column 字段
     * @param  integer $limit 要返回的记录条数
     * @return array
     */
    public function getCategoryRecent($column = '*', $limit = 10)
    {
        return $this->find()->select($column)->where(['catetype' => self::CATEGORY_CATE])->limit(intval($limit))->asArray()->all();
    }

    /**
     * 通过termid判断term是否存在
     * @param  integer $termid terms主键
     * @param  string $type    分类类型in['category', 'nav_menu', 'post_tag', 'link_category']
     * @return boolean         true存在，false不存在
     */
    public function getExistById($termid = 0, $type = '')
    {
        if (ctype_digit($termid) && $type) {
            return (boolean) $this->findOne(['termid' => $termid, 'catetype' => $type]);
        }

        return false;
    }

    /**
     * 通过title判断term是否存在
     * @param  string $title   term标题
     * @param  string $type    分类类型in['category', 'nav_menu', 'post_tag', 'link_category']
     * @return boolean         true存在，false不存在
     */
    public function getExistByTitle($title = '', $type = '')
    {
        if ($title && $type) {
            return (boolean) $this->findOne(['title' => $title, 'catetype' => $type]);
        }

        return false;
    }

    public static function deleteCategory($termid)
    {
        if (($termid = (int) $termid) && $termid != Yii::$app->params['post.defaultCategory']) {
            $menus = Menus::find()
                ->where(['object' => $termid, 'menu_type' => Menus::MENU_TYPE_TERM])
                ->with('termMenuRelation')
                ->all();
            foreach ($menus as $key => $item) {
                if ($item->termMenuRelation) {
                    $item->termMenuRelation->delete();
                }
                $item->delete();
            }

            TermRelations::updateAll(['term' => (int) Yii::$app->params['post.defaultCategory']], ['term' => $termid, 'type' => TermRelations::OBJECT_TYPE_POST]);

            static::find()->where(['termid' => $termid, 'catetype' => self::CATEGORY_CATE])->one()->delete();
        }
    }

    /**
     * 减少关联数量
     * @param  integer $termid termid
     * @return boolean
     */
    public static function decreaseCount($termid)
    {
        if ($termid) {
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET counts = `counts` - 1 WHERE termid =:termid', [':termid' => intval($termid)])->execute();
        }
        return false;
    }

    /**
     * 添加关联数量
     * @param  integer $termid termid
     * @return boolean
     */
    public static function increaseCount($termid)
    {
        if ($termid) {
            return Yii::$app->db->createCommand('UPDATE '.static::tableName().' SET counts = `counts` + 1 WHERE termid =:termid', [':termid' => intval($termid)])->execute();
        }
        return false;
    }
}
