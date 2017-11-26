<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\Users */
/* @var $token common\models\Token */
?>
您好： <?= Html::encode($nickname) ?>,

您正在进行重置密码的操作，此链接<?= Yii::$app->params['resetTokenExpire'] ?>分钟内有效,
请复制下面的链接到浏览器中打开，重置您的帐户密码，如果此邮件不是您本人操作，请勿泄露此链接:

<?= $resetLink ?>
