<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */


use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user common\models\User */
?>
<div class="password-reset">
    <p>您好,</p>

    <p><?= Html::encode($inviter) ?>邀请您注册成为<a href="<?= Yii::$app->params['siteHost'][Yii::$app->params['httpProtocol']]; ?>"><?= Yii::$app->params['siteDomain'] ?></a>网会员: 点击下面链接完成注册！</p>

    <p><?= Html::a(Html::encode($invitateUrl), $invitateUrl) ?></p>
</div>
