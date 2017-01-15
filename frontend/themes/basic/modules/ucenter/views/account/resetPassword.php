<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\widgets\JsBlock;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

$this->title = '重置密码';
?>

<div class="account-box">
    <div class="account-head">
        <div class="account-head-nav">
            <a href="<?= Url::home(true) ?>">首页</a>
            <a href="<?= Url::to(['/ucenter/account/signup'], true) ?>">注册帐号</a>
            <a href="<?= Url::to(['/ucenter/account/help']) ?>">帮助</a>
        </div>
    </div>
    <div class="account-content">
        <div class="account-content-top">
            <h2 class="title"><?= $this->title ?></h2>
        </div>
        <?php if (0) { ?>
        <div class="account-success">
            <p>重置密码链接已发送到您的注册邮箱，请分钟内完成密码更改。</p>
            <p>如果<code>分钟内</code>未收到重置密码链接的邮件，请再次申请。</p>
            <p><code></code></p>
            <div class="item">
                <a href="http://mail." class="item-btn">立即进入邮箱</a>
            </div>
        </div>
        <?php } else { ?>
        <div class="account-frame">
            <?php $form = ActiveForm::begin([
                'id' => 'account-form',
                'fieldConfig' => [
                'template' => '{label}<div class="input-item">{input}</div><span class="error-tip">{error}</span>',
                    'labelOptions' => ['class' => 'control-label'],
                ],
            ]); ?>
            <?= $form->field($model, 'password')
                    ->passwordInput(['autocomplete' => 'off', 'placeholder' => '新密码', 'class' => "form-control"]) ?>
            <?= $form->field($model, 'repassword')
                    ->passwordInput(['autocomplete' => 'off', 'placeholder' => '确认密码', 'class' => "form-control"]) ?>

            <div class="item">
                <a href="javascript:;" class="item-btn btn-warning" id="account-submit">重置密码</a>
            </div>
            <?php ActiveForm::end();?>
        </div>
        <?php } ?>
    </div>
</div>

<?php JsBlock::begin() ?>
<script>
    jQuery(function($) {
        $(document).ready(function() {
            $('#account-submit').on('click.account', function() {
                $('#account-form').submit();
            });
        });
    });

</script>
<?php JsBlock::end() ?>