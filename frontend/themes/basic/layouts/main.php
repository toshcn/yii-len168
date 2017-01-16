<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use common\models\NavMenu;
use yii\bootstrap\ActiveForm;
use frontend\assets\SiteAsset;
use yii\widgets\Menu;

/* @var $this \yii\web\View */
/* @var $content string */

SiteAsset::register($this);
/* @var $this->params['rememberName'] for remember user name*/
$cookie = Yii::$app->request->cookies;
$this->params['rememberName'] = $cookie->getValue('LOGINNAME_REMEMBER');

$navMenuObj = new NavMenu(['navMenuId' => 1]);
$this->params['navMenus'] = $navMenuObj->getSortNavMenuItems();

$navMenuObj->navMenuId = 2;
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
    <title><?= Html::encode($this->title) . '|' . Yii::$app->name ?></title>
    <!-- 网站描述 -->
    <meta name="description" content="">
    <!-- 网站SEO关键词 -->
    <meta name="keywords" content="">
    <?= Html::csrfMetaTags() ?>
    <?php
        $this->head();
        $this->params['bodyClass'] = isset($this->params['bodyClass']) ? $this->params['bodyClass'] : '';
    ?>
    <!-- <link href="/public/css/base.css" rel="stylesheet">
    <link href="/public/css/site.css" rel="stylesheet"> -->
    <script type="text/javascript">
        var _authorWidget = new Array();
        var _authorWidgetUrl = "<?= Url::to(['/ajax/author-widget']) ?>";
        var _isGuest = <?= intval(Yii::$app->user->isGuest) ?>;
        var _csrf = "<?= Yii::$app->getRequest()->getCsrfToken() ?>";
        var _imageHost = '<?= Yii::$app->params['image.host'] ?>';
        var _addFollowerUrl = "<?= Url::to(['/ucenter/user/add-follower'], true)?>";
        var _removeFollowerUrl = "<?= Url::to(['/ucenter/user/remove-follower'], true)?>";
    </script>
