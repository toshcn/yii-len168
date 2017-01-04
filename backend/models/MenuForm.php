<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\Terms;

/**
 * 后台菜单表单
 */
class MenuForm extends Model
{
    public $menuTitle = '';//菜单名称
    public $menuItemTitle = [];//菜单项名称
    public $menuItemSort = [];//菜单项排序
    public $menuId = ''; //菜单id

    public function attributeLabe()
    {
        return [
            'title' => Yii::t('backend/menuForm', 'Menu Title'),
        ];
    }

    public function rules()
    {
        return [
            ['menuTitle', 'required'],
            ['menuTitle', 'trim'],
            ['menuTitle', 'string', 'max' => 128],
        ];
    }

    public function saveNavMenu()
    {
        if ($this->validate()) {
            return $this->menuId ? $this->updateNavMenu() : $this->addNavMenu();
        }
        return null;
    }

    public function updateNavMenu()
    {
        $term = new Terms();
        $term->termid = $this->menuId;
        $term->title = $this->menuTitle;
        return $term->save();
    }

    public function addNavMenu()
    {
        $term = new Terms();
        $term->title = $this->menuTitle;
        $term->slug = $this->menuTitle;
        $term->catetype = Terms::CATEGORY_MENU;
        if ($term->save()) {
            return $this->menuId = $term->termid;
        }
        return null;
    }


}