<?php
/**
 * @link http://www.len168.com/
 * @copyright Copyright (c) 2015 len168.com
 * @license http://www.len168.com/license/
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\Comments;

/**
 * 文章我的点评 视图
 */
/** @var $this yii\web\view */
?>

<?php if (!$myComment) { ?>
    <?php if ($canComment) { ?>
        <div class="comment-area">
            <h3>我要点评</h3>
            <hr>
            <?php if (Yii::$app->user->isGuest) { ?>

            <div class="send-comment-login">
                <button type="button" class="btn btn-info" id="send-comment-login">登录后评论</button>
            </div>

            <?php } else { ?>

                <div class="send-comment-form">
                    <div class="comment-author author author-widget">
                        <a class="">
                            <img class="img-circle" src="<?= Yii::$app->user->getIdentity()->head; ?>" alt="image" width="50" height="50">
                        </a>
                        <div class="author-info-box js-uinfo-box animated flipInY" data-author="<?= Yii::$app->user->getId() ?>">
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
                    <div class="comment-editer">
                    <?php $form = ActiveForm::begin([
                        'id' => 'send-comment-form',
                    ]);?>
                        <div class="form-group comment-editer-ibox">
                            <textarea class="form-control" name="send[comment]" rows="5" placeholder="评论内容不少于五个字！支持Markdown语法"></textarea>
                            <div class="word-len">
                                <span class="pull-left">说说你的看法, 请文明用语，遵守法律法规！</span>
                                你已输入<b id="comment-length">0</b>个字，还可以输入<b id="comment-can-length"><?= Comments::TEXT_MAX_LENGHT ?></b>个字！
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="radio-inline green">
                                <input type="radio" name="send[stand]" value="1" checked>
                                <i class="fa fa-thumbs-o-up"></i>点赞
                            </label>
                            <label class="radio-inline red">
                                <input type="radio" name="send[stand]" value="-1">
                                <i class="fa fa-thumbs-o-down"></i>吐槽
                            </label>
                            <button class="btn btn-xs btn-success pull-right" type="button" id="send-comment">发表</button>
                        </div>
                    <?php ActiveForm::end(); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php } else { ?>
        <div class="comment-area">
            <h3>我要点评</h3>
            <hr>
            <div class="send-comment-login">
                评论已关闭
            </div>
        </div>
        <?php } ?>
    <?php } else { ?>
        <div class="my-comment" id="my-comment">
            <h3>我的<?= $myComment['standStr'] ?></h3>
            <hr>
            <div class="social-vote">
                <div class="social-comment">
                    <div class="social-comment-ibox" name="my-<?= $myComment['commentid']?>"  data-rows="<?= $myComment['replies']?>">
                        <div class="comment-author author author-widget">
                            <a href="<?= Url::to(['/ucenter/center/detail', 'uid' => $myComment['user']['uid']], true); ?>">
                                <img class="img-circle" src="<?= $myComment['user']['head'] ?>" alt="image">
                            </a>
                            <div class="author-info-box js-uinfo-box animated flipInY" data-author="<?= $myComment['user']['uid'] ?>">
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
                                <a href="javascript:;"><?= Html::encode($myComment['user']['nickname'])?></a>
                                <?php if ($myComment['user']['isauth']) { ?>
                                    <span class="gray" title="未认证"><i class="fa fa-shield"></i></span>
                                <?php } else { ?>
                                    <span class="green" title="已认证"><i class="fa fa-shield"></i></span>
                                <?php } ?>
                                <time class="timeago gray" datetime="<?= $myComment['comment_at'] ?>" title="<?= $myComment['comment_at'] ?>"><?= $myComment['comment_at'] ?></time>
                                <span class="gray">来自: <?= Html::encode($myComment['os']) ?></span>
                                <em class="comment-floor">楼主</em>
                            </div>
                            <div class="comment-hp">
                                <div class="progress progress-striped active" title="生命值">
                                    <div style="width: <?= $myComment['progress'] ?>%" aria-valuemax="<?= Comments::DEFAULT_HP ?>" aria-valuemin="0" aria-valuenow="<?= $myComment['progress'] ?>" role="progressbar" class="progress-bar progress-bar-success" name="comment-hp-<?= $myComment['commentid'] ?>">
                                        <span class="sr-only">HP(<b name="hp-num-<?= $myComment['commentid'] ?>"><?= $myComment['hp'] ?></b>)
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="comment-word" name="content-md" id="my-md-<?= $myComment['commentid'] ?>"><?= Html::encode($myComment['content']) ?></div>
                            <div class="comment-footer">
                                <small class="green">
                                    <i class="fa fa-thumbs-o-up"></i>
                                    点赞(<b name="comment-apps-<?= $myComment['commentid'] ?>"><?= $myComment['apps'] ?></b>)
                                </small>
                                <small class="red">
                                    <i class="fa fa-thumbs-o-down"></i>
                                    吐槽(<b name="comment-opps-<?= $myComment['commentid'] ?>"><?= $myComment['opps'] ?></b>)
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($myComment['replies']) { ?>
                    <div class="reply-more">
                        <a name="js-reply-more" data-page="0" data-comment="<?= $myComment['commentid'] ?>" data-where="my">加载更多</a>
                    </div>
                <?php }?>
            </div>

        </div>
<?php } ?>