<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\widgets\JsBlock;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
$this->title = '登录信息';
?>
<div class="login-box auth-box">
    <div class="login-head">
        <div class="login-subtitle">第三方登录</div>
    </div>
    <div class="login-content">
        <div class="auth-login-box">
            <?php foreach ($error as $key => $item) {?>
                <p><?= $item ?></p>
            <?php }?>
        </div>
        <div class="auth-item align-center">
        <?php if (Yii::$app->getUser()->isGuest) {?>
            <p><a href="<?= Url::to(['/ucenter/account/login'], true) ?>" class="btn btn-success item-btn">重新登录</a></p>
        <?php } else { ?>
            <p><a href="<?= Url::to(['/ucenter/user/link-oauth'], true) ?>" class="btn btn-success item-btn">重新绑定</a></p>
        <?php } ?>
            <p><a href="<?= Url::to(['/site/index'], true) ?>" class="btn btn-warning item-btn">返回首页</a></p>
        </div>
    </div>
</div>
<?php JsBlock::begin() ?>
<script>
    jQuery(function($) {
    });
</script>
<?php JsBlock::end() ?>