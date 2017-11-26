<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license
 */

namespace frontend\modules\ucenter\controllers;

use yii\helpers\Url;
use yii\web\Controller;

/**
 * frontend 会员模块 basc公共基类控制器
 *
 * @author Toshcn <toshcn@foxmail.com>
 * @since 1.0
*/
class CommonController extends Controller
{
    public $route;
    /**
     * 未登录用户跳转到登录页
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (\Yii::$app->user->isGuest) {
                $this->redirect(['/ucenter/account/login']);
                return false;
            }

            $this->route = $this->getRoute();
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
