<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\base\Model;
use backend\controllers\MainController;
use backend\models\MenuForm;
use common\models\NavMenu;
use common\models\Terms;
use common\models\TermRelations;
use common\models\Menus;
use common\models\Links;
use common\models\Posts;

/**
 * backend 外观 控制器
 * Surface controller
 *
 * @author Toshcn <toshcn@foxmail.com>
 * @version 0.1.0
 */
class SurfaceController extends MainController
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 菜单管理页
     */
    public function actionMenus()
    {
        $term       = new Terms();
        $menuForm   = new MenuForm();
        $menu       = new Menus();
        $link       = new Links();
        $post       = new Posts();
        $request    = Yii::$app->getRequest();
        $navMenuId  = $request->get('menu');
        $navMenuObj = new NavMenu(['navMenuId' => $navMenuId]);
        if ($menuForm->load($request->post()) && $menuForm->saveNavMenu()) {
            return $this->redirect(['menus', 'menu' => $menuForm->menuId]);
        }

        $renderData = [
            'route'      => $this->route,
            'navMenu'    => $navMenuObj->getNavMenu(),
            'navMenus'   => $navMenuObj->getNavMenus(),
            'termRecent' => $term->getCategoryRecent('termid, title'),
            'linkRecent' => $link->getLinkRecent('linkid, link_title'),
            'postRecent' => $post->getPostRecent('postid, title'),
        ];
        if (empty($navMenuObj->hasNavMenu())) {
            $renderData['menuForm'] = $menuForm;
            $renderData['navMenuId'] = 0;
            return $this->render('menus', $renderData);
        } else {
            $renderData['navMenuId'] = $navMenuId;
            $renderData['navMenuItems'] = $navMenuObj->getNavMenuItems();
            $renderData['termModel'] = $term;
            $renderData['menuModel'] = $menu;
            return $this->render('menus', $renderData);
        }
    }
    /**
     * 查找分类
     */
    public function actionAjaxSearchCates()
    {
        $word = trim(Yii::$app->getRequest()->post('s'));

        if ($word) {
            $term = new Terms();
            $term = $term->find()->select('termid, title')->where(['like', 'title', $word])->asArray()->all();
            return json_encode($term);
        }
        return json_encode([]);
    }

    public function actionAjaxCreateMenu()
    {
        $menu   = new Menus();
        $request    = Yii::$app->getRequest();
        $menus = json_decode($request->post('menus'), true);
        return Json::encode($menu->createMenu($menus['menus']));
    }

    public function actionEditNavMenu()
    {
        $term = new Terms();
        $termRelations = new TermRelations();
        //当前导航菜单ID
        $navMenuId = Yii::$app->getRequest()->post('navMenuId');
        //更新菜单项
        $menuItems = Yii::$app->getRequest()->post('Menus');
        if ($menuItems) {
            foreach ($menuItems as $key => $menu) {
                $menus = Menus::findOne($key);
                $menus->scenario = 'update';
                $menus->menuid = $key;
                $menus->menu_title = $menu['menu_title'];
                $menus->menu_parent = $menu['menu_parent'];
                $menus->menu_sort = $menu['menu_sort'];
                $menus->save();
            }
            //更新菜单名称
            $term->load(Yii::$app->getRequest()->post());
            $term->isNewRecord = false;
            $term->save();
            $this->redirect(['menus', 'menu' => $navMenuId]);
        }

        //添加菜单项
        $termRelationPost = Yii::$app->getRequest()->post('TermRelations');
        if ($termRelationPost) {
            foreach ($termRelationPost as $termRelation) {
                $termRelations->isNewRecord = true;
                $termRelations->objectid = $termRelation['objectid'];
                $termRelations->term = $navMenuId;
                $termRelations->type = TermRelations::OBJECT_TYPE_MENU;
                $termRelations->save();
            }
        }
        //删除菜单项
        $deleteItem = explode(',', trim(Yii::$app->getRequest()->post('deleteItem'), ','));
        if ($deleteItem[0]) {
            $menus = new Menus();
            foreach ($deleteItem as $key => $item) {
                list($objectid, $termId) = explode('-', $item);
                //删除关系
                $termRelations->findOne(['objectid' => $objectid, 'term' => $termId, 'type' => TermRelations::OBJECT_TYPE_MENU])->delete();
                //删除菜单
                $menus->findOne($objectid)->delete();
            }
        }
        $this->redirect(['menus', 'menu' => $navMenuId]);
    }
}
