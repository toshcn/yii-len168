<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
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
        <h4>个人资料</h4>
        <ul class="list-unstyled">
            <li class="<?= $this->params['route'] == 'ucenter/user/detail' ? 'cur' : ''; ?>">
                <a href="<?= Url::to(['/ucenter/user/detail', 'uid' => $uid], true); ?>">TA的资料</a>
                <div class="col-md-12 nav-span no-p-l"><span></span></div>
            </li>
            <li class="<?= $this->params['route'] == 'ucenter/user/posts' ? 'cur' : ''; ?>">
                <a href="<?= Url::to(['/ucenter/user/posts', 'uid' => $uid], true); ?>">TA的文章</a>
                <div class="col-md-12 nav-span no-p-l"><span></span></div>
            </li>
            <li class="<?= $this->params['route'] == 'ucenter/user/friends' ? 'cur' : ''; ?>">
                <a href="<?= Url::to(['/ucenter/user/friends', 'uid' => $uid], true); ?>">TA的关注</a>
                <div class="col-md-12 nav-span no-p-l"><span></span></div>
            </li>
            <li class="<?= $this->params['route'] == 'ucenter/user/followers' ? 'cur' : ''; ?>">
                <a href="<?= Url::to(['/ucenter/user/followers', 'uid' => $uid], true); ?>">TA的粉丝</a>
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