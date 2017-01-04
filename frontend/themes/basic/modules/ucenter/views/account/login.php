<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\widgets\JsBlock;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
/* @var $this->params['rememberName'] for remember user name*/

$cookie = Yii::$app->request->cookies;
$this->params['rememberName'] = $cookie->getValue('LOGINNAME_REMEMBER');
$this->title = '登录';

?>
<div class="login-box">
    <div class="login-head">
        <a href="/"><h1 class="logo"><span class="hide-text"><?= Yii::$app->name ?></span></h1></a>
        <div class="login-subtitle">会员帐号登录</div>
    </div>
    <div class="login-content">
        <div class="login-frame">
            <?php $form = ActiveForm::begin([
                'id' => 'account-form',
            ]); ?>
                <?php if ($model->tipes) {?>
                    <label class="error-tipes"><?= $model->tipes ?></label>
                <?php } ?>
                <?= $form->field($model, 'username', [
                    'template' => '<div class="input-item input-txt input-txt-right">{input}<span class="smg-txt">{error}</span></div>'])
                    ->input('text', ['placeholder' => Yii::t('common/label', 'User Name'), 'class' => "form-control login-input"]) ?>

                <?= $form->field($model, 'password', [
                    'template' => '<div class="input-item input-txt input-txt-right">{input}<span class="smg-txt">{error}</span></div>'])
                    ->passwordInput(['placeholder' => Yii::t('common/label', 'Login Password'), 'class' => "form-control login-input"]) ?>

                <div class="item rememberme">
                    <label><input type="checkbox" name="LoginForm[rememberMe]" id="login-rememberme" value="1" checked="checked"><?= Yii::t('common', 'Remember me') ?></label>
                    <span class="pull-right">
                        <a target="_blank" href="<?= Url::to(['/ucenter/account/forget-password'])?>"><?= Yii::t('common', 'Forget password?') ?></a>
                    </span>
                </div>
                <div class="item">
                <?php if ($model->interval) { ?>
                    <a href="javascript:;" class="btn btn-white item-btn login-btn" id="account-submit">
                        (<b class="times" id="times"><?= $model->interval ?></b>)秒后再登录
                    </a>
                <?php } else { ?>
                    <a href="javascript:;" class="btn btn-white item-btn login-btn" id="account-submit"><?= Yii::t('common', 'Sign in now') ?></a>
                <?php } ?>
                </div>
                <div class="item">
                    <a href="<?= Url::to(['/ucenter/account/signup']) ?>" class="btn btn-white item-btn signup-btn">
                        <?= Yii::t('common/sentence', 'No account ? Registered immediately') ?>
                    </a>
                </div>
                <div class="item other-login-type">
                    <fieldset class="other-title">
                        <legend align="center" class="other-txt"><?= Yii::t('common/sentence', 'Other ways to log in') ?></legend>
                    </fieldset>
                    <div class="sns-login">
                        <!--<a href="" title="微博"><i class="fa fa-weibo icon-md"></i></a>
                        <a href="" title="QQ"><i class="fa fa-qq icon-qq"></i></a>
                        <a href="" title="微信"><i class="fa fa-weixin icon-md"></i></a>-->
                        <a href="<?= Url::to(['/ucenter/auth/login', 'authclient' => 'github'], true)?>" title="Github"><i class="fa fa-github icon-github"></i></a>
                    </div>
                </div>
                <input type="hidden" name="LoginForm[os]" value="">
                <input type="hidden" name="LoginForm[browser]" value="">

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php JsBlock::begin() ?>
<script>
    jQuery(function($) {
        var _timeOut = <?= $model->interval ?>;
        $(document).ready(function() {
            uaparser('[name="LoginForm[os]"]', '[name="LoginForm[browser]"]');

            if (_timeOut) {
                timeout('#times', '#account-submit', '登录');
            } else {
                $('#account-submit').on('click.account', submitAccountForm);
            }
        });

        //倒计时
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
        //设置OS, 浏览器
        function uaparser(os, browser) {
            var parser = new UAParser();
            var o = parser.getOS();
            var b = parser.getBrowser();
            console.log(os);
            console.log(browser);
            $(os).val(o.name);
            $(browser).val(b.name +','+ b.major);
        }
    });


</script>
<?php JsBlock::end() ?>
