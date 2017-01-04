<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use common\models\LoginForm;

/**
 * 后台管理员登录认证控制器
 * backend administrater controller for user login.
 *
 * @author Toshcn <toshcn@foxmail.com>
 * @since 0.1
*/
class AuthController extends Controller
{
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
    public function actionIndex()
    {
        $this->redirect(['login']);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        } else {
            $this->redirect(Yii::$app->urlManager->createUrl('/ucenter/account/login', 'home'));
        }
    }

    /**
     * logout action
     * 退出管理
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        $this->redirect(['login']);
    }
}
