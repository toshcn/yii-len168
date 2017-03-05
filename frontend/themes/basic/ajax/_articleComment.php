<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\models\Comments;

/* @var $this \yii\web\View */
?>
<?php foreach ($comments as $key => $comm) {
    $replyRows = count($comm['replies']);
    switch ($comm['stand']) {
        case 1:
            $commStand = 'left-comment apps-comment';
            break;
        case -1:
            $commStand = 'right-comment opps-comment';
            break;
        default:
            $commStand = '';
            break;
    }
    $comm['user']['nickname'] = Html::encode($comm['user']['nickname']);
    $comm['os'] = Html::encode($comm['os']);
?>
<div class="social-vote">
    <div class="social-comment <?= $commStand ?>">
        <div class="social-comment-ibox" name="comment-<?= $comm['commentid'] ?>" data-rows="<?= $replyRows ?>">
            <div class="author author-widget comment-author">
                <a href="<?= Url::to(['/ucenter/user/detail', 'uid' => $comm['user']['uid']], true); ?>">
                    <img class="img-circle" src="<?= $comm['user']['head']; ?>" alt="image">
                </a>
                <div class="author-info-box  animated flipInY" data-author="<?= $comm['user']['uid'] ?>">
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
                    <a href="javascript:;"><?= $comm['user']['nickname'] ?></a>
                    <?php if ($comm['user']['isauth']) { ?>
                        <span class="gray" title="未认证"><i class="fa fa-shield"></i></span>
                    <?php } else { ?>
                        <span class="green" title="已认证"><i class="fa fa-shield"></i></span>
                    <?php } ?>
                    <time class="timeago gray" datetime="<?= $comm['comment_at'] ?>" title="<?= $comm['comment_at'] ?>"><?= $comm['comment_at'] ?></time>
                    <span class="gray">来自: <?= $comm['os'] ?></span>

                    <em class="comment-floor"><?= $key+1 ?>F</em>
                </div>
                <div class="comment-hp">
                <?php $comm['progress'] = $comm['hp'] < Comments::DEFAULT_HP ? $comm['hp'] / Comments::DEFAULT_HP * 100 : 100; ?>
                    <div class="progress progress-striped active" title="生命值">
                        <div style="width: <?= $comm['progress'] ?>%" aria-valuemax="<?= Comments::DEFAULT_HP ?>" aria-valuemin="0" aria-valuenow="<?= $comm['progress'] ?>" role="progressbar" class="progress-bar progress-bar-success" name="comment-hp-<?= $comm['commentid'] ?>">
                            <span class="sr-only">
                                HP(<b name="hp-num-<?= $comm['commentid'] ?>"><?= $comm['hp'] ?></b>)
                            </span>
                        </div>
                    </div>
                </div>
                <div class="comment-word" name="content-md" id="md-<?= $comm['commentid'] ?>"><?= Html::encode($comm['content']) ?></div>
                <div class="comment-footer">
                    <small class="green">
                        <i class="fa fa-thumbs-o-up"></i>点赞(<b name="comment-apps-<?= $comm['commentid'] ?>"><?= $comm['apps'] ?></b>)
                    </small>
                    <small class="red">
                        <i class="fa fa-thumbs-o-down"></i>吐槽(<b name="comment-opps-<?= $comm['commentid'] ?>"><?= $comm['opps'] ?></b>)
                    </small>
                    <?php if (Yii::$app->user->getId() != $comm['user']['uid']) { ?>
                        <a href="javascript:;" class="small" name="reply-btn" data-iscomment="1" reply-author="<?= $comm['user']['nickname'] ?>" reply-to="<?= $comm['user']['uid'] ?>" reply-comment= "<?= $comm['commentid'] ?>">
                            回复<i class="fa fa-mail-reply"></i>
                        </a>
                    <?php }?>
                </div>
            </div>
        </div>
        <?php if ($comm['replies']) { ?>
            <div class="reply-more">
                <a name="js-reply-more" data-page="0" data-comment="<?= $comm['commentid'] ?>" data-where="comment">加载更多</a>
            </div>
        <?php }?>
    </div>
</div>
<?php } ?>

<div class="pages-nav align-center">
    <?php echo LinkPager::widget([
        'linkOptions' => ['name' => 'pagination'],
        'pagination' => $pagination,
    ]);?>
</div>