<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\widgets\JsBlock;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
$this->title = '补全帐号信息';
?>
<div class="login-box auth-box">
    <div class="login-head">
        <a href="/"><h1 class="logo"></h1></a>
        <img class="img-circle" src="<?= $model->avatar ?>" height="74">
        <div class="login-subtitle">补全帐号信息</div>
    </div>
    <div class="login-content">
        <div class="login-frame">
            <?php $form = ActiveForm::begin([
                'id' => 'account-form',
                'options' => [
                    'class' => 'auth-form'
                ],
            ]); ?>
                <?= $form->field($model, 'nickname', [
                    'template' => '{label}<div class="input-item input-txt input-txt-right">{input}<span class="smg-txt">{error}</span></div>'])
                    ->input('text', ['placeholder' => Yii::t('common/label', 'Nick Name'), 'class' => "form-control login-input"]) ?>

                <?= $form->field($model, 'password', [
                    'template' => '{label}<div class="input-item input-txt input-txt-right">{input}<span class="smg-txt">{error}</span></div>'])
                    ->passwordInput(['placeholder' => Yii::t('common/label', 'Login Password'), 'class' => "form-control login-input"]) ?>

                <?= $form->field($model, 'repassword', [
                    'template' => '{label}<div class="input-item input-txt input-txt-right">{input}<span class="smg-txt">{error}</span></div>'])
                    ->passwordInput(['placeholder' => Yii::t('common/label', 'Confirm Password'), 'class' => "form-control login-input"]) ?>

                <?= $form->field($model, 'captcha', [
                    'template' => '{label}<div class="input-item input-txt input-txt-right">{input}</div>'])
                        ->widget(\yii\captcha\Captcha::className(), [
                            'captchaAction' => '/ucenter/auth/captcha',
                            'options' => [
                                'class' => 'form-control',
                                'autocomplete' => 'off',
                                'maxlenght' => 5,
                                'placeholder' => Yii::t('common', 'Case insensitive')
                            ],
                            'template' => '<div class="row"><div class="col-sm-7 pull-left">{input}</div><div class="col-sm-5">{image}</div></div>',
                            'imageOptions' => [
                                'height' => '34',
                                'alt'    =>Yii::t('common', 'Click change'),
                                'title'  =>Yii::t('common', 'Click change'),
                                'style'  =>'cursor:pointer',
                            ],
                        ]);
                ?>

                <?= $form->field($model, 'agreement', [
                    'checkboxTemplate' => '<div class="agreement">{beginLabel}{input}{label}{endLabel}</div>'])->checkbox([
                            'label' => Yii::t('common/sentence', 'I have read and agree to this ') . Html::a(Yii::t('common', '<membership>'), ['/ucenter/account/request-password-reset'], ['class' => '']),
                            'labelOptions' => ['class' => "control-label pull-left"]]) ?>

                <div class="item oauth-signup">
                    <a href="javascript:;" class="btn btn-success item-btn" id="account-submit">立即绑定</a>
                </div>
                <?= $form->field($model, 'os', ['template' => '{input}'])->hiddenInput() ?>
                <?= $form->field($model, 'browser', ['template' => '{input}'])->hiddenInput() ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php JsBlock::begin() ?>
<script>
    jQuery(function($) {
        $(document).ready(function() {
            uaparser('[name="AuthForm[os]"]', '[name="AuthForm[browser]"]');
        });
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