</head>
<body class="<?= $this->params['bodyClass'] ?> fixed-nav">
    <?php $this->beginBody() ?>
    <div class="top-wrapper navbar-fixed-top" id="navbar-header">
        <div class="top-header w-center">
            <a id="top-logo" href="<?= Yii::$app->homeUrl ?>">LEN168</a>
            <a class="hide" id="logo-gif" href="<?= Yii::$app->homeUrl ?>"></a>
            <nav class="top-nav hidden-xs" role="navigation">
                <?php
                    echo Menu::widget([
                        'options' => ['class' => ['list-inline menu']],
                        'submenuTemplate' => "\n<ul class=\"subnav\">\n{items}\n</ul>\n",
                        'encodeLabels' => false,
                        'activateParents' => true,
                        //'route' => 'article/detail',//$this->params['route'],
                        'items' => $this->params['navMenus'],
                    ]);
                ?>
            </nav>
            <div class="top-search hidden-xs hidden-sm">
                <form action="" method="post">
                    <div class="input-group">
                        <input class="form-control" type="text" name="keyword" value="" placeholder="搜索您感兴趣的……">
                        <span class="input-group-btn">
                            <button class="btn btn-success" type="submit"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="top-uinfo pull-right">
                <ul class="list-inline">
                <?php
                if (Yii::$app->user->isGuest) {
                    echo '<li>' . Html::a('欢迎进来吐槽 请登录 ', null, ['href' => Url::to(['/ucenter/account/login'], Yii::$app->params['httpProtocol'])]) . '</li>';
                    echo '<li>' . Html::a('<i class="fa fa-user-plus"></i> 注册', null, ['href' => Url::to(['/ucenter/account/signup'], Yii::$app->params['httpProtocol'])]) . '</li>';
                } else {
                    echo '<li>' . Html::a('<i class="fa fa-user"></i> '.Yii::$app->user->identity->nickname, null, ['href' => Url::to(['/ucenter/user/index'], Yii::$app->params['httpProtocol'])]).'</li>';
                    echo '<li>' . Html::a('<i class="fa fa-power-off"></i> 退出', null, ['href' => Url::to(['/site/logout'], Yii::$app->params['httpProtocol']), 'data-method' => 'post']). '</li>';
                }
                ?>
                </ul>
            </div>
        </div>
    </div>
    <?= $content ?>

    <div class="backtop">
        <button type="button" class="btn btn-sm btn-info" id="backtop" title="返回顶部">
            <i class="fa fa-arrow-up"></i>
        </button>
    </div>
    <div class="footer">
        <div class="w-center">
            <div class="row">
                <div class="col-sm-12">
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
                </div>
                <div class="col-sm-12">
                    <div class="site-detail">
                        <p>
                            <?= Yii::$app->params['siteCopyright'] ?>
                            <a href="http://www.miitbeian.gov.cn" target="_blank" rel="nofollow"><?= Yii::$app->params['siteRecordNumber'] ?></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- login widget -->
    <div class="fade-layout" id="fade-layout" style="display:none"></div>
    <div class="login-widget" id="login-widget" style="display:none">
        <div class="login-widget-box">
            <?php $form = ActiveForm::begin([
                'id' => 'login-widget-form',
                'action' => Url::to(['/ucenter/account/ajax-login'], true),
            ]); ?>
                <div class="login-widget-title"></div>
                    <div class="login-widget-close">
                        <a class="close-icon" id="login-widget-close" title="关闭">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                <div class="login-widget-content" node-type="inner">
                    <div class="login-widget-tab">
                        <a class="cur" href="javascript:;">密码登录</a>
                        <a class="" href="javascript:;">扫码登录</a>
                    </div>
                    <div class="login-frame">
                        <div class="item error-item">
                            <span class="error-msg" id="error-msg"></span>
                        </div>
                        <div class="input-item input-icon input-icon-left">
                            <i class="smg-icon fa fa-user"></i>
                            <input type="text" class="form-control login-widget-input" name="LoginForm[username]" placeholder="昵称/邮箱/会员帐号" value="<?= $this->params['rememberName'] ?>">
                        </div>
                        <div class="input-item input-icon input-icon-left">
                            <i class="smg-icon fa fa-lock"></i>
                            <input type="password" class="form-control" name="LoginForm[password]" autocomplete="off">
                        </div>
                        <div class="item rememberme">
                            <label>
                                <input type="checkbox" name="LoginForm[rememberMe]" id="login-rememberme" value="1" checked="checked">记住用户名
                            </label>
                            <span class="pull-right">
                                <a target="_blank" href="#">忘记密码?</a>
                            </span>
                        </div>
                        <div class="item">
                            <a href="javascript:;" class="btn item-btn login-btn" id="login-widget-submit">
                                登录
                            </a>
                        </div>
                        <div class="item">
                            <a href="<?= Url::to(['/ucenter/account/signup']) ?>" class="btn btn-white item-btn signup-btn">
                                未有帐号？立即注册
                            </a>
                        </div>
                        <div class="item other-login-type">
                            <fieldset class="other-title">
                                <legend align="center" class="other-txt">其他方式登录</legend>
                            </fieldset>
                            <div class="sns-login">
                                <a href="" title="微博"><i class="fa fa-weibo icon-md"></i></a>
                                <a href="" title="QQ"><i class="fa fa-qq icon-qq"></i></a>
                                <a href="" title="微信"><i class="fa fa-weixin icon-md"></i></a>
                                <a href="" title="Github"><i class="fa fa-github icon-github"></i></a>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="LoginForm[os]" value="">
                    <input type="hidden" name="LoginForm[browser]" value="">
                </div>
            <?php ActiveForm::end(); ?>
        </div>

    </div>
    <div class="message-widget" id="message-widget" style="display:none">
        <div class="message-widget-box">
            <?php $form = ActiveForm::begin([
                'id' => 'message-widget-form',
                'action' => Url::to(['/ucenter/user/ajax-send-message'], true),
            ]); ?>
                <div class="message-widget-title">给<b id="message-at"></b>发纸条</div>
                <div class="message-widget-close">
                    <a class="close-icon" id="message-widget-close" title="关闭">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
                <div class="message-widget-content" node-type="inner">
                    <div class="message-frame">
                        <div class="form-group">
                            <textarea class="message-input form-control" rows="10" name="message[content]" id="" maxlength="255" placeholder="纸条最多255个字"></textarea>
                            <label class="message-input-label">注意：纸条只保留七天，过期后系统自动清除。</label>
                        </div>
                    </div>
                </div>
                <div class="message-widget-footer">
                    <div class="bottom-right">
                        <button type="button" class="btn btn-sm btn-success" id="send-message-btn">发送</button>
                    </div>
                </div>
                <input type="hidden" name="message[user]" id="message-to-user" value="">
            <?php ActiveForm::end(); ?>
        </div>

    </div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
