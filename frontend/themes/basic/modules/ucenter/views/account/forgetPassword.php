<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\widgets\JsBlock;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '找回帐号密码';

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
        <?php if ($issend) { ?>
            <h2 class="title">重置密码链接已发送到您的注册邮箱</h2>
        <?php } else { ?>
            <h2 class="title">找回帐号密码</h2>
        <?php } ?>
        </div>
        <?php if ($success) {
            $emailArr = explode('@', $model->getUser()->email);
            $emailArr[0] = mb_strlen($emailArr[0]) > 4 ? substr($emailArr[0], 0, 1) . '***' . substr($emailArr[0], 4) : $emailArr[0];
            $this->params['bodyClass'] = 'gray-bg submit-success';
        ?>
        <div class="account-success">
            <p>重置密码链接已发送到您的注册邮箱，请<?= Yii::$app->params['resetTokenExpire'] ?>分钟内完成密码更改。</p>
            <p>如果<code><?= Yii::$app->params['resendResetTokenExpire'] ?>分钟内</code>未收到重置密码链接的邮件，请再次申请。</p>
            <p><code><?= $emailArr[0]. '@'.$emailArr[1] ?></code></p>
            <div class="item">
                <a href="http://mail.<?= $emailArr[1] ?>" class="item-btn">立即进入邮箱</a>
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
            <?= $form->field($model, 'username')
                ->input('text', ['placeholder' => '昵称/邮箱/会员帐号', 'class' => "form-control"]) ?>

            <?= $form->field($model, 'captcha')
                ->widget(\yii\captcha\Captcha::className(), [
                    'captchaAction' => '/ucenter/account/captcha',
                    'options' => [
                        'class' => 'form-control',
                        'autocomplete' => 'off',
                        'maxlength' => 5,
                        'placeholder' => '验证码'
                    ],
                    'template' => '<div class="row"><div class="col-sm-6 pull-left">{input}</div><div class="col-sm-6">{image}</div></div>',
                    'imageOptions' => [
                        'height' => '34',
                        'alt'    =>Yii::t('common', 'Click change'),
                        'title'  =>Yii::t('common', 'Click change'),
                        'style'  =>'cursor:pointer',
                    ],
                ]);
            ?>
            <div class="item">
            <?php if ($issend) { ?>
                <a href="javascript:;" class="item-btn btn-warning" id="account-submit">
                    (<b class="times" id="times"><?= $expires ?></b>)秒后可重发
                </a>
            <?php } else { ?>
                <a href="javascript:;" class="item-btn btn-warning" id="account-submit">立即验证</a>
            <?php } ?>
            </div>
            <?php ActiveForm::end();?>
        </div>
        <?php } ?>
    </div>
</div>

<?php JsBlock::begin() ?>
<script>
     jQuery(function($) {
        var _timeOut = <?= $expires ?>;
        <?php if ($issend) { ?>
        timeout('#times', '#account-submit', '立即验证');
        <?php } ?>

        //重发邀请倒计时
        function timeout(id, res, txt) {
            var interval = window.setInterval( function() {
                _timeOut -= 1;
                if (_timeOut >= 0) {
                    $(res).off('click.account');
                    $(id).text(_timeOut);
                };
                if (_timeOut <= 0) {
                    window.clearInterval(interval);
                    $(res).html(txt);
                    $(res).on('click.account', submitAccountForm);
                };
            }, 1000);
        }
    });
</script>
<?php JsBlock::end() ?>

