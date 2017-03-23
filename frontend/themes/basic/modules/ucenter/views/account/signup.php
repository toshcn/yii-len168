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
use frontend\assets\SignupAsset;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

SignupAsset::register($this);
$this->title = '注册新帐号';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper w-center register-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="head input-group-">
                <h3 class="green pull-left"><?= Yii::t('common/sentence', 'Welcome join us!') ?></h3>
                <div class="pull-right">
                    已有帐号，<a class="orange" href="<?= Url::to(['/ucenter/account/login'])?>">直接登录</a>
                </div>
            </div>
            <div class="clearfix"><hr class="m-t-5"></div>
        </div>
        <div class="col-md-12">
            <?php $form = ActiveForm::begin([
                'id' => 'signup-form',
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => '{label}<div class="col-md-4">{input}</div><div class="col-md-4">{error}</div>',
                    'labelOptions' => ['class' => 'col-md-3 control-label'],
                ],
            ]); ?>
                <?= $form->field($model, 'inviteCode', [
                    'template' => '{label}<div class="col-md-4"><div class="input-icon input-icon-left"><i class="smg-icon fa fa-code"></i>{input}</div></div><div class="col-md-4 fix-help-block"><p class="has-error-hide">'.Html::a(Yii::t('common/sentence', 'How to get the registration invitation code?'), ['/help/how-to-register']).'</p>{error}</div>'])
                    ->input('text', ['maxlength' => '32', 'placeholder' => Yii::t('common/label', 'Invitation Code')]) ?>
                <?= $form->field($model, 'username', [
                    'template' => '{label}<div class="col-md-4"><div class="input-icon input-icon-left"><i class="smg-icon fa fa-user"></i>{input}</div></div><div class="col-md-4">{error}</div>'])
                    ->input('text', ['placeholder' => Yii::t('common/label', 'User Name')]) ?>

                <?= $form->field($model, 'nickname', [
                    'template' => '{label}<div class="col-md-4"><div class="input-icon input-icon-left"><i class="smg-icon fa fa-user"></i>{input}</div></div><div class="col-md-4">{error}</div>'])
                    ->input('text', ['placeholder' => Yii::t('common/label', 'Nick Name')]) ?>


                <?= $form->field($model, 'sex', ['radioTemplate' => '{label}<div class="col-md-4">{input}</div><div class="col-md-4">{error}</div>'])
                    ->inline(true)->radioList(['1' => '男', '0' => '女', '-1' => '保密'], ['class' => 'col-md-4'])
                ?>

                <?= $form->field($model, 'password', [
                    'template' => '{label}<div class="col-md-4"><div class="input-icon input-icon-left"><i class="smg-icon fa fa-lock"></i>{input}</div></div><div class="col-md-4">{error}</div>'])
                    ->passwordInput(['maxlength' => 20, 'placeholder' => Yii::t('common/sentence', 'English numeral underline combination.'), 'autocomplete' => 'off']) ?>

                <?= $form->field($model, 'repassword', [
                    'template' => '{label}<div class="col-md-4"><div class="input-icon input-icon-left"><i class="smg-icon fa fa-lock"></i>{input}</div></div><div class="col-md-4">{error}</div>'])
                    ->passwordInput(['maxlength' => 20, 'autocomplete' => 'off']) ?>

                <?= $form->field($model, 'captcha', [
                    'template' => '{label}<div class="col-md-4">{input}</div><div class="col-md-4">{error}</div>'])
                        ->widget(\yii\captcha\Captcha::className(), [
                            'captchaAction' => '/ucenter/account/captcha',
                            'options' => [
                                'class' => 'form-control',
                                'maxlength' => 5,
                                'autocomplete' => 'off',
                                'placeholder' => Yii::t('common', 'Case insensitive')
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
                <?= $form->field($model, 'agreement', [
                    'checkboxTemplate' => '<div class="col-md-3"></div><div class="col-md-4 fix-help-block">{beginLabel}{input}{label}{endLabel}</div><div class="col-md-4">{error}</div>'])
                    ->checkbox(['label' => Yii::t('common/sentence', 'I have read and agree to this ') . Html::a(Html::encode(Yii::t('common', '<membership>')), ['/help/registration-protocol'], ['class' => '']), 'labelOptions' => ['class' => "control-label pull-left"]])  ?>

                <div class="form-group">
                    <div class="col-md-4 col-md-offset-3 align-center">
                        <?= Html::button(Yii::t('common', 'Sign up now'), ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
                    </div>
                </div>
                <input type="hidden" name="SignupForm[os]" value="">
                <input type="hidden" name="SignupForm[browser]" value="">
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php JsBlock::begin() ?>
    <script>
        jQuery(function($) {
            $(document).ready(function() {
                $('[name="signup-button"').on('click.signup', function() {
                    var parser = new UAParser();
                    var os = parser.getOS();
                    var browser = parser.getBrowser();
                    console.log(os)
                    console.log(browser)
                    $('[name="SignupForm[os]"]').val(os.name);
                    $('[name="SignupForm[browser]"]').val(browser.name+','+browser.major);
                    $('#signup-form').submit();
                });
            });
        });

    </script>
<?php JsBlock::end() ?>