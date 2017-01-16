<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

use yii\helpers\Html;
use frontend\assets\AccountAsset;
use common\widgets\JsBlock;
use yii\widgets\Menu;
use common\models\NavMenu;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->params['bodyClass'] = isset($this->params['bodyClass']) ? $this->params['bodyClass'] : 'gray-bg';

AccountAsset::register($this);
$navMenuObj = new NavMenu(['navMenuId' => 2]);
$navMenuObj->setNavMenuItems();
$this->params['footerMenus'] = $navMenuObj->getFooterNavMenuItems();
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
            <?php
                echo Menu::widget([
                    'options' => ['class' => ['list-inline footer-menu']],
                    'submenuTemplate' => "",
                    'encodeLabels' => false,
                    'activateParents' => false,
                    //'route' => 'article/detail',//$this->params['route'],
                    'items' => $this->params['footerMenus'],
                    ]);
                //var_dump($this->params['footerMenus']);
            ?>
            <div class="site-detail">
                <p>
                    <?= Yii::$app->params['siteCopyright'] ?>
                    <a href="http://www.miitbeian.gov.cn" target="_blank" rel="nofollow"><?= Yii::$app->params['siteRecordNumber'] ?></a>
                </p>
            </div>
        </div>
    </div>

    <?php JsBlock::begin() ?>
    <script>
        var btn;
        jQuery(function($) {
            $(document).ready(function() {
                $('#account-submit').on('click.account', submitAccountForm);
                btn = $('#account-submit').text();
            });
        });
        function submitAccountForm() {
            $('#account-submit').off('click.account');

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
