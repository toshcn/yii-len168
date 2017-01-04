<?php

use yii\helpers\Html;
use frontend\assets\AccountAsset;
use common\widgets\JsBlock;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->params['bodyClass'] = isset($this->params['bodyClass']) ? $this->params['bodyClass'] : 'gray-bg';

AccountAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta charset="utf-8">
    <!-- 初始网页宽度为设置屏幕宽度，缩放级别为1.0，禁止用户缩放-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <!-- 设置360等双内核的浏览器渲染模式 -->
    <meta name="renderer" content="webkit">
    <!-- 设置IE支持的最高模式 -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <!-- 禁止移动浏览器转码 -->
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <!-- 网站标志 -->
    <!-- <link rel="icon" type="image/png" href="favicon.png"> -->
    <title><?= $this->title . ' | ' . \Yii::$app->name ?></title>
    <!-- 网站描述 -->
    <meta name="description" content="">
    <!-- 网站SEO关键词 -->
    <meta name="keywords" content="">
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body class="<?= $this->params['bodyClass'] ?>">
    <?php $this->beginBody() ?>
    <div class="account-wrapper">
        <div class="account-layout">
        <?= $content ?>
        </div>
    </div>

    <div class="login-footer">
        <div class="link-area clearfix">
            <ul class="list-inline">
                <li>关于本站</li>
            </ul>
        </div>
    </div>

    <?php JsBlock::begin() ?>
    <script>
        jQuery(function($) {
            $(document).ready(function() {
                $('#account-submit').on('click.account', submitAccountForm);
            });
        });
        function submitAccountForm() {
            $('#account-submit').off('click.account');
            var btn = $('#account-submit').text();
            $('#account-submit').html('<i class="fa fa-spinner fa-spin"></i>');
            setTimeout(function() {
                $('#account-form').submit();
                resetBtn(btn);
            }, 300);
        }
        function resetBtn(btn) {
            $('#account-submit').on('click.account', submitAccountForm);
            $('#account-submit').html(btn);
        }

    </script>
    <?php JsBlock::end() ?>
    <?php $this->endBody() ?>
</body>
<?php $this->endPage() ?>
