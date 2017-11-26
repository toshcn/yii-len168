<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\Users */
/* @var $token common\models\Token */
?>
<div class="password-reset">
    <p>您好： <?= Html::encode($nickname) ?>,</p>

    <p>您正在进行重置密码的操作，此链接<?= Yii::$app->params['resetTokenExpire'] ?>分钟内有效，</p>
    <p>请点击下面的链接重置您的帐户密码，如果此邮件不是您本人操作，请勿泄露此链接:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
