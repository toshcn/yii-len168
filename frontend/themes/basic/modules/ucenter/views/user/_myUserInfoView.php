<?php
/**
 * @link http://www.shuimugua.com/
 * @copyright Copyright (c) 2015 shuimugua.com
 * @license
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
?>

<div class="user-face">
    <div class="user-head">
        <div class="uid">ID: <?= $userDetail['uid'] ?></div>
        <img class="img-circle circle-border" src="<?= $userDetail['head'] ?>" width="120" alt="">
        <div class="name">
            <h4>
                <?php
                switch ($userDetail['sex']) {
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
                <?= Html::encode($userDetail['nickname']) ?>
                <?php if ($userDetail['isauth']) { ?>
                    <span class="green" title="已认证"><i class="fa fa-shield"></i></span>
                <?php } else { ?>
                    <span class="gray" title="未认证"><i class="fa fa-shield"></i></span>
                <?php } ?>
            </h4>
        </div>
    </div>
    <div class="user-info">
        <div class="user-motto">
            <h5 class="gray"><?= Html::encode($userDetail['motto']) ?></h5>
        </div>
        <div class="user-wealth">
            <ol class="wealth">
                <li>
                    <span class="num"><?= $userDetail['hp'] ?></span>
                    <span class="text">HP</span>
                </li>
                <li class="center">
                    <span class="num"><?= $userDetail['crystal'] ?></span>
                    <span class="text">水晶</span>
                </li>
                <li>
                    <span class="num"><?= $userDetail['golds'] ?></span>
                    <span class="text">金币</span>
                </li>
            </ol>
            <div class="friend">
                <span class="label label-warning bold">粉丝: <em class="num"><?= $userDetail['followers'] ?></em> </span>
            </div>
            <div class="follower">
                <span class="label label-warning bold">关注: <em class="num"><?= $userDetail['friends'] ?></em> </span>
            </div>
            <div class="post">
                <span class="label label-info bold">文章: <em class="num"><?= $userDetail['posts'] ?></em> </span>
            </div>
            <div class="comment">
                <span class="label label-info bold">评论: <em class="num"><?= $userDetail['comments'] ?></em> </span>
            </div>
        </div>
    </div>
</div>