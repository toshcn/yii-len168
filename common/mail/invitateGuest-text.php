<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
?>
您好,

<?= Html::encode($inviter) ?>邀请您注册成为<?= Yii::$app->params['siteDomain'] ?>网会员: 复制下面链接到浏览器中打开以完成注册！

<?= $invitateUrl ?>
