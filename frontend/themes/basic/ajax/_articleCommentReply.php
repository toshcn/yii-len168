<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */


use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
foreach ($replies as $key => $reply) {
    switch ($reply['stand']) {
        case 1:
            $standStr = '<small class="green"><i class="fa fa-thumbs-o-up"></i>点赞</small>';
            $standClass = 'left-reply apps-reply';
            break;
        case -1:
            $standStr = '<small class="red"><i class="fa fa-thumbs-o-down"></i>吐槽</small>';
            $standClass = 'right-reply opps-reply';
            break;
        default:
            $standStr = $standClass = '';
            break;
    }

    $reply['user']['nickname'] = Html::encode($reply['user']['nickname']);
    $reply['os'] = Html::encode($reply['os']);
?>

    <div class="reply <?= $standClass ?>" id="reply-<?= $reply['replyid'] ?>" data-node="reply">
        <div class="comment-author author author-widget">
            <a href="<?= Url::to(['/ucenter/user/detail', 'uid' => $reply['user']['uid']], true); ?>">
                <img class="img-circle" src="<?= $reply['user']['head']; ?>" alt="image">
            </a>
            <div class="author-info-box  animated flipInY" data-author="<?= $reply['user']['uid'] ?>">
                <div class="author-info-loading">
                    <div class="sk-spinner sk-spinner-wave">
                        <div class="sk-rect1"></div>
                        <div class="sk-rect2"></div>
                        <div class="sk-rect3"></div>
                        <div class="sk-rect4"></div>
                        <div class="sk-rect5"></div>
                    </div> 加载中
                </div>
            </div>
        </div>
        <div class="comment-body">
            <div class="comment-head">
                <em class="reply-floor"><?= $key+ $index; ?>#</em>
                <span class="author author-text author-widget">
                    <a href="<?= Url::to(['/ucenter/user/detail', 'uid' => $reply['reply_to']], true); ?>">@<?= Html::encode($reply['replyTo']['nickname']) ?>
                    </a>
                    <div class="author-info-box  animated flipInY" data-author="<?= $reply['reply_to'] ?>" >
                        <div class="author-info-loading">
                            <div class="sk-spinner sk-spinner-wave">
                                <div class="sk-rect1"></div>
                                <div class="sk-rect2"></div>
                                <div class="sk-rect3"></div>
                                <div class="sk-rect4"></div>
                                <div class="sk-rect5"></div>
                            </div> 加载中
                        </div>
                    </div>
                </span>
                <span class="nickname">
                    <a href="javascript:;" id="reply-author-<?= $reply['user']['uid'] ?>"><?= $reply['user']['nickname'] ?></a>
                </span>
                <?php if ($reply['user']['isauth']) { ?>
                    <span class="auth gray" title="未认证"><i class="fa fa-shield"></i></span>
                <?php } else { ?>
                    <span class="auth green" title="已认证"><i class="fa fa-shield"></i></span>
                <?php } ?>
                <time class="timeago gray" datetime="<?= $reply['reply_at'] ?>"><?= $reply['reply_at'] ?></time>
                <span class="os gray">来自: <?= $reply['os'] ?></span>
            </div>

            <div class="comment-word">
                <div name="content-md" id="<?= $where ?>-md-<?= $commentid . '-' . $reply['replyid'] ?>"><?= Html::encode($reply['content']); ?></div>
            </div>
            <div class="comment-footer"><?= $standStr ?>
                <?php if (Yii::$app->user->getId() != $reply['user']['uid']) { ?>
                    <a href="javascript:;" class="small" name="reply-btn" data-iscomment="0" reply-author="<?= $reply['user']['nickname'] ?>" reply-to="<?= $reply['user']['uid'] ?>" reply-comment= "<?= $commentid ?>">
                        回复<i class="fa fa-mail-reply"></i>
                    </a>
                <?php }?>
            </div>
        </div>
    </div>
<?php }?>