<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

$this->title = '密码重置成功';
$this->params['bodyClass'] = 'gray-bg submit-success';
?>

<div class="account-box">
    <div class="account-head">
        <div class="account-head-nav">
            <a href="<?= Url::home(true) ?>">首页</a>
            <a href="<?= Url::to(['/ucenter/account/signup'], true) ?>">注册帐号</a>
            <a href="<?= Url::to(['/help/how-to-register']) ?>">帮助</a>
        </div>
    </div>
    <div class="account-content">
        <div class="account-content-top">
            <h2 class="title"><?= $this->title ?></h2>
        </div>
        <div class="account-success">
            <p>恭喜您已完成密码更改，请使用新密码重新登录。</p>
            <p></p>
            <div class="item">
                <a href="<?= Url::to(['/ucenter/account/login']) ?>" class="item-btn">重新登录</a>
            </div>
        </div>
    </div>
</div>
