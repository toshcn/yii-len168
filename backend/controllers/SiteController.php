<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;

class SiteController extends Controller
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
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        } else {
            return $this->redirect(Yii::$app->urlManager->createUrl('/ucenter/account/login', 'home'));
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

