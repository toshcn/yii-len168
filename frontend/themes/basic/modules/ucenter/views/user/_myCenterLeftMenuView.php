<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */

use yii\helpers\Url;

/* @var $this yii\web\View */
$this->params['route'] = $this->context->route;

/**
 * 会员中心左边导航菜单
 */
?>

<div class="left-center-nav">
    <div class="center-nav-menu">
        <h4>个人中心</h4>
        <ul class="list-unstyled">
            <li class="<?= $this->params['route'] == 'ucenter/user/posts' ? 'cur' : ''; ?>">
                <a href="<?= Url::to(['/ucenter/user/posts'], true); ?>">我的文章</a>
                <div class="col-md-12 nav-span no-p-l"><span></span></div>
            </li>
            <li class="<?= $this->params['route'] == 'ucenter/user/detail' ? 'cur' : ''; ?>">
                <a href="<?= Url::to(['/ucenter/user/detail'], true); ?>">个人资料</a>
                <div class="col-md-12 nav-span no-p-l"><span></span></div>
            </li>
            <li class="<?= $this->params['route'] == 'ucenter/user/comments' ? 'cur' : ''; ?>">
                <a href="<?= Url::to(['/ucenter/user/comments'], true); ?>">我的评论</a>
                <div class="col-md-12 nav-span no-p-l"><span></span></div>
            </li>
            <li class="<?= $this->params['route'] == 'ucenter/user/messages' ? 'cur' : ''; ?>">
                <a href="<?= Url::to(['/ucenter/user/messages'], true); ?>">我的信息</a>
                <div class="col-md-12 nav-span no-p-l"><span></span></div>
            </li>
            <li class="<?= $this->params['route'] == 'ucenter/user/friends' ? 'cur' : ''; ?>">
                <a href="<?= Url::to(['/ucenter/user/friends'], true); ?>">我的关注</a>
                <div class="col-md-12 nav-span no-p-l"><span></span></div>
            </li>
            <li class="<?= $this->params['route'] == 'ucenter/user/followers' ? 'cur' : ''; ?>">
                <a href="<?= Url::to(['/ucenter/user/followers'], true); ?>">我的粉丝</a>
                <div class="col-md-12 nav-span no-p-l"><span></span></div>
            </li>
            <li class="hide <?= $this->params['route'] == 'ucenter/user/invite' ? 'cur' : ''; ?>">
                <a href="<?= Url::to(['/ucenter/user/invite'], true); ?>">邀请注册</a>
                <div class="col-md-12 nav-span no-p-l"><span></span></div>
            </li>
            <li class="<?= $this->params['route'] == 'ucenter/user/info' ? 'cur' : ''; ?>">
                <a href="<?= Url::to(['/ucenter/user/info'], true); ?>">修改资料</a>
                <div class="col-md-12 nav-span no-p-l"><span></span></div>
            </li>
            <li class="<?= $this->params['route'] == 'ucenter/user/link-oauth' ? 'cur' : ''; ?>">
                <a href="<?= Url::to(['/ucenter/user/link-oauth'], true); ?>">帐号绑定</a>
                <div class="col-md-12 nav-span no-p-l"><span></span></div>
            </li>
            <li class="<?= $this->params['route'] == 'ucenter/user/avatar' ? 'cur' : ''; ?>">
                <a href="<?= Url::to(['/ucenter/user/avatar'], true); ?>">上传头像</a>
                <div class="col-md-12 nav-span no-p-l"><span></span></div>
            </li>

            <li class="<?= $this->params['route'] == 'ucenter/user/pay-qrcode' ? 'cur' : ''; ?>">
                <a href="<?= Url::to(['/ucenter/user/pay-qrcode'], true); ?>">收款二维码</a>
                <div class="col-md-12 nav-span no-p-l"><span></span></div>
            </li>
        </ul>
    </div>

</div>

<?php \common\widgets\JsBlock::begin(); ?>
<script>
    $(document).ready(function() {
        var cur = $('.center-nav-menu>ul').find('li.cur');
        var ul = cur.parent();

        ul.mouseover(function() {
            ul.find('li.cur').removeClass('cur');
        });
        ul.mouseleave(function() {
            ul.find('li.cur').removeClass('cur');
            cur.addClass('cur');
        });
    });
</script>
<?php \common\widgets\JsBlock::end();?>