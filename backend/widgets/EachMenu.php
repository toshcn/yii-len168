<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */
namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * 树形菜单小部件
 *
 * @author Toshcn <toshcn@foxmail.com>
 * @since 0.1
*/
class EachMenu extends Widget
{
    /**
     * @var ActiveForm the form that this field is associated with.
     */
    public $form;
    /**
     * @var Model the data model that this field is associated with
     */
    public $model;
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
    public $menuTemplate = '<ol>{items}</ol>';
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
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'ol');
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
                $this->html .= '<li id="menu-item-'.$itemId.'" class="dd-item menu-item menu-item-inactive" data-id="'.$itemId.'">
                    <a class="menu-item-dropdown" id="edit-'.$itemId.'" href="javascript:;" title="编辑" for="edit-'.$itemId.'"></a>
                    <div class="dd-handle" id="menu-handle-edit-'.$itemId.'">
                        <span class="menu-item-title" id="menu-item-title-'.$itemId.'">'.Html::encode($item['menu_title']).'</span>
                        <div class="menu-controls pull-right">
                            <span class="menu-item-type">'.$this->catetype[$item['menu_type']].'</span>
                            <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                    <div class="menu-item-settings" id="menu-item-edit-'.$itemId.'" style="display:none">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="" for="">导航标签<br/>
                                    '.$this->form->field($this->model, "[$itemId]menu_title", ['template' => '{input}'])->input('text', ['class' =>'form-control edit-menu-item-title', 'value' => $item['menu_title'], 'autocomplete' => 'off', 'placeholder' => '在此输入导航标签', 'for' => 'menu-item-title-'.$itemId]).
                                '</div>
                            </div>
                        </div>
                        <div class="menu-item-actions submitbox">
                            <p class="link-to-original">原始: <a href="javascript:;">'.$item[$item['menu_type']]['title'].'</a></p>
                            <a class="menu-item-delete submitdelete" href="javascript:;" for="menu-item-'.$itemId.'" data-object="'.$itemId.'" data-term-cateid="'.$this->navMenuId.'">删除</a>
                            <span class="meta-sep"> | </span>
                            <a class="menu-item-cancel submitcancel" href="javascript:;" for="edit-'.$itemId.'">取消</a>
                        </div>
                        <input type="hidden" id="menu-item-parent-'.$itemId.'" name="Menus['.$itemId.'][menu_parent]" value="'.$item['menu_parent'].'">
                        <input type="hidden" id="menu-item-sort-'.$itemId.'" name="Menus['.$itemId.'][menu_sort]" value="'.$item['menu_sort'].'">
                    </div>';
                $options = $this->options;
                $tag = ArrayHelper::remove($options, 'tag', 'ol');
                $child = $this->renderChildItem($menuItems, $item['menuid']);
                if ($child) {
                    if ($tag !== false) {
                        $this->html .= Html::tag($tag, $child, $options);
                    } else {
                        $this->html .= strtr($this->menuTemplate, ['{items}' => $child]);
                    }
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
                $html .= '<li id="menu-item-'.$itemId.'" class="dd-item menu-item menu-item-inactive" data-id="'.$itemId.'">
                    <a class="menu-item-dropdown" id="edit-'.$itemId.'" href="javascript:;" title="编辑" for="edit-'.$itemId.'"></a>
                    <div class="dd-handle" id="menu-handle-edit-'.$itemId.'">
                        <span class="menu-item-title" id="menu-item-title-'.$itemId.'">'.Html::encode($item['menu_title']).'</span>
                        <div class="menu-controls pull-right">
                            <span class="menu-item-type">'.$this->catetype[$item['menu_type']].'</span>
                            <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                    <div class="menu-item-settings" id="menu-item-edit-'.$itemId.'" style="display:none">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="" for="">导航标签<br/>
                                    '.$this->form->field($this->model, "[$itemId]menu_title", ['template' => '{input}'])->input('text', ['class' =>'form-control edit-menu-item-title', 'value' => $item['menu_title'], 'autocomplete' => 'off', 'placeholder' => '在此输入导航标签']).
                                '</div>
                            </div>
                        </div>
                        <div class="menu-item-actions submitbox">
                            <p class="link-to-original">原始: <a href="javascript:;">'.$item[$item['menu_type']]['title'].'</a></p>
                            <a class="menu-item-delete submitdelete" href="javascript:;" for="menu-item-'.$itemId.'" data-objecid="'.$itemId.'" data-term-cateid="'.$this->navMenuId.'">删除</a>
                            <span class="meta-sep"> | </span>
                            <a class="menu-item-cancel submitcancel" href="javascript:;" for="edit-'.$itemId.'">取消</a>
                        </div>
                        <input type="hidden" id="menu-item-parent-'.$itemId.'" name="Menus['.$itemId.'][menu_parent]" value="'.$item['menu_parent'].'">
                        <input type="hidden" id="menu-item-sort-'.$itemId.'" name="Menus['.$itemId.'][menu_sort]" value="'.$item['menu_sort'].'">
                    </div>';
                $options = $this->options;
                $tag = ArrayHelper::remove($options, 'tag', 'ol');
                $child = $this->renderChildItem($menuItems, $item['menuid']);
                if ($child) {
                    if ($tag !== false) {
                        $html .= Html::tag($tag, $child, $options);
                    } else {
                        $html .= strtr($this->menuTemplate, ['{items}' => $child]);
                    }
                }
                $html .= '</li>';
            }
        }
        return $html;
    }
}