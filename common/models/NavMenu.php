<?php
namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\base\Model;
use common\models\Posts;
use common\models\Links;
use common\models\Terms;

/**
 * 导航菜单 NavMenu
 *
 * @author Toshcn <toshcn@foxmail.com>
 * @since 0.1
 */
class NavMenu extends Model
{
    /**
     * @var integer or null `terms`表 菜单id
     */
    public $navMenuId = null;

    /**
     * @var array 由$navMenuId指定的`terms`表的菜单数据
     */
    public $navMenu   = [];
    /**
     * @var array `terms`表中全部菜单数据
     */
    public $navMenus  = [];
    /**
     * @var array 由$navMenuId指定的`terms`表的菜单, 包含了对应关系表的数据 如terms, posts, links(由菜单的menu_type字段决定).
     */
    public $navMenuItems = [];

    /**
     * 检测是否有菜单
     * @return object terms::class
     */
    public function hasNavMenu()
    {
        $termObj = new Terms();
        return $termObj->findNavMenu($this->navMenuId)->asArray()->one();
    }

    /**
     * 返回当前的菜单
     * @return array
     */
    public function getNavMenu()
    {
        if (empty($this->navMenu)) {
            $this->setNavMenu();
        }
        return $this->navMenu;
    }

    /**
     * 返回全部菜单
     * @return array
     */
    public function getNavMenus()
    {
        if (empty($this->navMenus)) {
            $this->setNavMenus();
        }
        return $this->navMenus;
    }

    /**
     * 返回当前菜单的全部分类项
     * @return array
     */
    public function getNavMenuItems()
    {
        if (empty($this->navMenuItems)) {
            $this->setNavMenuItems();
        }
        return $this->navMenuItems;
    }

    /**
     * 查找当前菜单
     */
    public function setNavMenu()
    {
        $termObj = new Terms();
        return $this->navMenu = $termObj->findNavMenu($this->navMenuId)->asArray()->one();//当前菜单
    }
    /**
     * 查找全部菜单
     */
    public function setNavMenus()
    {
        $termObj = new Terms();
        return $this->navMenus = $termObj->findNavMenu(null)->asArray()->all();//全部菜单
    }
    /**
     * 查找当前菜单的全部分类项
     */
    public function setNavMenuItems()
    {
        $termObj = new Terms();
        $menuItems   = $termObj->findNavMenu($this->navMenuId)->one()->getMenus()->asArray()->all();//当前菜单包含的所有项

        foreach ($menuItems as $key => $item) {
            $item['object'] = intval($item['object']);
            switch ($item['menu_type']) {
                case 'term':
                    $menuItems[$key]['term'] = Terms::find()->where(['termid' => $item['object']])->select('title')->asArray()->one();
                    break;
                case 'post':
                    $menuItems[$key]['post'] = Posts::find()->where(['postid' => $item['object']])->select('title')->asArray()->one();
                    break;
                case 'link':
                    $menuItems[$key]['link'] = Links::find()->where(['linkid' => $item['object']])->select('link_title as title, link_url')->asArray()->one();
                    break;
            }
        }
        $this->navMenuItems = $menuItems;
    }

    public function getSortNavMenuItems($items = [])
    {
        $items = $items ? $items : $this->getNavMenuItems();
        $temp = [];
        foreach ($items as $key => $item) {
            if ($item['menu_parent'] == 0) {
                $temp[$key] = [
                    'label' => $item['menu_title'],
                    'url' => self::getMenuUrl($item),
                ];
                unset($items[$key]);
                $childs = self::getChildMenu($items, $item['menuid']);
                if ($childs) {
                    //var_dump($items);
                    $temp[$key]['items'] = $childs;
                    $temp[$key]['label'] = $item['menu_title'] . '<i class="fa fa fa-caret-down top-fa"></i>';
                    $temp[$key]['options'] = ['class' => 'has-subnav'];
                }
            }
        }
        return $temp;
    }

    public static function getChildMenu(&$items, $parent = 0)
    {
        if ($parent) {
            $temp = [];
            foreach ($items as $key => $item) {
                if ($item['menu_parent'] == $parent) {
                    $temp[$key] = [
                        'label' => $item['menu_title'],
                        'url' => self::getMenuUrl($item),
                    ];
                    unset($items[$key]);
                    $childs = self::getChildMenu($items, $item['menuid']);
                    if ($childs) {
                        $temp[$key]['items'] = $childs;
                        $temp[$key]['label'] = $item['menu_title'] . '<i class="fa fa fa-caret-right"></i>';
                        $temp[$key]['options'] = ['class' => 'has-subnav'];
                    }
                }
            }
            return $temp;
        }
        return [];
    }
    /**
     * 底部导航
     * @param  array  $items [description]
     * @return [type]        [description]
     */
    public function getFooterNavMenuItems($items = [])
    {
        $items = $items ? $items : $this->getNavMenuItems();
        $temp = [];
        foreach ($items as $key => $item) {
            $temp[$key] = [
                'label' => $item['menu_title'],
                'url' => self::getMenuUrl($item),
            ];
        }
        return $temp;
    }

    public static function getMenuUrl(&$item)
    {
        switch ($item['menu_type']) {
            case 'term':
                $url = ['/article/lists', 'id' => $item['object']];
                break;
            case 'post':
                $url = ['/article/detail', 'id' => $item['object']];
                break;
            case 'link':
                $url = (string) $item['link']['link_url'];
                break;
            default:
                $url = '';
        }
        return $url;
    }
}
