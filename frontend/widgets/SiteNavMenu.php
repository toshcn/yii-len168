<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */
namespace frontend\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\NavMenu;
/**
 * 导航菜单小部件
 *
 * @author Toshcn <toshcn@foxmail.com>
 * @since 0.1
 */
class SiteNavMenu extends Widget
{
    /**
     * @var integer 当前导航菜单ID
     */
    public $navMenuId;
    /**
     * @var array 当前导航菜单下全部菜单项，{%menus%}表记录
     */
    public $menuItems = [];
    /**
     * @var string 菜单项的html模板
     */
    public $menuTemplate = '<ul>{items}</ul>';
    /**
     * @var array 配置html模板数据
     */
    public $options = [];
    /**
     * @var integer 根节点菜单id
     */
    public $parent = 0;
    /**
     * @var array 菜单类型对应名称数据
     */
    public $catetype = ['term'=>'分类', 'post'=>'文章','link'=>'链接'];
    /**
     * @var string 返回生成的菜单的html
     */
    public $html = '';


    public function run()
    {
        $options = $this->options['topTagOption'];
        $tag = ArrayHelper::getValue($options, 'tag', 'ul');
        if ($tag !== false) {
            echo Html::tag($tag, $this->renderItems($this->menuItems, $this->parent), $options);
        } else {
            echo strtr($this->menuTemplate, ['{items}' => $this->renderItems($this->menuItems, $this->parent)]);
        }
    }

    /**
     * 从根菜单开始渲染
     * @param  &array  &$menuItems 当前导航菜单的全部菜单项
     * @param  integer $parent     父菜单ID
     * @return string              整理生成的html代码
     */
    public function renderItems(&$menuItems, $parent = 0)
    {
        foreach ($menuItems as $i => $item) {
            if ($parent == $item['menu_parent']) {
                $itemId = $item['menuid'];
                $options = $this->options['subTagOption'];
                $tag = ArrayHelper::remove($options, 'subTag', 'ul');
                $child = $this->renderChildItem($menuItems, $item['menuid']);
                if ($child) {
                    $this->html .= '<li class="has-subnav">'.$item['menu_title'];
                    if ($tag !== false) {
                        $this->html .= Html::tag($tag, $child, $options);
                    } else {
                        $this->html .= strtr($this->menuTemplate, ['{items}' => $child]);
                    }
                } else {
                    $this->html .= '<li>'.$item['menu_title'];
                }
                $this->html .= '</li>';
            }
        }
        return $this->html;
    }

    /**
     * 递归渲染子菜单
     * @param  &array  &$menuItems 当前导航菜单的全部菜单项
     * @param  integer $parent     父菜单ID
     * @return string              整理生成的html代码
     */
    public function renderChildItem(&$menuItems, $parent)
    {
        if (!$parent) return '';
        $html = '';
        foreach ($menuItems as $i => $item) {
            if ($parent == $item['menu_parent']) {
                $itemId = $item['menuid'];
                //$html .= '<li>'.$item['menu_title'];
                $options = $this->options['subTagOption'];
                $tag = ArrayHelper::remove($options, 'subTag', 'ul');
                $child = $this->renderChildItem($menuItems, $item['menuid']);
                if ($child) {
                    $this->html .= '<li class="has-subnav">'.$item['menu_title'];
                    if ($tag !== false) {
                        $html .= Html::tag($tag, $child, $options);
                    } else {
                        $html .= strtr($this->menuTemplate, ['{items}' => $child]);
                    }
                } else {
                    //$this->html .= '<li>'.$item['menu_title'];
                }
                $html .= '</li>';
            }
        }
        return $html;
    }
}