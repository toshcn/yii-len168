<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%menus}}".
 *
 * @property integer $menuid
 * @property string $title
 * @property integer $sort
 */
class Menus extends \yii\db\ActiveRecord
{
    const MENU_TYPE_TERM = 'term';
    const MENU_TYPE_POST = 'post';
    const MENU_TYPE_LINK = 'link';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menus}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_title', 'object'], 'required', 'on' => 'create'],
            [['menu_title', 'menu_parent', 'menu_sort'], 'required', 'on' => 'update'],
            ['object', 'integer'],
            ['menu_type', 'string', 'max' => 10],
            ['menu_type', 'in', 'range' => [self::MENU_TYPE_TERM, self::MENU_TYPE_POST, self::MENU_TYPE_LINK]],
            [['menu_title', 'menu_type'], 'trim'],
            ['menu_title', 'string', 'max' => 128],
            [['menu_parent', 'menu_sort'], 'default', 'value' => 0],
            [['object', 'menu_type'], 'safe', 'on' => 'update'],

        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menuid' => 'Menu Id',
            'object' => 'Object Id',
            'menu_type' => 'Menu Type',
            'menu_title' => 'Menu Title',
            'menu_parent' => 'Menu Parent',
            'menu_sort' => 'Sort',
        ];
    }

    /**
     * 场景
     * @return [type] [description]
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['object', 'menu_type', 'menu_title', 'menu_parent', 'menu_sort'];
        $scenarios['update'] = ['menu_title', 'menu_parent', 'menu_sort'];
        return $scenarios;
    }

    /**
     * menus表对应的term表关系 一对一
     * @return object ActiveQuery object
     */
    public function getTerm()
    {
        return $this->hasOne(Terms::className(), ['termid' => 'object']);
    }

     /**
     * menus表对应的termRelations表关系 一对一
     * @return object ActiveQuery object
     */
    public function getTermMenuRelation()
    {
        return $this->hasOne(TermRelations::className(), ['objectid' => 'menuid'])->andWhere(['type' => TermRelations::OBJECT_TYPE_MENU]);
    }

    /**
     * 创建菜单
     * @param  array  $menus [description]
     * @return [type]        [description]
     */
    public function createMenu($menus = [])
    {
        $this->scenario = 'create';
        $menuId = [];
        if (!empty($menus) && is_array($menus[0])) {
            foreach ($menus as $key => $item) {
                $this->isNewRecord = true;
                $this->object = $item['object'];
                $this->menu_type = $item['type'];
                $this->menu_sort = 0;
                $this->menu_title = $item['title'];
                $this->menu_parent = intval($item['parent']);
                if ($this->validate()) {
                    $this->save(false);
                    $menuId[$key]['menuid'] = $this->menuid;
                    $menuId[$key]['cateid'] = $item['id'];
                    $this->menuid = 0;
                }
            }
        } elseif (!empty($menus)) {
            $this->object = $menus['object'];
            $this->menu_type = $menus['type'];
            $this->menu_sort = 0;
            $this->menu_title = $menus['title'];
            $this->menu_parent = intval($menus['parent']);
            if ($this->validate()) {
                $this->save(false);
                $menuId[0]['menuid'] = $this->menuid;
                $menuId[0]['cateid'] = $item['id'];
            }
        }
        return $menuId;
    }
}
