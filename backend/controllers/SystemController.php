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
use backend\controllers\MainController;

/**
 * System controller
 */
class SystemController extends MainController
{

    public function actionIndex()
    {
        return $this->render('index', ['route' => $this->route]);
    }

    public function actionGeneral()
    {
        return $this->render('general', ['route' => $this->route]);
    }
}
