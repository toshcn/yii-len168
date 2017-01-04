<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this \yii\web\View */

$author['nickname'] = Html::encode($author['nickname']);
?>
<div class="author-info">
    <a href="<?= Url::to(['/ucenter/user/detail', 'uid' => $author['uid']], true); ?>" title="Ta的主页" target="_blank"><img class="img-circle" src="<?= $author['head'] ?>" alt=""></a>
    <h4>
        <?php
        switch ($author['sex']) {
            case -1:
                echo '<i class="fa fa-transgender green" title="保密"></i>';
                break;
            case 1:
                echo '<i class="fa fa-mars green" title="男"></i>';
                break;
            case 0:
                echo '<i class="fa fa-venus green" title="女"></i>';
                break;
        }?>
        <?= $author['nickname'] ?>
        <?php if ($author['isauth']) { ?>
            <span class="auth gray" title="未认证"><i class="fa fa-shield"></i></span>
        <?php } else { ?>
            <span class="auth green" title="已认证"><i class="fa fa-shield"></i></span>
        <?php } ?>
     </h4>
    <h5 class="gray"><?= Html::encode($author['motto']) ?></h5>
    <ol class="wealth">
        <li>
            <span class="num"><?= $author['hp'] ?></span>
            <span class="text">HP</span>
        </li>
        <li class="center">
            <span class="num"><?= $author['crystal'] ?></span>
            <span class="text">水晶</span>
        </li>
        <li>
            <span class="num"><?= $author['golds'] ?></span>
            <span class="text">金币</span>
        </li>
    </ol>
    <p class="post">
    <?php if ($author['post']) { ?>
        <a href="<?= Url::to(['/article/detail', 'id' => $author['post']['postid']], true); ?>"  target="_blank"><?= Html::encode($author['post']['title']) ?></a>
    <?php } ?>
    </p>
    <div class="conperson">
        <?php if ($author['uid'] == $myself) {?>
            <div class="pull-left">
                <label class="label label-warning" type="label">粉丝<?= $author['followers'] ?></label>
            </div>
            <div class="pull-right">
                <label class="label label-warning" type="label">关注<?= $author['friends'] ?></label>
            </div>

            <div class="pull-left">
                <label class="label label-success" type="label">文章<?= $author['posts'] ?></label>
            </div>
            <div class="pull-right">
                <label class="label label-success" type="label">评论<?= $author['comments'] ?></label>
            </div>
        <?php } else { ?>
            <div class="pull-left">
                <?php if ($author['isfollower']) { ?>
                    <button class="btn btn-success btn-sm" type="button" name="remove-follower-<?= $author['uid'] ?>" data-user="<?= $author['uid'] ?>">
                    <i class="fa fa-minus"></i> 取消关注</button>
                <?php } else { ?>
                    <button class="btn btn-success btn-sm" type="button" name="add-follower-<?= $author['uid'] ?>" data-user="<?= $author['uid'] ?>">
                    <i class="fa fa-plus"></i> 关注</button>
                <?php } ?>
            </div>
            <div class="pull-right">
                <button class="btn btn-success btn-sm" type="button" name="send-message-btn" data-user="<?= $author['uid'] ?>" data-at="<?= $author['nickname'] ?>">
                <i class="fa fa-commenting"></i> 纸条</button>
            </div>
        <?php } ?>

    </div>
</div>