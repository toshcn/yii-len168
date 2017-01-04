<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;

/**
 * backend 公共控制器
 * Main controller
 */
class MainController extends Controller
{
    public $route = ''; //路由
    //public $layout = 'main';
    /**
     * 登录用户跳转到会员中心
     * urlManager组件必需配置
     * ~~~
     * [
     *     'enablePrettyUrl' = true,
     *     'showScriptName' => false,
     * ]
     * ~~~
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (\Yii::$app->user->isGuest || Yii::$app->getUser()->getId() != 10000) {
                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['/ucenter/account/login'], Yii::$app->params['httpProtocol'], 'home'));
            }
            return true;
        }
        return false;
    }
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}
